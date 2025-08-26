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
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
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
        <!-- Botones de navegación -->
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-center flex-wrap">
                    <a href="../../template/menu_farmaciacentral.php" class="btn-moderno btn-regresar mx-2 mb-2">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                    <a href="existencias_global.php" class="btn-moderno btn-warning-moderno mx-2 mb-2">
                        <i class="fas fa-globe"></i> Existencias Globales
                    </a>
                    <a href="excelexistenciash.php" class="btn-moderno btn-success-moderno mx-2 mb-2">
                        <i class="fas fa-file-excel"></i> Exportar a Excel
                    </a>
                </div>
            </div>
        </div>
        <!-- Container principal moderno -->
        <div class="container-moderno">
            <!-- Header principal -->
            <div class="header-principal">
                <i class="fas fa-pills icono-principal"></i>
                <h1>EXISTENCIAS DE FARMACIA HOSPITALARIA</h1>
            </div>

            <!-- Contenedor de filtros -->
            <div class="contenedor-filtros">
                <div class="row justify-content-center">
                    <!-- Campo de búsqueda -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-search"></i> Búsqueda rápida
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" id="search" placeholder="Buscar por código o lote...">
                            </div>
                        </div>
                    </div>
                    <!-- Selector de medicamentos -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-capsules"></i> Medicamento/Insumo
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-capsules"></i></span>
                                </div>
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
                    </div>

                    <!-- Botones de acción -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn-moderno btn-especial d-block w-100" id="btnBuscar">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn-moderno btn-borrar d-block w-100" id="btnLimpiar">
                                <i class="fas fa-broom"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabs para separar existencias -->
                <ul class="nav nav-tabs" id="existenciaTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="normales-tab" data-toggle="tab" href="#normales" role="tab" aria-controls="normales" aria-selected="true">
                            <i class="fas fa-check-circle"></i> Existencias Normales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="cero-tab" data-toggle="tab" href="#cero" role="tab" aria-controls="cero" aria-selected="false">
                            <i class="fas fa-exclamation-triangle"></i> Existencias en 0
                        </a>
                    </li>
                </ul>

                <!-- Leyenda de colores de caducidad -->
                <div class="legend-container">
                    <strong><i class="fas fa-calendar-alt"></i> Leyenda de Caducidad:</strong>
                    <span class="legend-item" style="background-color: #dc3545; color: white;">
                        <i class="fas fa-exclamation-circle"></i> ≤ 3 meses
                    </span>
                    <span class="legend-item" style="background-color: #ffc107; color: black;">
                        <i class="fas fa-exclamation-triangle"></i> 4-6 meses
                    </span>
                    <span class="legend-item" style="background-color: #28a745; color: white;">
                        <i class="fas fa-check-circle"></i> > 6 meses
                    </span>
                </div>
            </div>


            <!-- Contenido de las tabs -->
            <div class="tab-content" id="existenciaTabContent">
                <!-- Tab para existencias normales -->
                <div class="tab-pane fade show active" id="normales" role="tabpanel" aria-labelledby="normales-tab">
                    <div class="tabla-contenedor">
                        <table class="table table-moderna" id="mytable">
                            <thead>
                            <tr>
                                <th>
                                    <i class="fas fa-barcode icon-header"></i>
                                    Código
                                </th>
                                <th>
                                    <i class="fas fa-pills icon-header"></i>
                                    Medicamento / Insumo
                                </th>
                                <th>
                                    <i class="fas fa-tags icon-header"></i>
                                    Lote
                                </th>
                                <th>
                                    <i class="fas fa-calendar-alt icon-header"></i>
                                    Caducidad
                                </th>
                                <th>
                                    <i class="fas fa-arrow-up icon-header"></i>
                                    Máximo
                                </th>
                                <th>
                                    <i class="fas fa-refresh icon-header"></i>
                                    P.reorden
                                </th>
                                <th>
                                    <i class="fas fa-arrow-down icon-header"></i>
                                    Mínimo
                                </th>
                                <th>
                                    <i class="fas fa-plus-circle icon-header"></i>
                                    Entradas
                                </th>
                                <th>
                                    <i class="fas fa-minus-circle icon-header"></i>
                                    Salidas
                                </th>
                                <th>
                                    <i class="fas fa-boxes icon-header"></i>
                                    Existencias
                                </th>
                                <th>
                                    <i class="fas fa-map-marker-alt icon-header"></i>
                                    Ubicación
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

                    <!-- Paginación moderna para existencias normales -->
                    <div class="contenedor-paginacion" id="paginacion-normales">
                        <div class="paginacion-moderna">
                            <?php
                            // Establecer el rango de páginas a mostrar
                            $rango = 5;

                            // Determinar el inicio y fin del rango de páginas a mostrar
                            $inicio = max(1, $page - $rango);
                            $fin = min($total_pages, $page + $rango);

                            // Mostrar el enlace a la primera página
                            if ($page > 1) {
                                echo '<a class="btn-paginacion" href="?page=1">&laquo; Primero</a>';
                                echo '<a class="btn-paginacion" href="?page=' . ($page - 1) . '">&lt; Anterior</a>';
                            }

                            // Mostrar las páginas dentro del rango
                            for ($i = $inicio; $i <= $fin; $i++) {
                                $active = ($i == $page) ? ' active' : '';
                                echo '<a class="btn-paginacion' . $active . '" href="?page=' . $i . '">' . $i . '</a>';
                            }

                            // Mostrar el enlace a la siguiente página
                            if ($page < $total_pages) {
                                echo '<a class="btn-paginacion" href="?page=' . ($page + 1) . '">Siguiente &gt;</a>';
                                echo '<a class="btn-paginacion" href="?page=' . $total_pages . '">Último &raquo;</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                    <div class="tab-pane fade" id="cero" role="tabpanel" aria-labelledby="cero-tab">
                    <div class="tabla-contenedor">
                        <table class="table table-moderna" id="mytable-cero">
                            <thead>
                            <tr>
                                <th>
                                    <i class="fas fa-barcode icon-header"></i>
                                    Código
                                </th>
                                <th>
                                    <i class="fas fa-pills icon-header"></i>
                                    Medicamento
                                </th>
                                <th>
                                    <i class="fas fa-tags icon-header"></i>
                                    Lote
                                </th>
                                <th>
                                    <i class="fas fa-calendar-alt icon-header"></i>
                                    Caducidad
                                </th>
                                <th>
                                    <i class="fas fa-arrow-down icon-header"></i>
                                    Mínimo
                                </th>
                                <th>
                                    <i class="fas fa-arrow-up icon-header"></i>
                                    Máximo
                                </th>
                                <th>
                                    <i class="fas fa-refresh icon-header"></i>
                                    P.reorden
                                </th>
                                <th>
                                    <i class="fas fa-plus-circle icon-header"></i>
                                    Entradas
                                </th>
                                <th>
                                    <i class="fas fa-minus-circle icon-header"></i>
                                    Salidas
                                </th>
                                <th>
                                    <i class="fas fa-exclamation-triangle icon-header"></i>
                                    Existencias
                                </th>
                                <th>
                                    <i class="fas fa-map-marker-alt icon-header"></i>
                                    Ubicación
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


                        <!-- Paginación moderna para existencias en 0 -->
                        <div class="contenedor-paginacion" id="paginacion-cero">
                            <div class="paginacion-moderna">
                                <?php
                                // Establecer el rango de páginas a mostrar
                                $rango = 5;

                                // Determinar el inicio y fin del rango de páginas a mostrar
                                $inicio_cero = max(1, $page_cero - $rango);
                                $fin_cero = min($total_pages_cero, $page_cero + $rango);

                                // Mostrar el enlace a la primera página
                                if ($page_cero > 1) {
                                    echo '<a class="btn-paginacion" href="?page_cero=1#cero">&laquo; Primero</a>';
                                    echo '<a class="btn-paginacion" href="?page_cero=' . ($page_cero - 1) . '#cero">&lt; Anterior</a>';
                                }

                                // Mostrar las páginas dentro del rango
                                for ($i = $inicio_cero; $i <= $fin_cero; $i++) {
                                    $active = ($i == $page_cero) ? ' active' : '';
                                    echo '<a class="btn-paginacion' . $active . '" href="?page_cero=' . $i . '#cero">' . $i . '</a>';
                                }

                                // Mostrar el enlace a la siguiente página
                                if ($page_cero < $total_pages_cero) {
                                    echo '<a class="btn-paginacion" href="?page_cero=' . ($page_cero + 1) . '#cero">Siguiente &gt;</a>';
                                    echo '<a class="btn-paginacion" href="?page_cero=' . $total_pages_cero . '#cero">Último &raquo;</a>';
                                }
                                ?>
                            </div>
                        </div>

                    </div>
          </div>
        </div>
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
                .btn-warning-moderno {
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
                color: white !important;
            }
                .btn-success-moderno {
                background: linear-gradient(135deg, #28a745 0%, #218838 100%);
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
                .btn-moderno:active {
                transform: translateY(0);
                box-shadow: var(--sombra);
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