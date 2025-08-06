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
    cr.id_recib, ia.item_name, cr.fecha, cr.almacen, cr.solicita
ORDER BY 
    ia.item_name, cr.id_recib, cr.fecha ASC;

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



<body>
  <a href="../../template/menu_farmaciacentral.php"

    style='color: white; margin-left: 20px; margin-bottom: 1px; background-color: #d9534f; 
          border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer; display: inline-block;'>
    Regresar
  </a>

  <section class="content container-fluid">

    <hr>
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
      <strong>
        <center>SURTIR A LOS SUBALMACENES</center>
      </strong>

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
    <br>
    <div class="table-container">



      <form method="post" id="formSurtir" action="">
        <table class="table table-bordered table-striped" id="mytable">
          <thead class="thead" style="background-color: #2b2d7f">
            <tr>
              <th class="col-itemid">
                <font color="white">IDMedicamento</font>
              </th>
              <th class="col-medicamentos">
                <font color="white">Medicamento</font>
              </th>
              <th class="col-fecha">
                <font color="white">Fecha de solicitud</font>
              </th>
              <th class="col-almacen">
                <font color="white">Sub almacen</font>
              </th>
              <th class="col-solicitan">
                <font color="white">Solicitan</font>
              </th>
              <th class="col-parcial">
                <font color="white">Entrega Parcial</font>
              </th>
              <th class="col-parcial">
                <font color="white">Enviar</font>
              </th>
              <th class="col-lote">
                <font color="white">Lote</font>
              </th>
              <th class="col-caducidad">
                <font color="white">Caducidad</font>
              </th>
              <th class="col-existencias">
                <font color="white">Existencias.Lote/Total</font>
              </th>
              <th class="col-surtir">
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

          <button type="button" class="enviar" onclick="agregarSeleccionados()" style="margin-right: 10px;">
            Agregar seleccionados
          </button>

          <button type="submit" id="btnEnviar" class="enviar" style="margin-right: 10px;">
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
  .borrar-btn {
    background-color: #2b2d7f;
    color: white;
    border: none;
    padding: 5px;
    font-size: 12px;
    cursor: pointer;
    margin-left: 6px;
    text-align: center;

  }


  table {
    table-layout: fixed;
    width: 120%;
    border-collapse: collapse;
  }

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

  .input-uniform {
    width: 100%;
    box-sizing: border-box;
    padding: 5px;
  }

  th,
  td {
    padding: 8px;
    text-align: center;
  }

  .table-container {
    width: 100%;
    overflow-x: auto;
  }

  table {
    border: 1px solid #ddd;
  }

  th,
  td {
    border: 1px solid #ddd;
    /* Borde en las celdas */
  }

  button.enviar {
    background-color: #2b2d7f;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
  }

  button.enviar:hover {
    background-color: #2b2d7f;
    /* Un color ligeramente más oscuro para el hover */
  }


  .cuadro {
    width: 15px;
    /* Tamaño del cuadro */
    height: 15px;
    /* Tamaño del cuadro */
    display: inline-block;
    margin-right: 10px;
    /* Separación entre los cuadros */
    border-radius: 5px;

  }

  .en-espera {
    background-color: #8eb5f0ff;
    color: black;

  }

  .entrega-parcial {
    background-color: #b3cef7ff;
  }

  .nuevo-surtimiento {
    background-color: #e6f0ff;
  }

  .texto {
    display: inline-block;
    font-size: 12px;
    /* Ajuste del tamaño de la fuente */
    font-weight: bold;

  }
</style>