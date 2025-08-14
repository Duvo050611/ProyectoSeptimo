<?php
session_start();
ob_start(); // Iniciar el buffering de salida
include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;

if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
  include "../header_farmaciah.php";
} else {
  echo "<script>window.Location='../../index.php';</script>";
}

// Manejar peticiones AJAX de búsqueda
if (isset($_GET['ajax']) && isset($_GET['search'])) {
  $searchTerm = mysqli_real_escape_string($conexion, $_GET['search']);
  $tab = isset($_GET['tab']) ? $_GET['tab'] : 'normales';

  // Limpiar cualquier salida previa
  ob_clean();

  if ($tab === 'cero') {
    $query_search = "
      SELECT 
          ia.item_id,
          ia.item_code,
          ia.item_name,
          ia.item_grams,
          ia.item_max,
          ia.item_min,
          ia.reorden,
          it.item_type_desc,
          ea.existe_id,
          ea.existe_lote,
          ea.existe_caducidad,
          ea.existe_qty,
          ea.existe_entradas,
          ea.existe_salidas,
          ea.ubicacion_id
      FROM item_almacen ia
      INNER JOIN existencias_almacenh ea ON ia.item_id = ea.item_id
      INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
      WHERE ea.existe_qty = 0
      AND (ia.item_code LIKE '%$searchTerm%' 
           OR ea.existe_lote LIKE '%$searchTerm%')
      ORDER BY ia.item_code ASC
    ";
  } else {
    $query_search = "
      SELECT 
          ia.item_id,
          ia.item_code,
          ia.item_name,
          ia.item_grams,
          ia.item_max,
          ia.item_min,
          ia.reorden,
          it.item_type_desc,
          ea.existe_id,
          ea.existe_lote,
          ea.existe_caducidad,
          ea.existe_qty,
          ea.existe_entradas,
          ea.existe_salidas,
          ea.ubicacion_id
      FROM item_almacen ia
      INNER JOIN existencias_almacenh ea ON ia.item_id = ea.item_id
      INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
      WHERE ea.existe_qty > 0
      AND (ia.item_code LIKE '%$searchTerm%' 
           OR ea.existe_lote LIKE '%$searchTerm%')
      ORDER BY ia.item_code ASC, ea.existe_caducidad DESC
    ";
  }

  $resultado_search = $conexion->query($query_search) or die($conexion->error);

  // Solo generar las filas de la tabla
  while ($row_search = $resultado_search->fetch_assoc()) {
    $caduca = date_create($row_search['existe_caducidad']);
    $existencias = $row_search['existe_qty'];
    $maximo = $row_search['item_max'];
    $minimo = $row_search['item_min'];
    $reordena = $row_search['reorden'];
    $id_ubica = $row_search['ubicacion_id'];

    // Calcular los meses hasta la caducidad
    $fecha_actual = new DateTime();
    $meses_hasta_caducidad = $fecha_actual->diff($caduca)->m + ($fecha_actual->diff($caduca)->y * 12);
    if ($caduca < $fecha_actual) {
      $meses_hasta_caducidad = 0; // Si ya venció
    }

    // Determinar color de fondo según caducidad
    $color_caducidad = '';
    if ($meses_hasta_caducidad <= 3) {
      $color_caducidad = 'style="background-color: #dc3545; color: white;"'; // Rojo
    } elseif ($meses_hasta_caducidad > 3 && $meses_hasta_caducidad <= 6) {
      $color_caducidad = 'style="background-color: #ffc107; color: black;"'; // Amarillo
    } else {
      $color_caducidad = 'style="background-color: #28a745; color: white;"'; // Verde
    }

    $result_ubica = $conexion->query("SELECT * FROM ubicaciones_almacen WHERE ubicacion_id = $id_ubica") or die($conexion->error);
    $ubicacion = 'Sin ubicación';
    while ($row_ubica = $result_ubica->fetch_assoc()) {
      $ubicacion = $row_ubica['nombre_ubicacion'];
    }

    echo '<tr>'
      . '<td>' . $row_search['item_code'] . '</td>'
      . '<td>' . $row_search['item_name'] . ', ' . $row_search['item_grams'] . ', ' . $row_search['item_type_desc'] . '</td>'
      . '<td>' . $row_search['existe_lote'] . '</td>'
      . '<td ' . $color_caducidad . '>' . date_format($caduca, "d/m/Y") . '</td>';

    if ($tab === 'cero') {
      // Para la pestaña de existencias en 0, mostrar en el orden correcto
      $entradas = $row_search['existe_entradas'];
      $salidas = $row_search['existe_salidas'];
      echo '<td>' . $minimo . '</td>'
        . '<td>' . $maximo . '</td>'
        . '<td>' . $reordena . '</td>'
        . '<td>' . $entradas . '</td>'
        . '<td>' . $salidas . '</td>'
        . '<td>' . $existencias . '</td>';
    } else {
      // Para la pestaña de existencias normales
      $entradas = $row_search['existe_entradas'];
      $salidas = $row_search['existe_salidas'];
      echo '<td>' . $maximo . '</td>'
        . '<td>' . $reordena . '</td>'
        . '<td>' . $minimo . '</td>'
        . '<td>' . $entradas . '</td>'
        . '<td>' . $salidas . '</td>'
        . '<td>' . $existencias . '</td>';
    }

    echo '<td>' . $ubicacion . '</td></tr>';
  }

  exit; // Importante: terminar la ejecución aquí para no enviar HTML adicional
}

// Manejar peticiones AJAX para filtro por medicamento
if (isset($_GET['ajax']) && isset($_GET['item_id'])) {
  $item_id = intval($_GET['item_id']);
  $tab = isset($_GET['tab']) ? $_GET['tab'] : 'normales';

  // Limpiar cualquier salida previa
  ob_clean();

  if ($tab === 'cero') {
    $query_item = "
      SELECT 
          ia.item_id,
          ia.item_code,
          ia.item_name,
          ia.item_grams,
          ia.item_max,
          ia.item_min,
          ia.reorden,
          it.item_type_desc,
          ea.existe_id,
          ea.existe_lote,
          ea.existe_caducidad,
          ea.existe_qty,
          ea.existe_entradas,
          ea.existe_salidas,
          ea.ubicacion_id
      FROM item_almacen ia
      INNER JOIN existencias_almacenh ea ON ia.item_id = ea.item_id
      INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
      WHERE ea.existe_qty = 0 AND ia.item_id = $item_id
      ORDER BY ia.item_code ASC
    ";
  } else {
    $query_item = "
      SELECT 
          ia.item_id,
          ia.item_code,
          ia.item_name,
          ia.item_grams,
          ia.item_max,
          ia.item_min,
          ia.reorden,
          it.item_type_desc,
          ea.existe_id,
          ea.existe_lote,
          ea.existe_caducidad,
          ea.existe_qty,
          ea.existe_entradas,
          ea.existe_salidas,
          ea.ubicacion_id
      FROM item_almacen ia
      INNER JOIN existencias_almacenh ea ON ia.item_id = ea.item_id
      INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
      WHERE ea.existe_qty > 0 AND ia.item_id = $item_id
      ORDER BY ia.item_code ASC, ea.existe_caducidad DESC
    ";
  }

  $resultado_item = $conexion->query($query_item) or die($conexion->error);

  // Solo generar las filas de la tabla
  while ($row_item = $resultado_item->fetch_assoc()) {
    $caduca = date_create($row_item['existe_caducidad']);
    $existencias = $row_item['existe_qty'];
    $maximo = $row_item['item_max'];
    $minimo = $row_item['item_min'];
    $reordena = $row_item['reorden'];
    $id_ubica = $row_item['ubicacion_id'];

    // Calcular los meses hasta la caducidad
    $fecha_actual = new DateTime();
    $meses_hasta_caducidad = $fecha_actual->diff($caduca)->m + ($fecha_actual->diff($caduca)->y * 12);
    if ($caduca < $fecha_actual) {
      $meses_hasta_caducidad = 0; // Si ya venció
    }

    // Determinar color de fondo según caducidad
    $color_caducidad = '';
    if ($meses_hasta_caducidad <= 3) {
      $color_caducidad = 'style="background-color: #dc3545; color: white;"'; // Rojo
    } elseif ($meses_hasta_caducidad > 3 && $meses_hasta_caducidad <= 6) {
      $color_caducidad = 'style="background-color: #ffc107; color: black;"'; // Amarillo
    } else {
      $color_caducidad = 'style="background-color: #28a745; color: white;"'; // Verde
    }

    $result_ubica = $conexion->query("SELECT * FROM ubicaciones_almacen WHERE ubicacion_id = $id_ubica") or die($conexion->error);
    $ubicacion = 'Sin ubicación';
    while ($row_ubica = $result_ubica->fetch_assoc()) {
      $ubicacion = $row_ubica['nombre_ubicacion'];
    }

    echo '<tr>'
      . '<td>' . $row_item['item_code'] . '</td>'
      . '<td>' . $row_item['item_name'] . ', ' . $row_item['item_grams'] . ', ' . $row_item['item_type_desc'] . '</td>'
      . '<td>' . $row_item['existe_lote'] . '</td>'
      . '<td ' . $color_caducidad . '>' . date_format($caduca, "d/m/Y") . '</td>';

    if ($tab === 'cero') {
      $entradas = $row_item['existe_entradas'];
      $salidas = $row_item['existe_salidas'];
      echo '<td>' . $minimo . '</td>'
        . '<td>' . $maximo . '</td>'
        . '<td>' . $reordena . '</td>'
        . '<td>' . $entradas . '</td>'
        . '<td>' . $salidas . '</td>'
        . '<td>' . $existencias . '</td>';
    } else {
      $entradas = $row_item['existe_entradas'];
      $salidas = $row_item['existe_salidas'];
      echo '<td>' . $maximo . '</td>'
        . '<td>' . $reordena . '</td>'
        . '<td>' . $minimo . '</td>'
        . '<td>' . $entradas . '</td>'
        . '<td>' . $salidas . '</td>'
        . '<td>' . $existencias . '</td>';
    }

    echo '<td>' . $ubicacion . '</td></tr>';
  }

  exit; // Importante: terminar la ejecución aquí para no enviar HTML adicional
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta charset="UTF-8">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      // Verificar si hay parámetro page_cero en la URL o hash #cero para activar el tab correcto
      const urlParams = new URLSearchParams(window.location.search);
      const hash = window.location.hash;

      if (urlParams.has('page_cero') || hash === '#cero') {
        // Activar el tab de existencias en 0
        $('#cero-tab').tab('show');
      }

      // Función para realizar búsqueda
      function realizarBusqueda() {
        var searchTerm = $("#search").val();
        var itemId = $('#mibuscador').val();

        // Verificar que al menos uno de los campos tenga valor
        if (searchTerm.length === 0 && !itemId) {
          alert('Por favor, ingrese un término de búsqueda o seleccione un medicamento.');
          return;
        }

        // Determinar qué tab está activo
        var activeTab = $('.nav-link.active').attr('id');
        var isZeroTab = (activeTab === 'cero-tab');

        // Preparar datos para AJAX
        var ajaxData = {
          'ajax': 1,
          'tab': isZeroTab ? 'cero' : 'normales'
        };

        // Priorizar búsqueda por medicamento si está seleccionado
        if (itemId) {
          ajaxData['item_id'] = itemId;
        } else if (searchTerm.length > 0) {
          if (searchTerm.length < 3) {
            alert('El término de búsqueda debe tener al menos 3 caracteres.');
            return;
          }
          ajaxData['search'] = searchTerm;
        }

        // Realizar búsqueda AJAX
        $.ajax({
          url: window.location.pathname,
          type: 'GET',
          data: ajaxData,
          dataType: 'html',
          success: function(response) {
            // Limpiar la respuesta para asegurar que solo contenga las filas de la tabla
            var cleanResponse = response.trim();

            // Verificar que la respuesta no contenga etiquetas HTML no deseadas
            if (cleanResponse.indexOf('<html>') !== -1 || cleanResponse.indexOf('<!DOCTYPE') !== -1) {
              console.log('Error: La respuesta contiene HTML completo');
              return;
            }

            if (isZeroTab) {
              $('#mytable-cero tbody').empty().html(cleanResponse);
              // Ocultar paginación durante búsqueda
              $('#cero .pagination').hide();
            } else {
              $('#mytable tbody').empty().html(cleanResponse);
              // Ocultar paginación durante búsqueda
              $('#normales .pagination').hide();
            }
          },
          error: function(xhr, status, error) {
            console.log('Error en la búsqueda: ' + error);
            alert('Error al realizar la búsqueda. Por favor, intente de nuevo.');
          }
        });
      }

      // Evento del botón de búsqueda
      $('#btnBuscar').on('click', function() {
        realizarBusqueda();
      });

      // Evento del botón limpiar
      $('#btnLimpiar').on('click', function() {
        $('#search').val('');
        $('#mibuscador').val('').trigger('change');
        window.location.reload();
      });

      // Permitir búsqueda con Enter en el campo de texto
      $("#search").keypress(function(e) {
        if (e.which === 13) { // Enter key
          realizarBusqueda();
        }
      });

      // Limpiar el otro campo cuando se usa uno
      $("#search").on('input', function() {
        if ($(this).val().length > 0) {
          $('#mibuscador').val('').trigger('change');
        }
      });

      $('#mibuscador').on('change', function() {
        if ($(this).val()) {
          $('#search').val('');
        }
      });

      // Mostrar paginación cuando se cambie de tab
      $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('.pagination').show();
      });
    });
  </script>
</head>

<body>
  <div class="container-fluid">

    <!-- Botones -->
    <div class="container botones-superiores">
      <div class="row">
        <div class="col-12 d-flex justify-content-between">
          <div class="row mt-3 mb-3">
            <div class="col-sm-5">
              <a class="btn btn-danger" href="../../template/menu_farmaciahosp.php" style="margin-left: 0px;">Regresar</a>
            </div>
          </div>
          <!-- Botones alineados a la derecha -->
          <div class="d-flex">
            <a type="submit" class="btn btn-warning mx-2 my-3" href="existencias_globalh.php">Existencias Globales</a>
            <a href="excelexistenciash.php" class="btn btn-success mx-2 my-3">Exportar a Excel</a>
          </div>
        </div>
      </div>
    </div>

    <div class="container box">
      <div class="content">
        <div class="thead" style="background-color: white;margin-top: 10px; color: black; font-size: 20px;">

          <div class="thead" style="background-color: #0c675e; margin: 5px auto; padding: 5px; color: white; width: fit-content; text-align: center; border-radius: 5px;">
            <h1 style="font-size: 26px; margin: 2;">EXISTENCIAS DE FARMACIA HOSPITALARIA</h1>
          </div>
        </div>
        <br> <br>

        <!-- Formulario de búsqueda -->
        <div class="container form-search-container">
          <div class="row justify-content-center">
            <!-- Campo de búsqueda (Input) -->
            <div class="col-md-4">
              <div class="form-group">
                <input type="text" class="form-control" id="search" placeholder="Buscar por código o lote..." style="width: 100%; margin-bottom: 20px;">
              </div>
            </div>

            <!-- Selector de medicamentos/insumos -->
            <div class="col-md-4">
              <div class="form-group">
                <select name="item_id" class="form-control" id="mibuscador" style="width: 100%; margin-bottom: 20px;">
                  <option value="">Seleccione un medicamento o insumo</option>
                  <?php
                  $sql = "SELECT * FROM item_almacen ORDER BY item_name";
                  $result = $conexion->query($sql);
                  while ($row_datos = $result->fetch_assoc()) {
                    $selected = ($item_id == $row_datos['item_id']) ? 'selected' : '';
                    echo "<option value='" . $row_datos['item_id'] . "' $selected>" . $row_datos['item_name'] . ', ' . $row_datos['item_grams'] . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <!-- Botón de búsqueda -->
            <div class="col-md-2">
              <div class="form-group">
                <button type="button" class="btn btn-primary" id="btnBuscar" style="width: 100%; margin-bottom: 10px;">
                  Buscar
                </button>
              </div>
            </div>

            <!-- Botón para limpiar -->
            <div class="col-md-2">
              <div class="form-group">
                <button type="button" class="btn btn-secondary" id="btnLimpiar" style="width: 100%; margin-bottom: 10px;">
                  Limpiar
                </button>
              </div>
            </div>

          </div> <!-- Cierre de la fila -->

          <!-- Tabs para separar existencias normales y existencias en 0 -->
          <ul class="nav nav-tabs" id="existenciaTabs" role="tablist" style="margin-top: 10px;">
            <li class="nav-item">
              <a class="nav-link active" id="normales-tab" data-toggle="tab" href="#normales" role="tab" aria-controls="normales" aria-selected="true">
                Existencias Normales
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="cero-tab" data-toggle="tab" href="#cero" role="tab" aria-controls="cero" aria-selected="false">
                Existencias en 0
              </a>
            </li>
          </ul>

          <!-- Leyenda de colores de caducidad -->
          <div style="margin-top: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: white;">
            <span style="background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 3px; margin: 0 5px;">≤ 3 meses</span>
            <span style="background-color: #ffc107; color: black; padding: 3px 8px; border-radius: 3px; margin: 0 5px;">4-6 meses</span>
            <span style="background-color: #28a745; color: white; padding: 3px 8px; border-radius: 3px; margin: 0 5px;">> 6 meses</span>
          </div>


          <div class="tab-content" id="existenciaTabContent">
            <!-- Tab para existencias normales -->
            <div class="tab-pane fade show active" id="normales" role="tabpanel" aria-labelledby="normales-tab">
              <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">
                  <thead class="thead" style="background-color: #0c675e; color: white;">
                    <tr>
                      <th>
                        <font color="white">Código
                      </th>
                      <th>
                        <font color="white">Medicamento / Insumo
                      </th>
                      <th>
                        <font color="white">Lote
                      </th>
                      <th>
                        <font color="white">Caducidad
                      </th>
                      <th>
                        <font color="white">Máximo
                      </th>
                      <th>
                        <font color="white">P.reorden
                      </th>
                      <th>
                        <font color="white">Mínimo
                      </th>
                      <th>
                        <font color="white">Entradas
                      </th>
                      <th>
                        <font color="white">Salidas
                      </th>
                      <th>
                        <font color="white">Existencias
                      </th>
                      <th>
                        <font color="white">Ubicación
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Configuración de la paginación para existencias normales
                    $records_per_page = 50;
                    $query_normales_count = "
                                SELECT COUNT(*) as total
                                FROM item_almacen ia
                                INNER JOIN existencias_almacenh ea ON ia.item_id = ea.item_id
                                WHERE ea.existe_qty > 0
                            ";
                    $result_count = $conexion->query($query_normales_count);
                    $total_records = $result_count->fetch_assoc()['total'];
                    $total_pages = ceil($total_records / $records_per_page);

                    // Obtener la página actual
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $start_from = ($page - 1) * $records_per_page;

                    // Consulta con limit para obtener solo los registros necesarios
                    $query_normales = "
                                SELECT 
                                    ia.item_id,
                                    ia.item_code,
                                    ia.item_name,
                                    ia.item_grams,
                                    ia.item_max,
                                    ia.item_min,
                                    ia.reorden,
                                    it.item_type_desc,
                                    ea.existe_id,
                                    ea.existe_lote,
                                    ea.existe_caducidad,
                                    ea.existe_qty,
                                    ea.existe_entradas,
                                    ea.existe_salidas,
                                    ea.ubicacion_id
                                FROM item_almacen ia
                                INNER JOIN existencias_almacenh ea ON ia.item_id = ea.item_id
                                INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
                                WHERE ea.existe_qty > 0
                                ORDER BY ia.item_code ASC, ea.existe_caducidad DESC
                                LIMIT $start_from, $records_per_page
                            ";
                    $resultado_normales = $conexion->query($query_normales) or die($conexion->error);

                    // Mostrar las filas de existencias normales
                    while ($row_normales = $resultado_normales->fetch_assoc()) {
                      $caduca = date_create($row_normales['existe_caducidad']);
                      $existencias = $row_normales['existe_qty'];
                      $entradas = $row_normales['existe_entradas'];
                      $salidas = $row_normales['existe_salidas'];
                      $maximo = $row_normales['item_max'];
                      $minimo = $row_normales['item_min'];
                      $reordena = $row_normales['reorden'];
                      $id_ubica = $row_normales['ubicacion_id'];

                      // Calcular los meses hasta la caducidad
                      $fecha_actual = new DateTime();
                      $meses_hasta_caducidad = $fecha_actual->diff($caduca)->m + ($fecha_actual->diff($caduca)->y * 12);
                      if ($caduca < $fecha_actual) {
                        $meses_hasta_caducidad = 0; // Si ya venció
                      }

                      // Determinar color de fondo según caducidad
                      $color_caducidad = '';
                      if ($meses_hasta_caducidad <= 3) {
                        $color_caducidad = 'style="background-color: #dc3545; color: white;"'; // Rojo
                      } elseif ($meses_hasta_caducidad > 3 && $meses_hasta_caducidad <= 6) {
                        $color_caducidad = 'style="background-color: #ffc107; color: black;"'; // Amarillo
                      } else {
                        $color_caducidad = 'style="background-color: #28a745; color: white;"'; // Verde
                      }

                      // Obtener ubicación
                      $result3 = $conexion->query("SELECT * FROM ubicaciones_almacen WHERE ubicacion_id = $id_ubica") or die($conexion->error);
                      $ubicacion = 'Sin ubicación';
                      while ($row3 = $result3->fetch_assoc()) {
                        $ubicacion = $row3['nombre_ubicacion'];
                      }

                      // Mostrar fila
                      $fila = '<tr>'
                        . '<td>' . $row_normales['item_code'] . '</td>'
                        . '<td>' . $row_normales['item_name'] . ', ' . $row_normales['item_grams'] . ', ' . $row_normales['item_type_desc'] . '</td>'
                        . '<td>' . $row_normales['existe_lote'] . '</td>'
                        . '<td ' . $color_caducidad . '>' . date_format($caduca, "d/m/Y") . '</td>'
                        . '<td>' . $maximo . '</td>'
                        . '<td>' . $reordena . '</td>'
                        . '<td>' . $minimo . '</td>'
                        . '<td>' . $entradas . '</td>'
                        . '<td>' . $salidas . '</td>'
                        . '<td>' . $existencias . '</td>'
                        . '<td>' . $ubicacion . '</td>'
                        . '</tr>';
                      echo $fila;
                    }
                    ?>
                  </tbody>
                </table>
              </div>

              <!-- Paginación para existencias normales -->
              <div class="pagination">
                <?php
                // Establecer el rango de páginas a mostrar
                $rango = 5;

                // Determinar el inicio y fin del rango de páginas a mostrar
                $inicio = max(1, $page - $rango);
                $fin = min($total_pages, $page + $rango);

                // Mostrar el enlace a la primera página
                if ($page > 1) {
                  echo '<a href="?page=1">&laquo; Primero</a>';
                  echo '<a href="?page=' . ($page - 1) . '">&lt; Anterior</a>';
                }

                // Mostrar las páginas dentro del rango
                for ($i = $inicio; $i <= $fin; $i++) {
                  echo '<a href="?page=' . $i . '" class="' . ($i == $page ? 'current' : '') . '">' . $i . '</a>';
                }

                // Mostrar el enlace a la siguiente página
                if ($page < $total_pages) {
                  echo '<a href="?page=' . ($page + 1) . '">Siguiente &gt;</a>';
                  echo '<a href="?page=' . $total_pages . '">Último &raquo;</a>';
                }

                ?>
              </div>

            </div>



            <!-- Tab para existencias en 0 -->
            <div class="tab-pane fade" id="cero" role="tabpanel" aria-labelledby="cero-tab">
              <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable-cero">
                  <thead class="thead" style="background-color: #0c675e; color: white;">
                    <tr>
                      <th>
                        <font color="white">Código
                      </th>
                      <th>
                        <font color="white">Medicamento
                      </th>
                      <th>
                        <font color="white">Lote
                      </th>
                      <th>
                        <font color="white">Caducidad
                      </th>

                      <th>
                        <font color="white">Mínimo
                      </th>
                      <th>
                        <font color="white">Máximo
                      </th>

                      <th>
                        <font color="white">P.reorden
                      </th>
                      <th>
                        <font color="white">Entradas
                      </th>
                      <th>
                        <font color="white">Salidas
                      </th>
                      <th>
                        <font color="white">Existencias
                      </th>
                      <th>
                        <font color="white">Ubicación
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Configuración de la paginación para existencias en 0
                    $query_cero_count = "
                                SELECT COUNT(*) as total
                                FROM item_almacen ia
                                INNER JOIN existencias_almacenh ea ON ia.item_id = ea.item_id
                                WHERE ea.existe_qty = 0
                            ";
                    $result_cero_count = $conexion->query($query_cero_count);
                    $total_records_cero = $result_cero_count->fetch_assoc()['total'];
                    $total_pages_cero = ceil($total_records_cero / $records_per_page);

                    $page_cero = isset($_GET['page_cero']) ? $_GET['page_cero'] : 1;
                    $start_from_cero = ($page_cero - 1) * $records_per_page;

                    $query_cero = "
                                SELECT 
                                    ia.item_id,
                                    ia.item_code,
                                    ia.item_name,
                                    ia.item_grams,
                                    ia.item_max,
                                    ia.item_min,
                                    ia.reorden,
                                    it.item_type_desc,
                                    ea.existe_id,
                                    ea.existe_lote,
                                    ea.existe_caducidad,
                                    ea.existe_qty,
                                    ea.existe_entradas,
                                    ea.existe_salidas,
                                    ea.ubicacion_id
                                FROM item_almacen ia
                                INNER JOIN existencias_almacenh ea ON ia.item_id = ea.item_id
                                INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
                                WHERE ea.existe_qty = 0
                                ORDER BY ia.item_code ASC
                                LIMIT $start_from_cero, $records_per_page
                            ";
                    $resultado_cero = $conexion->query($query_cero) or die($conexion->error);

                    // Mostrar filas de existencias en 0
                    while ($row_cero = $resultado_cero->fetch_assoc()) {
                      $caduca = date_create($row_cero['existe_caducidad']);
                      $entradas = $row_cero['existe_entradas'];
                      $salidas = $row_cero['existe_salidas'];
                      $existencias = $row_cero['existe_qty'];
                      $maximo = $row_cero['item_max'];
                      $minimo = $row_cero['item_min'];
                      $reordena = $row_cero['reorden'];
                      $id_ubica = $row_cero['ubicacion_id'];

                      // Calcular los meses hasta la caducidad
                      $fecha_actual = new DateTime();
                      $meses_hasta_caducidad = $fecha_actual->diff($caduca)->m + ($fecha_actual->diff($caduca)->y * 12);
                      if ($caduca < $fecha_actual) {
                        $meses_hasta_caducidad = 0; // Si ya venció
                      }

                      // Determinar color de fondo según caducidad
                      $color_caducidad = '';
                      if ($meses_hasta_caducidad <= 3) {
                        $color_caducidad = 'style="background-color: #dc3545; color: white;"'; // Rojo
                      } elseif ($meses_hasta_caducidad > 3 && $meses_hasta_caducidad <= 6) {
                        $color_caducidad = 'style="background-color: #ffc107; color: black;"'; // Amarillo
                      } else {
                        $color_caducidad = 'style="background-color: #28a745; color: white;"'; // Verde
                      }

                      // Obtener ubicación
                      $result3 = $conexion->query("SELECT * FROM ubicaciones_almacen WHERE ubicacion_id = $id_ubica") or die($conexion->error);
                      $ubicacion = 'Sin ubicación';
                      while ($row3 = $result3->fetch_assoc()) {
                        $ubicacion = $row3['nombre_ubicacion'];
                      }

                      // Mostrar fila
                      $fila = '<tr>'
                        . '<td>' . $row_cero['item_code'] . '</td>'
                        . '<td>' . $row_cero['item_name'] . ', ' . $row_cero['item_grams'] . ', ' . $row_cero['item_type_desc'] . '</td>'
                        . '<td>' . $row_cero['existe_lote'] . '</td>'
                        . '<td ' . $color_caducidad . '>' . date_format($caduca, "d/m/Y") . '</td>'
                        . '<td>' . $minimo . '</td>'
                        . '<td>' . $maximo . '</td>'
                        . '<td>' . $reordena . '</td>'
                        . '<td>' . $entradas . '</td>'
                        . '<td>' . $salidas . '</td>'
                        . '<td>' . $existencias . '</td>'
                        . '<td>' . $ubicacion . '</td>'
                        . '</tr>';
                      echo $fila;
                    }
                    ?>
                  </tbody>
                </table>
              </div>


              <!-- Paginación para existencias en 0 -->
              <div class="pagination">
                <?php
                // Establecer el rango de páginas a mostrar
                $rango = 5;

                // Determinar el inicio y fin del rango de páginas a mostrar
                $inicio_cero = max(1, $page_cero - $rango);
                $fin_cero = min($total_pages_cero, $page_cero + $rango);

                // Mostrar el enlace a la primera página
                if ($page_cero > 1) {
                  echo '<a href="?page_cero=1#cero">&laquo; Primero</a>';
                  echo '<a href="?page_cero=' . ($page_cero - 1) . '#cero">&lt; Anterior</a>';
                }

                // Mostrar las páginas dentro del rango
                for ($i = $inicio_cero; $i <= $fin_cero; $i++) {
                  echo '<a href="?page_cero=' . $i . '#cero" class="' . ($i == $page_cero ? 'current' : '') . '">' . $i . '</a>';
                }

                // Mostrar el enlace a la siguiente página
                if ($page_cero < $total_pages_cero) {
                  echo '<a href="?page_cero=' . ($page_cero + 1) . '#cero">Siguiente &gt;</a>';
                  echo '<a href="?page_cero=' . $total_pages_cero . '#cero">Último &raquo;</a>';
                }
                ?>
              </div>
            </div>
          </div>
        </div>
        <style>
          .total-row {
            background-color: #0c675e;
            color: white;
          }

          .ultima-existencia {
            background-color: #0c675e;
            color: white;
          }

          /* Hacer la tabla completamente responsive y más ancha */
          .table-responsive {
            max-height: 80vh;
            overflow-x: auto;
            overflow-y: auto;
            width: 100%;
            margin: 0;
            padding: 0;
          }

          /* Contenedor principal más ancho */
          .container.box {
            max-width: 100%;
            width: 100%;
            margin: 0;
            padding: 0 10px;
          }

          /* Contenedor fluid para usar todo el ancho */
          .container-fluid {
            padding: 0;
            margin: 0;
            width: 100%;
            max-width: 100%;
          }

          /* Tabla más ancha y responsive */
          .table {
            font-size: 11px;
            width: 100%;
            min-width: 1200px;
            /* Ancho mínimo para evitar compresión excesiva */
            margin: 0;
            border-collapse: collapse;
          }

          .table th,
          .table td {
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            border: 1px solid #dee2e6;
          }

          .table th {
            font-size: 10px;
            font-weight: bold;
            background-color: #0c675e !important;
            color: white !important;
            position: sticky;
            top: 0;
            z-index: 10;
          }

          /* Columnas específicas con anchos ajustados */
          .table th:nth-child(1),
          /* Código */
          .table td:nth-child(1) {
            min-width: 80px;
            max-width: 100px;
          }

          .table th:nth-child(2),
          /* Medicamento */
          .table td:nth-child(2) {
            min-width: 250px;
            max-width: 350px;
            white-space: normal;
            word-wrap: break-word;
            text-align: left;
          }

          .table th:nth-child(3),
          /* Lote */
          .table td:nth-child(3) {
            min-width: 80px;
            max-width: 120px;
          }

          .table th:nth-child(4),
          /* Caducidad */
          .table td:nth-child(4) {
            min-width: 90px;
            max-width: 110px;
          }

          .table th:nth-child(n+5),
          /* Otras columnas */
          .table td:nth-child(n+5) {
            min-width: 70px;
            max-width: 100px;
          }

          .table th:nth-child(11),
          /* Ubicación */
          .table td:nth-child(11) {
            min-width: 120px;
            max-width: 150px;
            white-space: normal;
            word-wrap: break-word;
            text-align: left;
          }

          /* Responsive para dispositivos móviles */
          @media (max-width: 768px) {
            .table {
              font-size: 9px;
              min-width: 900px;
            }

            .table th,
            .table td {
              padding: 4px 6px;
            }

            .container.box {
              padding: 0 5px;
            }
          }

          @media (max-width: 576px) {
            .table {
              font-size: 8px;
              min-width: 800px;
            }

            .table th,
            .table td {
              padding: 3px 4px;
            }
          }

          .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
          }

          .pagination a {
            padding: 8px 12px;
            text-decoration: none;
            background-color: #0c675e;
            color: white;
            border-radius: 5px;
            margin: 2px 3px;
            font-size: 12px;
          }

          .pagination a:hover {
            background-color: #084c47;
          }

          .pagination .current {
            background-color: #ff7f50;
            color: white;
            font-weight: bold;
          }

          /* Estilos para los botones de búsqueda */
          #btnBuscar {
            background-color: #0c675e;
            border-color: #0c675e;
            font-weight: bold;
          }

          #btnBuscar:hover {
            background-color: #084c47;
            border-color: #084c47;
          }

          #btnLimpiar {
            background-color: #6c757d;
            border-color: #6c757d;
          }

          #btnLimpiar:hover {
            background-color: #545b62;
            border-color: #545b62;
          }

          /* Asegurar que los campos de búsqueda tengan el mismo alto que los botones */
          #search,
          #mibuscador,
          #btnBuscar,
          #btnLimpiar {
            height: 38px;
          }

          /* Contenedor de formulario más responsive */
          .form-search-container {
            margin-bottom: 20px;
          }

          @media (max-width: 768px) {

            .form-search-container .col-md-4,
            .form-search-container .col-md-2 {
              margin-bottom: 10px;
            }
          }

          /* Tabs responsivos */
          .nav-tabs {
            margin-top: 10px;
            border-bottom: 2px solid #0c675e;
          }

          .nav-tabs .nav-link {
            color: #0c675e;
            font-weight: bold;
          }

          .nav-tabs .nav-link.active {
            background-color: #0c675e;
            color: white;
            border-color: #0c675e;
          }

          /* Título responsive */
          .titulo-principal {
            background-color: #0c675e;
            margin: 5px auto;
            padding: 10px;
            color: white;
            text-align: center;
            border-radius: 5px;
            width: 100%;
          }

          .titulo-principal h1 {
            font-size: 24px;
            margin: 0;
            color: white;
          }

          @media (max-width: 768px) {
            .titulo-principal h1 {
              font-size: 18px;
            }
          }

          @media (max-width: 576px) {
            .titulo-principal h1 {
              font-size: 16px;
            }
          }

          /* Botones superiores responsivos */
          .botones-superiores {
            margin: 10px 0;
          }

          @media (max-width: 768px) {
            .botones-superiores .d-flex {
              flex-direction: column;
              align-items: stretch;
            }

            .botones-superiores .btn {
              margin: 5px 0 !important;
              width: 100%;
            }
          }
        </style>

        <script>
          $(document).ready(function() {
            $('#mibuscador').select2({
              placeholder: "Seleccione un medicamento",
              allowClear: true,
              width: '100%'
            });
          });
        </script>
</body>

</html>