<?php
include "../../conexionbd.php";
session_start();
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
date_default_timezone_set('America/Guatemala');

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



// Consulta SQL para obtener el total de entradas por cada lote y el total general de entradas por medicamento
$sql = "
  SELECT
    cr.id_recib AS ID,
    ia.item_id AS ItemID,
    CONCAT(ia.item_name, ' ', ia.item_grams) AS Medicamento,
    cr.fecha AS FechaSolicitud,
    cr.almacen AS SubAlmacen,
    cr.solicita AS Solicitan,
    GROUP_CONCAT(ea.existe_lote ORDER BY ea.existe_caducidad ASC) AS Lotes,
    GROUP_CONCAT(ea.existe_caducidad ORDER BY ea.existe_caducidad ASC) AS Caducidades,
    GROUP_CONCAT(ea.existe_qty ORDER BY ea.existe_caducidad ASC) AS TotalEntradasPorLote,
    GROUP_CONCAT(ea.existe_id ORDER BY ea.existe_caducidad ASC) AS ExisteIds,

    (SELECT SUM(existe_qty)
     FROM existencias_almacen
     WHERE item_id = cr.item_id) AS TotalEntradasGeneral,
    (SELECT SUM(entrega)
     FROM carrito_entradash
     WHERE id_recib = cr.id_recib) AS Entrega -- Sumar todos los valores de entrega para el id_recib
FROM
    cart_recib cr
LEFT JOIN
    existencias_almacen ea ON cr.item_id = ea.item_id AND ea.existe_qty > 0
JOIN
    item_almacen ia ON cr.item_id = ia.item_id
WHERE
    cr.confirma = 'SI'
GROUP BY
    cr.id_recib, ia.item_name, cr.fecha, cr.almacen, cr.solicita, ia.item_grams
ORDER BY
    ia.item_name, cr.id_recib,ia.item_grams, cr.fecha ASC;

";



$resultados = $conexion->query($sql);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $lotesSeleccionados = isset($_POST['lotes']) ? $_POST['lotes'] : [];
  $fechaActual = date('Y-m-d H:i:s');

  // Convertir la variable a JSON


  if (!empty($lotesSeleccionados)) {
    foreach ($lotesSeleccionados as $idRecib => $jsonData) {
      $data = json_decode($jsonData, true);

      if (json_last_error() === JSON_ERROR_NONE) {
        $lotes = $data['lotes'];
        $total = $data['total'];




        // Obtener datos de cart_recib
        $queryCartRecib = "SELECT item_id, solicita, almacen FROM cart_recib WHERE id_recib = ?";
        $stmtCartRecib = $conexion->prepare($queryCartRecib);
        $stmtCartRecib->bind_param('i', $idRecib);
        $stmtCartRecib->execute();
        $resultCartRecib = $stmtCartRecib->get_result();
        $cartRecibData = $resultCartRecib->fetch_assoc();
        $stmtCartRecib->close();


        if ($cartRecibData) {
          $solicitaCartRecib = intval($cartRecibData['solicita']);
          $almacenCartRecib = $cartRecibData['almacen'];
          $itemIdCartRecib = $cartRecibData['item_id'];

          // Consultar entregas actuales
          $queryEntregaActual = "
                      SELECT SUM(entrega) AS total_entregas
                      FROM carrito_entradash
                      WHERE id_recib = ?";
          $stmtEntregaActual = $conexion->prepare($queryEntregaActual);
          $stmtEntregaActual->bind_param('i', $idRecib);
          $stmtEntregaActual->execute();
          $resultEntregaActual = $stmtEntregaActual->get_result();
          $entregaActualData = $resultEntregaActual->fetch_assoc();
          $stmtEntregaActual->close();

          $entregaActual = $entregaActualData ? intval($entregaActualData['total_entregas']) : 0;
          $entregaTotal = $entregaActual + $total;

          // Validar si la entrega supera la cantidad solicitada
          if ($entregaTotal > $solicitaCartRecib) {
            echo "<script>
                          alert('Error: La cantidad total de entrega supera la cantidad solicitada.');
                          window.location.href = 'surtir_subalma.php';
                      </script>";
            exit;
          }




          foreach ($lotes as $lote) {
            $loteNombre = $lote['lote'];
            $cantidadLote = intval($lote['cantidad']);
            $existeId = $lote['existe_id'];



            // Verificar si el registro ya existe
            $checkQuery = "
                          SELECT COUNT(*) AS total
                          FROM carrito_entradash
                          WHERE id_recib = ? AND item_id = ? AND existe_lote = ?";
            $stmtCheck = $conexion->prepare($checkQuery);
            $stmtCheck->bind_param('iis', $idRecib, $itemIdCartRecib, $loteNombre);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            $rowCheck = $resultCheck->fetch_assoc();
            $registroExiste = intval($rowCheck['total']) > 0;
            $stmtCheck->close();



            // Consulta para obtener las existencias
            $selectExistenciasQuery = "SELECT existe_qty, existe_salidas, item_id FROM existencias_almacen WHERE existe_id = ?";
            $stmtSelect = $conexion->prepare($selectExistenciasQuery);
            $stmtSelect->bind_param('i', $existeId);
            $stmtSelect->execute();
            $stmtSelect->bind_result($existeQty, $existeSalidas, $itemIdCartRecib);
            $stmtSelect->fetch();
            $stmtSelect->close();


            // Calcular nuevos valores
            $nuevaExistenciaQty = $existeQty - $cantidadLote;
            $nuevaExistenciaSalidas = $existeSalidas + $cantidadLote;

            // Validar si hay suficiente stock
            if ($existeQty < $cantidadLote) {
              echo "<script>
                alert('Error: El lote \"$loteNombre\" no tiene suficiente stock. Disponible: $existeQty, requerido: $cantidadLote.');
                window.location.href = 'surtir_subalma.php';
                </script>";
              exit;
            }
            // Consulta para actualizar existencias
            $updateExistenciasQuery = "UPDATE existencias_almacen SET existe_qty = ?, existe_fecha=?, existe_salidas = ? WHERE existe_id = ?";
            $stmtUpdateExistencias = $conexion->prepare($updateExistenciasQuery);
            $stmtUpdateExistencias->bind_param('isii', $nuevaExistenciaQty, $fechaActual, $nuevaExistenciaSalidas, $existeId);
            $stmtUpdateExistencias->execute();
            $stmtUpdateExistencias->close();


            $queryExistencias = "
                          SELECT existe_qty, existe_salidas, item_id ,existe_caducidad
                          FROM existencias_almacen
                          WHERE existe_lote = ? AND item_id = ?";
            $stmtExistencias = $conexion->prepare($queryExistencias);
            $stmtExistencias->bind_param('si', $loteNombre, $itemIdCartRecib);
            $stmtExistencias->execute();
            $resultExistencias = $stmtExistencias->get_result();
            $existencia = $resultExistencias->fetch_assoc();
            $stmtExistencias->close();

            if ($existencia) {
              if (!$registroExiste && $total == $solicitaCartRecib) {
                $caducidad = $existencia['existe_caducidad'];


                // Insertar nuevo registro
                $insertQuery = "
                                INSERT INTO carrito_entradash
                                (id_recib, item_id, solicita, entrega, existe_lote, existe_caducidad, fecha, almacen)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtInsert = $conexion->prepare($insertQuery);
                $stmtInsert->bind_param(
                  'iiiissss',
                  $idRecib,
                  $itemIdCartRecib,
                  $solicitaCartRecib,
                  $cantidadLote,
                  $loteNombre,
                  $caducidad,
                  $fechaActual,
                  $almacenCartRecib
                );
                $stmtInsert->execute();
                $stmtInsert->close();
                // Marcar como entrega completa
                $updateParcialQuery = "UPDATE cart_recib SET parcial = 'NO' WHERE id_recib = ?";
                $stmtUpdateParcial = $conexion->prepare($updateParcialQuery);
                $stmtUpdateParcial->bind_param('i', $idRecib);
                $stmtUpdateParcial->execute();
                $stmtUpdateParcial->close();
                // Actualiza 'enviado' a 'SI'
                $updateQuery = "UPDATE cart_recib SET enviado = 'SI' WHERE id_recib = ?";
                $stmtUpdate = $conexion->prepare($updateQuery);
                $stmtUpdate->bind_param('i', $idRecib);
                $stmtUpdate->execute();
                $stmtUpdate->close();
              } else {
                if (!$registroExiste) {



                  $caducidad = $existencia['existe_caducidad'];

                  // Insertar nuevo registro
                  $insertQuery = "
                                    INSERT INTO carrito_entradash
                                    (id_recib, item_id, solicita, entrega, existe_lote, existe_caducidad, fecha, almacen)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                  $stmtInsert = $conexion->prepare($insertQuery);
                  $stmtInsert->bind_param(
                    'iiiissss',
                    $idRecib,
                    $itemIdCartRecib,
                    $solicitaCartRecib,
                    $cantidadLote,
                    $loteNombre,
                    $caducidad,
                    $fechaActual,
                    $almacenCartRecib
                  );
                  $stmtInsert->execute();
                  $stmtInsert->close();
                  // Marcar como entrega completa
                  $updateParcialQuery = "UPDATE cart_recib SET parcial = 'SI' WHERE id_recib = ?";
                  $stmtUpdateParcial = $conexion->prepare($updateParcialQuery);
                  $stmtUpdateParcial->bind_param('i', $idRecib);
                  $stmtUpdateParcial->execute();
                  $stmtUpdateParcial->close();
                  // Actualiza 'enviado' a 'SI'
                  $updateQuery = "UPDATE cart_recib SET enviado = 'SI' WHERE id_recib = ?";
                  $stmtUpdate = $conexion->prepare($updateQuery);
                  $stmtUpdate->bind_param('i', $idRecib);
                  $stmtUpdate->execute();
                  $stmtUpdate->close();
                } else {

                  if ($total + $entregaActual == $solicitaCartRecib) {
                    // Actualizar entrega en registro existente
                    $updateQuery = "
                                    UPDATE carrito_entradash
                                    SET entrega = entrega + ?
                                    WHERE id_recib = ? AND item_id = ? AND existe_lote = ?";
                    $stmtUpdate = $conexion->prepare($updateQuery);
                    $stmtUpdate->bind_param('iiis', $cantidadLote, $idRecib, $itemIdCartRecib, $loteNombre);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                  } else {
                    if ($total + $entregaActual == $solicitaCartRecib) {

                      $caducidad = $existencia['existe_caducidad'];

                      // Insertar nuevo registro
                      $insertQuery = "
                                      INSERT INTO carrito_entradash
                                      (id_recib, item_id, solicita, entrega, existe_lote, existe_caducidad, fecha, almacen)
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                      $stmtInsert = $conexion->prepare($insertQuery);
                      $stmtInsert->bind_param(
                        'iiiissss',
                        $idRecib,
                        $itemIdCartRecib,
                        $solicitaCartRecib,
                        $cantidadLote,
                        $loteNombre,
                        $caducidad,
                        $fechaActual,
                        $almacenCartRecib
                      );
                      $stmtInsert->execute();
                      $stmtInsert->close();
                    }
                  }
                }
              }
            } else {
              echo "<p>No se encontró el lote '$loteNombre' en existencias_almacen para item_id = $itemIdCartRecib</p>";
            }
          }
        } else {
          echo "<p>No se seleccionaron lotes.</p>";
        }
      }
    }
  }
  $conexion->close();

  echo "<script>
      alert('Datos insertados correctamente');
      window.location.href = 'surtir_subalma.php';
    </script>";
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
      integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<body>
<div class="container-fluid">
    <div class="container-moderno">

        <!-- Botón de regreso modernizado -->
        <div class="d-flex justify-content-start mb-4">
            <a href="kardex.php" class="btn-moderno btn-regresar">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
        <!-- Header principal modernizado -->
        <div class="header-principal">
            <div class="contenido-header">
                <div class="icono-header">
                    <i class="fas fa-arrow-up icono-principal"></i>
                </div>
        <center>SURTIR A LOS SUBALMACENES</center>
            </div>
        </div>
        <div class="texto en-espera">
            <span class="cuadro en-espera"></span> En espera de confirmar
        </div>

        <div class="texto entrega-parcial">
            <span class="cuadro entrega-parcial"></span> Entrega parcial
        </div>

        <div class="texto nuevo-surtimiento">
            <span class="cuadro nuevo-surtimiento"></span> Nuevo surtimiento
        </div>

    <div class="table-container">

      <form method="post" id="formSurtir" action="">
          <div class="tabla-contenedor">
              <div class="table-responsive">
                  <table class="table table-moderna">
                      <thead>
                      <tr>
              <th class="col-itemid"><i class="fas fa-hashtag"></i>
                <font color="white">IDMedicamento</font>
              </th>
              <th class="col-medicamentos"><i class="fas fa-pills"></i>
                <font color="white">Medicamento</font>
              </th>
              <th class="col-fecha"><i class="fas fa-calendar-alt"></i>
                <font color="white">Fecha de solicitud</font>
              </th>
              <th class="col-almacen"><i class="fa-solid fa-house"></i>
                <font color="white">Sub almacen</font>
              </th>
              <th class="col-solicitan"><i class="fa-regular fa-bell"></i>
                <font color="white">Solicitan</font>
              </th>
              <th class="col-parcial"><i class="fa-solid fa-truck"></i>
                <font color="white">Entrega Parcial</font>
              </th>
              <th class="col-parcial"><i class="fa-solid fa-paper-plane"></i>
                <font color="white">Enviar</font>
              </th>
              <th class="col-lote"><i class="fa-regular fa-layer-group"></i>
                <font color="white">Lote</font>
              </th>
              <th class="col-caducidad"><i class="fa-solid fa-calendar-days"></i>
                <font color="white">Caducidad</font>
              </th>
              <th class="col-existencias"><i class="fa-brands fa-stack-exchange"></i>
                <font color="white">Existencias.Lote/Total</font>
              </th>
              <th class="col-surtir"><i class="fa-solid fa-file-import"></i>
                <font color="white">Surtir</font>
              </th>

              <th class="col-seleccionar">
                <input type="checkbox" id="check-todos" style="width: 20px; height: 20px;" onclick="marcarTodos(this)">
              </th>
            </tr>
          </thead>

          <tbody>
            <?php
            if ($resultados && $resultados->num_rows > 0) {
              $index = 1;
              while ($fila = $resultados->fetch_assoc()) {
                $lotes = explode(',', $fila['Lotes']);
                $caducidades = explode(',', $fila['Caducidades']);
                $entradasPorLote = explode(',', $fila['TotalEntradasPorLote']);
                $totalGeneral = $fila['TotalEntradasGeneral'];
                $idRecib = $fila['ID'];
                $solicita = $fila['Solicitan'];
                $entrega = $fila['Entrega'];
                $existeIds = explode(',', $fila['ExisteIds']); // Añadido para obtener los existe_id



                // Determinar el color de fondo según las condiciones
                if ($solicita === $entrega) {
                  $rowStyle = "style='background-color: #8eb5f0ff; color: black;'";
                } elseif ($entrega > 0) {
                  $rowStyle = "style='background-color: #b3cef7ff; color: black;'";
                } else {
                  $rowStyle = "style='background-color: #e6f0ff; color: black;'";
                }

                echo "<tr $rowStyle>";
                echo "<td class='col-itemid'>{$fila['ItemID']}</td>";
                echo "<td class='col-medicamentos'>{$fila['Medicamento']}</td>";
                echo "<td class='col-fecha'>{$fila['FechaSolicitud']}</td>";
                echo "<td class='col-almacen'>{$fila['SubAlmacen']}</td>";
                echo "<td class='col-solicitan'>{$fila['Solicitan']}</td>";
                echo "<td class='col-parcial'>{$entrega}</td>";
                echo "<td class='col-parcial'>";
                if ($solicita !== $entrega) {
                  echo "
                        <span id='enviar-{$idRecib}'>0</span>
                        <button type='button' onclick='borrarValorEnviar({$idRecib})' class='borrar-btn'>Borrar</button>
                    ";
                } else {
                }
                echo "</td>";

                echo "<td class='col-lote'>";
                if ($solicita !== $entrega) {
                  echo "
                    <select name='lote[{$fila['ID']}]' id='lote-{$fila['ID']}' class='lote-select' onchange=\"actualizarExistencias(this, {$totalGeneral}, {$index})\">
                        <option value='' disabled selected>lote</option>
                ";
                  foreach ($lotes as $key => $lote) {
                    $caducidad = $caducidades[$key];
                    $entradas = $entradasPorLote[$key];
                    $existeId = $existeIds[$key]; // Obtener el existe_id correspondiente
                    echo "<option value='{$lote}' data-existe-id='{$existeId}' data-lote='{$lote}' data-entradas='{$entradas}' data-caducidad='{$caducidad}'>{$lote}</option>";
                  }
                  echo "</select>";
                } else {
                }
                echo "</td>";

                echo "<td class='col-caducidad' id='caducidad_{$index}'>";
                if ($solicita !== $entrega) {
                  echo "{$caducidades[0]}";
                } else {
                }
                echo "</td>";
                echo "<td id='existencias_{$index}' class='col-existencias'>";
                if ($solicita !== $entrega) {
                  echo "{$entradasPorLote[0]} / {$totalGeneral}";
                } else {
                }
                echo "</td>";

                // Campo de entrada
                echo "<td class='col-surtir'>";
                if ($solicita !== $entrega) {
                  echo "
        <input
            type='number'
            name='cantidad_surtir[{$idRecib}]'
            id='cantidad-surtir-{$idRecib}'
            min='1'
            max='{$solicita}'
            oninput='validarCantidad(this, {$solicita}, {$entrega});'
            class='input-uniform cantidad-input'>
    ";
                } else {
                }
                echo "</td>";


                echo "</td>";
                echo "<td class='col-seleccionar'>";
                if ($solicita !== $entrega) {
                  echo "<input type='checkbox' name='items_seleccionados[]' value='{$fila['ID']}' style='width: 20px; height: 20px;' class='item-checkbox' data-id='{$fila['ID']}'>";
                } else {
                  echo "<span style='color: white;'></span>"; // Opcional: texto o indicador si quieres mostrar algo
                }
                echo "</td>";

                echo "</tr>";
                $index++;
              }
            } else {
              echo "<tr><td colspan='11' style='text-align: center;'>No hay datos disponibles</td></tr>";
            }
            ?>
          </tbody>
        </table>

        <div style="display: flex; flex-direction: column; align-items: flex-end; padding-top: 10px; gap: 10px;">

          <button type="button" class="btn-moderno enviar" onclick="agregarSeleccionados()" style="margin-right: 10px;">
            Agregar seleccionados
          </button>

          <button type="submit" id="btnEnviar" class="btn-moderno enviar" style="margin-right: 10px;">
            Enviar
          </button>
          <div id="datosSurtirOutput" style="margin-top: 20px; padding: 10px; display: flex; justify-content: center; align-items: center; width: 100%;">
          </div>
        </div>
      </form>
    </div>
  </section>
</body>

</html>
<script>
  // Actualizar existencias y caducidad al cambiar de lote
  function actualizarExistencias(selectElement, totalGeneral, index) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const totalEntradasLote = selectedOption.getAttribute('data-entradas');
    const caducidad = selectedOption.getAttribute('data-caducidad');

    // Actualizar la celda de existencias con el valor del lote seleccionado y el total general
    document.getElementById('existencias_' + index).innerText = totalEntradasLote + ' / ' + totalGeneral;

    // Actualizar la celda de caducidad con la fecha de caducidad del lote seleccionado
    document.getElementById('caducidad_' + index).innerText = caducidad;
  }
</script>

<script>
  // Objeto global para almacenar las cantidades surtidas por idRecib
  let datosSurtir = {};



  function validarCantidad(input, solicita, entrega) {
    const id = input.id.split('-')[2]; // Obtener el ID desde el input
    const valor = parseInt(input.value) || 0;

    // Obtener el total acumulado en datosSurtir para este ID, o inicializarlo en 0 si no existe
    const totalActual = datosSurtir[id]?.total || 0;

    // Calcular el resto que aún se puede surtir
    const entregaRestante = solicita - totalActual;

    // Validar la cantidad ingresada
    if (valor > entregaRestante || valor < 1) {
      alert("La cantidad debe estar entre 1 y " + entregaRestante + ".");
      input.value = ''; // Limpiar el valor si es inválido
    }
  }


  function actualizarVistaDatosSurtir() {
    const output = document.getElementById('datosSurtirOutput');
    output.innerHTML = ''; // Limpia el contenido previo

    // Verifica si hay datos en `datosSurtir`
    if (Object.keys(datosSurtir).length === 0) {
      output.textContent = 'No hay datos surtidos actualmente.';
      return;
    }

    // Construye una tabla para mostrar los datos
    let table = '<table style="width: 40%; border-collapse: collapse; text-align: center; float: right; margin-right: 20px;">';
    table += `
        <thead>
            <tr style="background-color: #5a5cd8ff; color: white;">
                <th style="border: 1px solid #ccc; padding: 8px; width: 1%;">ID</th>
                <th style="border: 1px solid #ccc; padding: 8px; width: 1%;">Total</th>
                <th style="border: 1px solid #ccc; padding: 8px; width: 2%;">Lotes</th>
            </tr>
        </thead>
        <tbody>
    `;

    for (const [id, datos] of Object.entries(datosSurtir)) {
      // Construye la lista de lotes para este ID
      const lotes = datos.lotes.map(lote =>
        `Lote: ${lote.lote}, Cantidad: ${lote.cantidad}`
      ).join('<br>');

      table += `
            <tr>
                <td style="border: 1px solid #ccc; padding: 8px; width: 1%;">${id}</td>
                <td style="border: 1px solid #ccc; padding: 8px; width: 1%;">${datos.total}</td>
                <td style="border: 1px solid #ccc; padding: 8px; width: 2%;">${lotes}</td>
            </tr>
        `;
    }

    table += '</tbody></table>';
    output.innerHTML = table;
  }

  function agregarSurtir(idRecib) {
    const input = document.getElementById(`cantidad-surtir-${idRecib}`);
    const valorSurtir = parseInt(input.value) || 0;

    const loteSelect = document.getElementById(`lote-${idRecib}`);
    const loteSeleccionado = loteSelect ? loteSelect.value : null;

    const checkbox = document.querySelector(`.item-checkbox[data-id="${idRecib}"]`);
    const checkboxMarcado = checkbox && checkbox.checked;

    if (checkboxMarcado) {
      if (valorSurtir > 0 && loteSeleccionado) {
        if (!datosSurtir[idRecib]) {
          datosSurtir[idRecib] = {
            total: 0,
            lotes: []
          };
        }

        // Verificar si el lote ya existe en el arreglo de lotes
        const loteExistente = datosSurtir[idRecib].lotes.find(lote => lote.lote === loteSeleccionado);

        if (loteExistente) {
          // Si el lote ya existe, sumar la cantidad
          loteExistente.cantidad += valorSurtir;
        } else {
          // Si no existe, agregar un nuevo lote
          datosSurtir[idRecib].lotes.push({
            lote: loteSeleccionado,
            cantidad: valorSurtir,
            existe_id: loteSelect.options[loteSelect.selectedIndex].getAttribute('data-existe-id')
          });
        }

        // Actualizar el total
        datosSurtir[idRecib].total += valorSurtir;
        document.getElementById(`enviar-${idRecib}`).textContent = datosSurtir[idRecib].total;

        // Limpiar el input y el select
        input.value = '';
        loteSelect.value = '';
      } else {
        alert('Por favor, ingrese una cantidad válida y seleccione un lote.');
      }
    } else {
      if (datosSurtir[idRecib]) {
        delete datosSurtir[idRecib];
        document.getElementById(`enviar-${idRecib}`).textContent = 0;
      }
    }


    // Actualizar vista en pantalla
    actualizarVistaDatosSurtir();
    console.log("Datos en memoria:", datosSurtir);
    guardarEnSesion();
  }

  // Modifica `borrarValorEnviar` para actualizar la vista
  function borrarValorEnviar(id) {
    delete datosSurtir[id];
    document.getElementById(`enviar-${id}`).textContent = '0';
    actualizarVistaDatosSurtir();
    console.log("Datos después de borrar:", datosSurtir);
  }

  function guardarEnSesion() {
    // Verificar si hay datos en datosSurtir
    if (Object.keys(datosSurtir).length === 0) {
      alert('No hay datos para guardar.');
      return; // No hacer nada si no hay datos
    }
    document.getElementById("btnEnviar").addEventListener("click", function() {
      // Verificar si hay datos en datosSurtir
      if (Object.keys(datosSurtir).length === 0) {
        alert("No hay datos para guardar.");
        return; // No hacer nada si no hay datos
      }

      console.log("Datos antes de enviar:", datosSurtir); // Verifica el contenido

      // Crear un formulario oculto dinámico
      const form = document.getElementById("formSurtir"); // Usar el formulario existente

      // Limpia el formulario
      while (form.firstChild) {
        form.removeChild(form.firstChild);
      }

      // Convierte `datosSurtir` en campos ocultos
      for (let idRecib in datosSurtir) {
        const data = datosSurtir[idRecib];

        // Combina todos los datos en un solo campo oculto
        const inputData = document.createElement("input");
        inputData.type = "hidden";
        inputData.name = `lotes[${idRecib}]`;

        // Serializa los datos como JSON
        inputData.value = JSON.stringify({
          lotes: data.lotes.map(lote => ({
            lote: lote.lote,
            cantidad: lote.cantidad,
            existe_id: lote.existe_id // Incluye el existe_id aquí

          })),
          total: data.total,
        });

        form.appendChild(inputData);
      }

      // Envía el formulario
      console.log("enviar:", form); // Depuración
      form.submit();

    });
  }


  if (typeof datosRecibidos !== 'undefined') {
    console.log('Lotes recibidos:', datosRecibidos.lotes);
    console.log('Total:', datosRecibidos.total);
  }

  function marcarTodos(checkboxPrincipal) {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(cb => {
      if (!cb.disabled) {
        cb.checked = checkboxPrincipal.checked;
      }
    });
  }

  function agregarSeleccionados() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkboxes.length === 0) {
      alert("Selecciona al menos un registro para agregar.");
      return;
    }

    checkboxes.forEach(checkbox => {
      const id = checkbox.getAttribute('data-id');
      agregarSurtir(id);
      checkbox.checked = false;
    });

    const checkboxTodos = document.getElementById('check-todos');
    if (checkboxTodos && checkboxTodos.checked) {
      checkboxTodos.checked = false;
    }

    actualizarVistaDatosSurtir();
  }
</script>



<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
        font-family: 'Roboto', sans-serif !important;
        min-height: 100vh;
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

    /* ===== VARIABLES CSS ===== */
    :root {
        --color-primario: #40E0FF;
        --color-secundario: #0f3460;
        --color-fondo: rgba(22, 33, 62, 0.9);
        --color-borde: rgba(64, 224, 255, 0.3);
        --sombra: 0 8px 30px rgba(0, 0, 0, 0.5);
    }

    /* Header personalizado */
    .main-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        box-shadow: 0 4px 20px rgba(64, 224, 255, 0.2);
    }

    .main-header .logo {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-right: 2px solid #40E0FF !important;
        color: #40E0FF !important;
    }

    .main-header .navbar {
        background: transparent !important;
    }

    /* Header table */
    .headt {
        width: 100%;
    }

    /* Sidebar personalizado */
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

    /* Treeview - tamaño de fuente */
    .treeview {
        font-size: 13.3px;
    }

    .treeview-menu > li > a {
        color: rgba(255, 255, 255, 0.9) !important;
        transition: all 0.3s ease;
    }

    .treeview-menu > li > a:hover {
        color: #40E0FF !important;
        background: rgba(64, 224, 255, 0.05) !important;
    }

    /* Separador del menú treeview */
    .treeview-menu-separator {
        padding: 10px 15px;
        font-weight: bold;
        color: #40E0FF !important;
        cursor: default;
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.1) 0%, rgba(64, 224, 255, 0.05) 100%) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.3);
        border-bottom: 1px solid rgba(64, 224, 255, 0.3);
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    .user-panel {
        border-bottom: 1px solid rgba(64, 224, 255, 0.2);
    }

    .user-panel .info {
        color: #ffffff !important;
    }

    /* Content wrapper */
    .content-wrapper {
        background: transparent !important;
        min-height: 100vh;
    }

    /* Dropdown menu */
    .dropdwn {
        float: left;
        overflow: hidden;
    }

    .dropdwn .dropbtn {
        cursor: pointer;
        font-size: 16px;
        border: none;
        outline: none;
        color: white;
        padding: 14px 16px;
        background-color: inherit;
        font-family: inherit;
        margin: 0;
        transition: all 0.3s ease;
    }

    .navbar a:hover,
    .dropdwn:hover .dropbtn,
    .dropbtn:focus {
        background-color: rgba(64, 224, 255, 0.2);
    }

    .dropdwn-content {
        display: none;
        position: absolute;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(64, 224, 255, 0.3);
        z-index: 1;
        border: 1px solid #40E0FF;
        border-radius: 10px;
    }

    .dropdwn-content a {
        float: none;
        color: #ffffff !important;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
        transition: all 0.3s ease;
    }

    .dropdwn-content a:hover {
        background: rgba(64, 224, 255, 0.2) !important;
        color: #40E0FF !important;
    }

    .dropdwn:hover .dropdwn-content {
        display: block;
    }

    .show {
        display: block;
    }

    /* Breadcrumb mejorado */
    .breadcrumb {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 20px !important;
        padding: 25px !important;
        margin-bottom: 40px !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        position: relative;
        overflow: hidden;
    }

    .breadcrumb::before {
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

    .breadcrumb h4 {
        color: #ffffff !important;
        font-weight: 700 !important;
        margin: 0;
        font-size: 28px !important;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
        position: relative;
        z-index: 1;
    }

    /* ===== CONTENEDORES MODERNOS ===== */
    .content {
        padding: 20px;
    }

    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
    }

    .container {
        max-width: 1140px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .container-moderno {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.95) 0%, rgba(15, 52, 96, 0.95) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.4) !important;
        border-radius: 20px;
        padding: 30px;
        margin: 20px auto;
        max-width: 98%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6), 0 0 30px rgba(64, 224, 255, 0.2);
        color: #ffffff !important;
    }

    /* Contenedor de farmacia */
    .farmacia-container {
        padding: 30px;
        background: transparent !important;
        min-height: 100vh;
        margin: 0;
    }

    /* Row y columnas */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col, .col-1, .col-2, .col-3, .col-4, .col-5, .col-6,
    .col-7, .col-8, .col-9, .col-10, .col-11, .col-12,
    .col-sm, .col-md, .col-lg, .col-xl {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    /* ===== HEADER PRINCIPAL ===== */
    .header-principal {
        text-align: center;
        margin-bottom: 40px;
        padding: 30px 0;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        border-radius: 20px;
        color: white;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        position: relative;
        border: 2px solid #40E0FF;
    }

    .header-principal .icono-principal {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
        color: #40E0FF;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
    }

    .header-principal h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
    }

    .btn-ajuste {
        position: absolute;
        top: 50%;
        right: 30px;
        transform: translateY(-50%);
    }

    /* ===== CONTENEDOR DE FILTROS ===== */
    .contenedor-filtros {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px;
        padding: 25px;
        margin: 30px 0;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
    }

    /* ===== TABLAS CYBERPUNK ===== */
    .table-container,
    .tabla-contenedor {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        border: 2px solid rgba(64, 224, 255, 0.3);
        max-height: 80vh;
        overflow-y: auto;
        width: 100%;
    }

    table,
    .table,
    .table-moderna {
        width: 100%;
        margin-bottom: 1rem;
        background: transparent;
        border-collapse: separate;
        border-spacing: 0;
        color: #ffffff !important;
    }

    .table-bordered {
        border: 2px solid rgba(64, 224, 255, 0.4);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background: rgba(64, 224, 255, 0.05);
    }

    .table-hover tbody tr:hover,
    .table-moderna tbody tr:hover {
        background: rgba(64, 224, 255, 0.1);
        transform: scale(1.01);
        transition: all 0.3s ease;
    }

    thead,
    .table-moderna thead {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        border-bottom: 2px solid #40E0FF;
    }

    thead th,
    .table-moderna thead th {
        color: #40E0FF !important;
        font-weight: 700;
        text-transform: uppercase;
        padding: 15px 10px !important;
        border: none !important;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        font-size: 11px;
        letter-spacing: 1px;
        position: sticky;
        top: 0;
        z-index: 10;
        text-align: center;
    }

    thead th i,
    .table-moderna thead th i {
        margin-right: 5px;
    }

    tbody,
    .table-moderna tbody {
        color: #ffffff !important;
    }

    tbody td,
    .table-moderna tbody td {
        padding: 10px 8px !important;
        border: 1px solid rgba(64, 224, 255, 0.2) !important;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
    }

    tbody tr,
    .table-moderna tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(64, 224, 255, 0.1);
    }

    th, td {
        padding: 12px 15px !important;
        text-align: center;
        border: 1px solid rgba(64, 224, 255, 0.2) !important;
    }

    /* Columnas con anchos específicos */
    .col-seleccionar {
        width: 50px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-id {
        width: 60px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-itemid {
        width: 60px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-medicamentos {
        width: 128px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-fecha {
        width: 100px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-almacen {
        width: 110px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-solicitan {
        width: 90px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-lote {
        width: 98px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-caducidad {
        width: 100px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-existencias {
        width: 150px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-surtir {
        width: 100px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .col-parcial {
        width: 83px;
        text-align: center;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    /* Celdas especiales */
    td.fondosan {
        background: linear-gradient(135deg, #5c1a1a 0%, #3a0f0f 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(239, 68, 68, 0.5) !important;
        font-weight: 600;
        text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
    }

    /* ===== INPUTS UNIFORMES ===== */
    .input-uniform {
        width: 100%;
        box-sizing: border-box;
        padding: 5px;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 8px !important;
        color: #ffffff !important;
        transition: all 0.3s ease !important;
    }

    .input-uniform:focus {
        border-color: #40E0FF !important;
        box-shadow: 0 0 15px rgba(64, 224, 255, 0.4) !important;
        outline: none !important;
    }

    /* ===== FORMULARIOS CYBERPUNK ===== */
    .form-control {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px !important;
        color: #ffffff !important;
        padding: 12px 15px !important;
        transition: all 0.3s ease !important;
    }

    .form-control:focus {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.4) !important;
        color: #ffffff !important;
        outline: none !important;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label,
    .form-label {
        color: #40E0FF !important;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"],
    input[type="tel"],
    input[type="date"],
    input[type="time"],
    input[type="datetime-local"],
    textarea,
    select {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px !important;
        color: #ffffff !important;
        padding: 10px 15px !important;
        transition: all 0.3s ease !important;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    input[type="number"]:focus,
    input[type="tel"]:focus,
    input[type="date"]:focus,
    input[type="time"]:focus,
    input[type="datetime-local"]:focus,
    textarea:focus,
    select:focus {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.4) !important;
        outline: none !important;
    }

    select option {
        background: #16213e !important;
        color: #ffffff !important;
    }

    /* Checkbox y Radio */
    input[type="checkbox"],
    input[type="radio"] {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(64, 224, 255, 0.5);
        accent-color: #40E0FF;
    }

    /* ===== BOXES Y PANELS ===== */
    .box {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .box-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        padding: 15px !important;
    }

    .box-header h3,
    .box-header .box-title {
        color: #40E0FF !important;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    .box-body {
        padding: 20px !important;
        color: #ffffff !important;
    }

    .box-footer {
        background: rgba(15, 52, 96, 0.5) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.3) !important;
        padding: 15px !important;
    }

    /* Panel similar a box */
    .panel {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        margin-bottom: 20px;
    }

    .panel-heading {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        padding: 15px !important;
        color: #40E0FF !important;
        font-weight: 700;
    }

    .panel-body {
        padding: 20px !important;
        color: #ffffff !important;
    }

    .panel-footer {
        background: rgba(15, 52, 96, 0.5) !important;
        border-top: 1px solid rgba(64, 224, 255, 0.3) !important;
        padding: 15px !important;
    }

    /* WELL */
    .well {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px !important;
        padding: 20px !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    /* ===== BADGES Y LABELS ===== */
    .badge,
    .label {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        color: #ffffff !important;
        padding: 5px 10px;
        border-radius: 12px;
        font-weight: 600;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        box-shadow: 0 2px 8px rgba(64, 224, 255, 0.3);
    }

    .badge-primary,
    .label-primary {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
    }

    .badge-success,
    .label-success {
        background: linear-gradient(135deg, #4ade80 0%, #1a4a2e 100%) !important;
    }

    .badge-warning,
    .label-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #5c4a1a 100%) !important;
    }

    .badge-danger,
    .label-danger {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
    }

    .badge-info,
    .label-info {
        background: linear-gradient(135deg, #818cf8 0%, #2e2e5c 100%) !important;
    }

    /* ===== CUADROS DE ESTADO ===== */
    .cuadro {
        width: 15px;
        height: 15px;
        display: inline-block;
        margin-right: 10px;
        border-radius: 5px;
        border: 1px solid rgba(64, 224, 255, 0.3);
    }

    .en-espera {
        background: linear-gradient(135deg, #8eb5f0ff 0%, #6a9dd8 100%);
        box-shadow: 0 0 10px rgba(142, 181, 240, 0.5);
    }

    .entrega-parcial {
        background: linear-gradient(135deg, #b3cef7ff 0%, #91b8f0 100%);
        box-shadow: 0 0 10px rgba(179, 206, 247, 0.5);
    }

    .nuevo-surtimiento {
        background: linear-gradient(135deg, #e6f0ff 0%, #c4dcf7 100%);
        box-shadow: 0 0 10px rgba(230, 240, 255, 0.5);
    }

    .texto {
        display: inline-block;
        font-size: 12px;
        font-weight: bold;
        color: #ffffff;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    /* ===== PROGRESS BARS ===== */
    .progress {
        background: rgba(22, 33, 62, 0.8) !important;
        border: 1px solid rgba(64, 224, 255, 0.3);
        border-radius: 10px;
        height: 25px;
        overflow: hidden;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.5);
    }

    .progress-bar {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        box-shadow: 0 0 15px rgba(64, 224, 255, 0.6);
        transition: width 0.6s ease;
        line-height: 25px;
        color: #ffffff;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .progress-bar-success {
        background: linear-gradient(135deg, #4ade80 0%, #1a4a2e 100%) !important;
        box-shadow: 0 0 15px rgba(74, 222, 128, 0.6);
    }

    .progress-bar-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #5c4a1a 100%) !important;
        box-shadow: 0 0 15px rgba(251, 191, 36, 0.6);
    }

    .progress-bar-danger {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.6);
    }

    /* ===== PAGINACIÓN MODERNA ===== */
    .pagination,
    .contenedor-paginacion {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }

    .paginacion-moderna {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .pagination li {
        margin: 0 3px;
    }

    .pagination li a,
    .pagination li span,
    .btn-paginacion {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        color: #ffffff !important;
        padding: 8px 15px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        height: 45px;
        font-weight: 600;
    }

    .pagination li a:hover,
    .btn-paginacion:hover {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .pagination li.active a,
    .pagination li.active span,
    .btn-paginacion.active {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.6);
    }

    /* ===== TABS ===== */
    .nav-tabs {
        border-bottom: 2px solid rgba(64, 224, 255, 0.3);
    }

    .nav-tabs > li > a {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-bottom: none !important;
        color: #ffffff !important;
        border-radius: 10px 10px 0 0 !important;
        margin-right: 5px;
        transition: all 0.3s ease;
    }

    .nav-tabs > li > a:hover {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
    }

    .nav-tabs > li.active > a {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
        color: #40E0FF !important;
        box-shadow: 0 -3px 15px rgba(64, 224, 255, 0.3);
    }

    .tab-content {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-top: none !important;
        padding: 20px;
        border-radius: 0 0 10px 10px;
        color: #ffffff !important;
    }

    /* ===== TOOLTIPS ===== */
    .tooltip-inner {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 1px solid #40E0FF;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.5);
        padding: 8px 12px;
        border-radius: 8px;
    }

    /* ===== POPOVERS ===== */
    .popover {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.4);
        border-radius: 10px;
    }

    .popover-title {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #40E0FF !important;
        border-bottom: 1px solid #40E0FF !important;
    }

    .popover-content {
        color: #ffffff !important;
    }

    /* ===== CARDS PEQUEÑAS INFO ===== */
    .info-box {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        min-height: 90px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .info-box:hover {
        border-color: #40E0FF !important;
        box-shadow: 0 10px 40px rgba(64, 224, 255, 0.4);
        transform: translateY(-5px);
    }

    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 70px;
        border-radius: 10px;
        background: rgba(64, 224, 255, 0.2);
        border: 2px solid #40E0FF;
        margin-right: 15px;
    }

    .info-box-icon i {
        font-size: 35px;
        color: #40E0FF;
    }

    .info-box-content {
        flex: 1;
        color: #ffffff;
    }

    .info-box-text {
        text-transform: uppercase;
        font-weight: 600;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
    }

    .info-box-number {
        font-size: 24px;
        font-weight: 700;
        color: #40E0FF;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* ===== SMALL BOX ===== */
    .small-box {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        position: relative;
        margin-bottom: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .small-box:hover {
        border-color: #40E0FF !important;
        box-shadow: 0 10px 40px rgba(64, 224, 255, 0.4);
        transform: translateY(-5px);
    }

    .small-box h3 {
        color: #40E0FF !important;
        font-size: 38px;
        font-weight: 700;
        margin: 0 0 10px 0;
        text-shadow: 0 0 15px rgba(64, 224, 255, 0.6);
    }

    .small-box p {
        color: #ffffff;
        font-size: 14px;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .small-box .icon {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 70px;
        color: rgba(64, 224, 255, 0.3);
    }

    .small-box .small-box-footer {
        display: block;
        padding: 10px 0;
        margin-top: 10px;
        text-align: center;
        border-top: 1px solid rgba(64, 224, 255, 0.3);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .small-box .small-box-footer:hover {
        color: #40E0FF;
        background: rgba(64, 224, 255, 0.1);
    }

    /* ===== LISTA DE GRUPOS ===== */
    .list-group {
        border-radius: 10px;
        overflow: hidden;
    }

    .list-group-item {
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%) !important;
        border: 1px solid rgba(64, 224, 255, 0.3) !important;
        color: #ffffff !important;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-color: #40E0FF !important;
        transform: translateX(5px);
    }

    .list-group-item.active {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        border-color: #40E0FF !important;
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
    }

    /* ===== MENSAJE SIN RESULTADOS ===== */
    .mensaje-sin-resultados {
        text-align: center;
        padding: 50px 20px;
        color: #40E0FF;
        font-size: 18px;
        font-weight: 600;
    }

    .mensaje-sin-resultados i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
        color: #40E0FF;
    }

    /* Todo Container - Estilo Kanban cyberpunk */
    .todo-container {
        max-width: 15000px;
        height: auto;
        display: flex;
        overflow-y: scroll;
        overflow-x: auto;
        column-gap: 0.5em;
        column-rule: 2px solid rgba(64, 224, 255, 0.3);
        column-width: 140px;
        column-count: 7;
        padding: 10px;
    }

    /* Scrollbar para todo-container */
    .todo-container::-webkit-scrollbar {
        height: 12px;
        width: 12px;
    }

    .todo-container::-webkit-scrollbar-track {
        background: rgba(10, 10, 10, 0.5);
        border-radius: 10px;
    }

    .todo-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%);
        border-radius: 10px;
    }

    .todo-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #00D9FF 0%, #40E0FF 100%);
    }

    .status {
        width: 25%;
        min-width: 250px;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px;
        position: relative;
        padding: 60px 1rem 0.5rem;
        height: 100%;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.2);
        margin-right: 10px;
    }

    .status h4 {
        position: absolute;
        top: 0;
        left: 0;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #ffffff !important;
        margin: 0;
        width: 100%;
        padding: 0.5rem 1rem;
        border-radius: 13px 13px 0 0;
        border-bottom: 2px solid #40E0FF;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        font-weight: 600;
        font-size: 16px;
        text-align: center;
    }

    /* Estilos para alertas/tarjetas de pacientes */
    .alert {
        padding: 15px 40px 15px 15px;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
        border: 1px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px;
        margin-bottom: 10px;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        position: relative;
    }

    .alert:hover {
        border-color: #40E0FF !important;
        box-shadow: 0 6px 20px rgba(64, 224, 255, 0.4);
        transform: translateX(5px);
    }

    .alert-success {
        border-color: rgba(74, 222, 128, 0.5) !important;
        background: linear-gradient(135deg, rgba(26, 74, 46, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .alert-warning {
        border-color: rgba(251, 191, 36, 0.5) !important;
        background: linear-gradient(135deg, rgba(92, 74, 26, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .alert-danger {
        border-color: rgba(239, 68, 68, 0.5) !important;
        background: linear-gradient(135deg, rgba(92, 26, 26, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .alert-info {
        border-color: rgba(129, 140, 248, 0.5) !important;
        background: linear-gradient(135deg, rgba(46, 46, 92, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    /* Botón de cerrar alert */
    .alert .close {
        color: #40E0FF !important;
        opacity: 1;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.8);
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
    }

    /* Nombre del paciente */
    .nompac {
        font-size: 11.5px;
        position: absolute;
        color: #ffffff !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .nod {
        font-size: 10.3px;
        color: rgba(255, 255, 255, 0.9) !important;
    }

    /* Tarjetas de contenido */
    .ancholi {
        margin-top: 1px;
        margin-bottom: 10px;
        width: 175px;
        height: 100px;
        display: inline-block;
    }

    .ancholi2 {
        width: 170px;
        height: 97px;
        display: inline-block;
        box-shadow: 0 5px 15px rgba(64, 224, 255, 0.3);
        border: 1px solid rgba(64, 224, 255, 0.2);
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.9) 0%, rgba(15, 52, 96, 0.9) 100%);
        transition: all 0.3s ease;
    }

    .ancholi2:hover {
        box-shadow: 0 8px 25px rgba(64, 224, 255, 0.5);
        border-color: #40E0FF;
        transform: scale(1.05);
    }

    /* Tarjetas modernas cyberpunk - Estilo base */
    .modern-card,
    .farmacia-card {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        padding: 40px 20px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                    0 0 30px rgba(64, 224, 255, 0.2) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative;
        overflow: hidden;
        min-height: 280px;
        margin: 20px 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-decoration: none;
    }

    .modern-card::before,
    .farmacia-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent,
            rgba(64, 224, 255, 0.1),
            transparent
        );
        transform: rotate(45deg);
        transition: all 0.6s ease;
    }

    .modern-card:hover::before,
    .farmacia-card:hover::before {
        left: 100%;
    }

    .modern-card:hover,
    .farmacia-card:hover {
        transform: translateY(-15px) scale(1.05) !important;
        border-color: #00D9FF !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7),
                    0 0 50px rgba(64, 224, 255, 0.5),
                    inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
        text-decoration: none;
    }

    .modern-card a,
    .farmacia-card a {
        text-decoration: none !important;
        color: inherit;
        display: block;
    }

    /* Variaciones de color para tarjetas de farmacia */
    .farmacia-card.surtir {
        background: linear-gradient(135deg, #16213e 0%, #1a3a5c 100%) !important;
        border-color: #40E0FF !important;
    }

    .farmacia-card.existencias {
        background: linear-gradient(135deg, #16213e 0%, #2e1a4a 100%) !important;
        border-color: #c084fc !important;
    }

    .farmacia-card.kardex {
        background: linear-gradient(135deg, #16213e 0%, #1a4a2e 100%) !important;
        border-color: #4ade80 !important;
    }

    .farmacia-card.caducidades {
        background: linear-gradient(135deg, #16213e 0%, #5c3a1a 100%) !important;
        border-color: #fb923c !important;
    }

    .farmacia-card.devoluciones {
        background: linear-gradient(135deg, #16213e 0%, #4a1a2e 100%) !important;
        border-color: #f472b6 !important;
    }

    .farmacia-card.confirmar {
        background: linear-gradient(135deg, #16213e 0%, #5c1a1a 100%) !important;
        border-color: #ef4444 !important;
    }

    .farmacia-card.pedir {
        background: linear-gradient(135deg, #16213e 0%, #1a5c5c 100%) !important;
        border-color: #2dd4bf !important;
    }

    .farmacia-card.salidas {
        background: linear-gradient(135deg, #16213e 0%, #2e2e5c 100%) !important;
        border-color: #818cf8 !important;
    }

    .farmacia-card.inventario {
        background: linear-gradient(135deg, #16213e 0%, #5c4a1a 100%) !important;
        border-color: #fbbf24 !important;
    }

    /* Hover para variaciones de color */
    .farmacia-card:hover.surtir {
        border-color: #00D9FF !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(64, 224, 255, 0.6) !important;
    }

    .farmacia-card:hover.existencias {
        border-color: #a855f7 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(192, 132, 252, 0.6) !important;
    }

    .farmacia-card:hover.kardex {
        border-color: #22c55e !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(74, 222, 128, 0.6) !important;
    }

    .farmacia-card:hover.caducidades {
        border-color: #f97316 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(251, 146, 60, 0.6) !important;
    }

    .farmacia-card:hover.devoluciones {
        border-color: #ec4899 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(244, 114, 182, 0.6) !important;
    }

    .farmacia-card:hover.confirmar {
        border-color: #dc2626 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(239, 68, 68, 0.6) !important;
    }

    .farmacia-card:hover.pedir {
        border-color: #14b8a6 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(45, 212, 191, 0.6) !important;
    }

    .farmacia-card:hover.salidas {
        border-color: #6366f1 !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(129, 140, 248, 0.6) !important;
    }

    .farmacia-card:hover.inventario {
        border-color: #f59e0b !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 50px rgba(251, 191, 36, 0.6) !important;
    }

    /* Círculo de icono */
    .icon-circle,
    .farmacia-icon-circle {
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.2) 0%, rgba(0, 217, 255, 0.3) 100%) !important;
        width: 140px !important;
        height: 140px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 auto 20px !important;
        border: 3px solid #40E0FF !important;
        box-shadow: 0 10px 30px rgba(64, 224, 255, 0.3),
                    inset 0 0 20px rgba(64, 224, 255, 0.1) !important;
        transition: all 0.4s ease !important;
        position: relative;
    }

    .icon-circle::after,
    .farmacia-icon-circle::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid #40E0FF;
        opacity: 0;
        animation: ripple 2s ease-out infinite;
    }

    @keyframes ripple {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }
        100% {
            transform: scale(1.3);
            opacity: 0;
        }
    }

    .modern-card:hover .icon-circle,
    .farmacia-card:hover .farmacia-icon-circle,
    .modern-card:hover .farmacia-icon-circle,
    .farmacia-card:hover .icon-circle {
        transform: scale(1.15) rotate(360deg) !important;
        box-shadow: 0 15px 40px rgba(64, 224, 255, 0.5),
                    inset 0 0 30px rgba(64, 224, 255, 0.2) !important;
        background: linear-gradient(135deg, rgba(64, 224, 255, 0.3) 0%, rgba(0, 217, 255, 0.4) 100%) !important;
    }

    .modern-card .fa,
    .farmacia-card i,
    .modern-card i,
    .farmacia-card .fa {
        font-size: 60px !important;
        color: #40E0FF !important;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.8);
        transition: all 0.4s ease !important;
    }

    .modern-card:hover .fa,
    .farmacia-card:hover i,
    .modern-card:hover i,
    .farmacia-card:hover .fa {
        transform: scale(1.2) !important;
        text-shadow: 0 0 30px rgba(64, 224, 255, 1),
                     0 0 40px rgba(64, 224, 255, 0.8);
        animation: pulse-icon 1.5s infinite;
    }

    @keyframes pulse-icon {
        0% { transform: scale(1.2); }
        50% { transform: scale(1.25); }
        100% { transform: scale(1.2); }
    }

    /* Títulos */
    .card-title,
    .farmacia-card h4,
    .modern-card h4 {
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 1.4rem !important;
        margin: 0 !important;
        text-align: center;
        padding: 20px;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5),
                     0 0 20px rgba(64, 224, 255, 0.3);
        transition: all 0.3s ease;
        line-height: 1.3;
    }

    .modern-card:hover .card-title,
    .farmacia-card:hover h4,
    .modern-card:hover h4 {
        color: #40E0FF !important;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.8),
                     0 0 30px rgba(64, 224, 255, 0.5);
    }

    /* Animaciones de entrada */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-card,
    .farmacia-card,
    .container-moderno {
        animation: fadeInUp 0.6s ease-out backwards;
    }

    .contenedor-filtros,
    .tabla-contenedor {
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }

    .modern-card:nth-child(1),
    .farmacia-card:nth-child(1) { animation-delay: 0.1s; }
    .modern-card:nth-child(2),
    .farmacia-card:nth-child(2) { animation-delay: 0.2s; }
    .modern-card:nth-child(3),
    .farmacia-card:nth-child(3) { animation-delay: 0.3s; }
    .modern-card:nth-child(4),
    .farmacia-card:nth-child(4) { animation-delay: 0.4s; }
    .modern-card:nth-child(5),
    .farmacia-card:nth-child(5) { animation-delay: 0.5s; }
    .modern-card:nth-child(6),
    .farmacia-card:nth-child(6) { animation-delay: 0.6s; }
    .modern-card:nth-child(7),
    .farmacia-card:nth-child(7) { animation-delay: 0.7s; }
    .modern-card:nth-child(8),
    .farmacia-card:nth-child(8) { animation-delay: 0.8s; }
    .modern-card:nth-child(9),
    .farmacia-card:nth-child(9) { animation-delay: 0.9s; }

    /* Efecto de brillo en hover */
    @keyframes glow {
        0%, 100% {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                        0 0 30px rgba(64, 224, 255, 0.2);
        }
        50% {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7),
                        0 0 50px rgba(64, 224, 255, 0.6);
        }
    }

    .modern-card:hover,
    .farmacia-card:hover {
        animation: glow 2s ease-in-out infinite;
    }

    /* Modal */
    .modal-content {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 20px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9),
                    0 0 40px rgba(64, 224, 255, 0.4);
    }

    .modal-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-bottom: 2px solid #40E0FF !important;
        border-radius: 20px 20px 0 0 !important;
    }

    .modal-header .close {
        color: #40E0FF !important;
        opacity: 1;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.8);
    }

    .modal-body {
        color: #ffffff !important;
    }

    .modal-footer {
        border-top: 2px solid #40E0FF !important;
        background: rgba(15, 52, 96, 0.5) !important;
    }

    /* ===== BOTONES MODERNOS CYBERPUNK ===== */
    .btn,
    .btn-moderno,
    button.enviar {
        border-radius: 25px !important;
        padding: 12px 30px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease !important;
        border: 2px solid #40E0FF !important;
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #ffffff !important;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .btn:hover,
    .btn-moderno:hover,
    button.enviar:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border-color: #00D9FF !important;
        color: #ffffff !important;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%) !important;
        border-color: #40E0FF !important;
    }

    .btn-success,
    .btn-filtrar {
        background: linear-gradient(135deg, #4ade80 0%, #1a4a2e 100%) !important;
        border-color: #4ade80 !important;
    }

    .btn-warning {
        background: linear-gradient(135deg, #fbbf24 0%, #5c4a1a 100%) !important;
        border-color: #fbbf24 !important;
    }

    .btn-danger,
    .btn-borrar,
    .btn-regresar {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
        border-color: #ef4444 !important;
    }

    .btn-info,
    .btn-especial {
        background: linear-gradient(135deg, #818cf8 0%, #2e2e5c 100%) !important;
        border-color: #818cf8 !important;
    }

    .borrar-btn {
        background: linear-gradient(135deg, #ef4444 0%, #5c1a1a 100%) !important;
        color: white;
        border: 2px solid #ef4444 !important;
        padding: 5px 10px;
        font-size: 12px;
        cursor: pointer;
        margin-left: 6px;
        text-align: center;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .borrar-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.5);
        border-color: #dc2626 !important;
    }

    /* ===== SELECT2 CUSTOM ===== */
    .select2-container--default .select2-selection--single {
        border: 2px solid rgba(64, 224, 255, 0.3) !important;
        border-radius: 10px !important;
        height: 48px !important;
        line-height: 48px !important;
        background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%) !important;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #40E0FF !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding-left: 15px !important;
        padding-top: 8px !important;
        color: #ffffff !important;
    }

    /* Dropdown menu del usuario */
    .dropdown-menu {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 10px;
    }

    .dropdown-menu > li > a {
        color: #ffffff !important;
    }

    .dropdown-menu > li > a:hover {
        background: rgba(64, 224, 255, 0.1) !important;
        color: #40E0FF !important;
    }

    .user-header {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
    }

    /* Footer */
    .main-footer {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-top: 2px solid #40E0FF !important;
        color: #ffffff !important;
        box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
    }

    /* Links globales */
    a {
        color: #40E0FF;
        transition: all 0.3s ease;
    }

    a:hover {
        color: #00D9FF;
        text-decoration: none;
    }

    /* Headings globales */
    h1, h2, h3, h4, h5, h6 {
        color: #ffffff;
    }

    /* Párrafos */
    p {
        color: rgba(255, 255, 255, 0.9);
    }

    /* HR */
    hr {
        border-top: 1px solid rgba(64, 224, 255, 0.3);
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 12px;
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

    /* Scrollbar para contenedores específicos */
    .tabla-contenedor::-webkit-scrollbar,
    .table-container::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .tabla-contenedor::-webkit-scrollbar-track,
    .table-container::-webkit-scrollbar-track {
        background: rgba(10, 10, 10, 0.5);
        border-radius: 10px;
    }

    .tabla-contenedor::-webkit-scrollbar-thumb,
    .table-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #40E0FF 0%, #0f3460 100%);
        border-radius: 10px;
    }

    /* Responsividad mejorada */
    @media (max-width: 992px) {
        .icon-circle,
        .farmacia-icon-circle {
            width: 120px !important;
            height: 120px !important;
        }

        .modern-card .fa,
        .farmacia-card i {
            font-size: 50px !important;
        }

        .card-title,
        .farmacia-card h4 {
            font-size: 1.2rem !important;
        }

        .breadcrumb h4 {
            font-size: 24px !important;
        }

        table, .table, .table-moderna {
            font-size: 13px;
        }

        thead th, .table-moderna thead th {
            padding: 10px !important;
        }

        tbody td, .table-moderna tbody td {
            padding: 8px 10px !important;
        }

        .container-moderno {
            margin: 10px;
            padding: 20px;
            border-radius: 15px;
        }

        .header-principal h1 {
            font-size: 24px;
        }

        .btn-moderno, .btn {
            padding: 10px 16px !important;
            font-size: 14px;
        }

        .btn-ajuste {
            position: relative;
            top: auto;
            right: auto;
            transform: none;
            margin-top: 15px;
        }
    }

    @media screen and (max-width: 980px) {
        .alert {
            padding-right: 38px;
            padding-left: 10px;
        }

        .nompac {
            margin-left: -3px;
            font-size: 10px;
        }

        .nod {
            font-size: 7px;
        }
    }

    @media (max-width: 768px) {
        .farmacia-container {
            padding: 15px;
        }

        .modern-card,
        .farmacia-card {
            margin: 15px 0;
            padding: 30px 15px;
            min-height: 220px;
        }

        .icon-circle,
        .farmacia-icon-circle {
            width: 100px !important;
            height: 100px !important;
            margin-bottom: 15px !important;
        }

        .modern-card .fa,
        .farmacia-card i {
            font-size: 40px !important;
        }

        .card-title,
        .farmacia-card h4 {
            font-size: 1.1rem !important;
            padding: 15px;
        }

        .breadcrumb {
            padding: 20px !important;
            margin-bottom: 30px !important;
        }

        .breadcrumb h4 {
            font-size: 20px !important;
        }

        .status {
            min-width: 200px;
        }

        table, .table, .table-moderna {
            font-size: 12px;
        }

        .box, .panel, .well {
            margin-bottom: 15px;
        }

        .info-box {
            flex-direction: column;
            text-align: center;
        }

        .info-box-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }

        .table-moderna thead th,
        .table-moderna tbody td {
            padding: 8px 6px !important;
        }
    }

    @media (max-width: 576px) {
        .modern-card,
        .farmacia-card {
            min-height: 200px;
            padding: 25px 15px;
            margin: 10px 0;
        }

        .icon-circle,
        .farmacia-icon-circle {
            width: 80px !important;
            height: 80px !important;
            margin-bottom: 12px !important;
        }

        .modern-card .fa,
        .farmacia-card i {
            font-size: 32px !important;
        }

        .card-title,
        .farmacia-card h4 {
            font-size: 13px !important;
            padding: 10px;
        }

        .breadcrumb h4 {
            font-size: 18px !important;
            letter-spacing: 1px;
        }

        .status {
            min-width: 180px;
        }

        table, .table, .table-moderna {
            font-size: 10px;
        }

        thead th, .table-moderna thead th {
            padding: 8px 5px !important;
            font-size: 10px;
        }

        tbody td, .table-moderna tbody td {
            padding: 6px 5px !important;
        }

        .btn, .btn-moderno {
            padding: 10px 20px !important;
            font-size: 12px;
        }

        .small-box h3 {
            font-size: 28px;
        }

        .info-box-number {
            font-size: 20px;
        }
    }
</style>
