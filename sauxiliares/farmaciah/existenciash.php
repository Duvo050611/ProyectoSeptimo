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

  exit;
}

if (isset($_GET['ajax']) && isset($_GET['item_id'])) {
  $item_id = intval($_GET['item_id']);
  $tab = isset($_GET['tab']) ? $_GET['tab'] : 'normales';

  ob_clean();
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
  
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  
  <!-- jQuery y Scripts -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
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

        if (searchTerm.length === 0) {
          alert('Por favor, ingrese un término de búsqueda.');
          return;
        }

        var activeTab = $('.nav-link.active').attr('id');
        var isZeroTab = (activeTab === 'cero-tab');

        var ajaxData = {
          'ajax': 1,
          'tab': isZeroTab ? 'cero' : 'normales'
        };

        if (searchTerm.length < 3) {
          alert('El término de búsqueda debe tener al menos 3 caracteres.');
          return;
        }
        ajaxData['search'] = searchTerm;

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
              $('#cero .contenedor-paginacion').hide();
            } else {
              $('#mytable tbody').empty().html(cleanResponse);
              // Ocultar paginación durante búsqueda
              $('#normales .contenedor-paginacion').hide();
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
        window.location.reload();
      });

      $("#search").keypress(function(e) {
        if (e.which === 13) {
          realizarBusqueda();
        }
      });

      // Mostrar paginación cuando se cambie de tab
      $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('.contenedor-paginacion').show();
      });
    });
  </script>
</head>

<body>
  <div class="container-fluid">

    <!-- Botones modernizados -->
    <div class="container-fluid botones-superiores">
      <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
          <div class="d-flex">
            <a href="../../template/menu_farmaciahosp.php" class="btn-moderno btn-regresar">
              <i class="fas fa-arrow-left"></i> Regresar
            </a>
          </div>
          <!-- Botones alineados a la derecha -->
          <div class="d-flex gap-3">
            <a href="existencias_globalh.php" class="btn-moderno btn-warning">
              <i class="fas fa-globe"></i> Existencias Globales
            </a>
            <a href="excelexistenciash.php" class="btn-moderno btn-success">
              <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="container-moderno">
      <div class="contenido-principal">
        <!-- Título modernizado -->
        <div class="header-principal">
          <div class="titulo-contenedor">
            <div class="icono-titulo">
              <i class="fas fa-pills"></i>
            </div>
            <h1>EXISTENCIAS DE FARMACIA HOSPITALARIA</h1>
          </div>
        </div>

        <div class="contenedor-busqueda">
          <div class="fila-busqueda">
            <div class="campo-busqueda">
              <div class="input-con-icono">
                <i class="fas fa-search icono-input"></i>
                <input type="text" class="form-control input-moderno" id="search" placeholder="Buscar por código o lote...">
              </div>
            </div>

            <div class="botones-busqueda">
              <button type="button" class="btn-moderno btn-primary" id="btnBuscar">
                <i class="fas fa-search"></i> Buscar
              </button>
              <button type="button" class="btn-moderno btn-secondary" id="btnLimpiar">
                <i class="fas fa-eraser"></i> Limpiar
              </button>
            </div>
          </div>
        </div>

          <div class="contenedor-tabs">
            <ul class="nav nav-tabs tabs-modernos" id="existenciaTabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="normales-tab" data-toggle="tab" href="#normales" role="tab" aria-controls="normales" aria-selected="true">
                  <i class="fas fa-box"></i> Existencias Normales
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="cero-tab" data-toggle="tab" href="#cero" role="tab" aria-controls="cero" aria-selected="false">
                  <i class="fas fa-exclamation-triangle"></i> Existencias en 0
                </a>
              </li>
            </ul>
          </div>

          <div class="leyenda-contenedor">
            <div class="leyenda-titulo">
              <i class="fas fa-info-circle"></i> Leyenda de Caducidad:
            </div>
            <div class="leyenda-items">
              <span class="leyenda-item peligro">
                <i class="fas fa-clock"></i> ≤ 3 meses
              </span>
              <span class="leyenda-item advertencia">
                <i class="fas fa-exclamation"></i> 4-6 meses
              </span>
              <span class="leyenda-item exitoso">
                <i class="fas fa-check"></i> > 6 meses
              </span>
            </div>
          </div>


          <div class="tab-content" id="existenciaTabContent">
            <div class="tab-pane fade show active" id="normales" role="tabpanel" aria-labelledby="normales-tab">
              <div class="tabla-contenedor">
                <table class="table tabla-moderna" id="mytable">
                  <thead>
                    <tr>
                      <th><i class="fas fa-barcode"></i> Código</th>
                      <th><i class="fas fa-pills"></i> Medicamento / Insumo</th>
                      <th><i class="fas fa-tag"></i> Lote</th>
                      <th><i class="fas fa-calendar-alt"></i> Caducidad</th>
                      <th><i class="fas fa-arrow-up"></i> Máximo</th>
                      <th><i class="fas fa-repeat"></i> P.reorden</th>
                      <th><i class="fas fa-arrow-down"></i> Mínimo</th>
                      <th><i class="fas fa-plus-circle"></i> Entradas</th>
                      <th><i class="fas fa-minus-circle"></i> Salidas</th>
                      <th><i class="fas fa-box"></i> Existencias</th>
                      <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
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

              <!-- Paginación modernizada para existencias normales -->
              <div class="contenedor-paginacion">
                <div class="paginacion-moderna">
                  <?php
                  // Establecer el rango de páginas a mostrar
                  $rango = 5;

                  // Determinar el inicio y fin del rango de páginas a mostrar
                  $inicio = max(1, $page - $rango);
                  $fin = min($total_pages, $page + $rango);

                  // Mostrar el enlace a la primera página
                  if ($page > 1) {
                    echo '<a href="?page=1" class="btn-paginacion"><i class="fas fa-angle-double-left"></i> Primero</a>';
                    echo '<a href="?page=' . ($page - 1) . '" class="btn-paginacion"><i class="fas fa-angle-left"></i> Anterior</a>';
                  }

                  // Mostrar las páginas dentro del rango
                  for ($i = $inicio; $i <= $fin; $i++) {
                    echo '<a href="?page=' . $i . '" class="btn-paginacion ' . ($i == $page ? 'activo' : '') . '">' . $i . '</a>';
                  }

                  // Mostrar el enlace a la siguiente página
                  if ($page < $total_pages) {
                    echo '<a href="?page=' . ($page + 1) . '" class="btn-paginacion">Siguiente <i class="fas fa-angle-right"></i></a>';
                    echo '<a href="?page=' . $total_pages . '" class="btn-paginacion">Último <i class="fas fa-angle-double-right"></i></a>';
                  }
                  ?>
                </div>
              </div>

            </div>



            <!-- Tab para existencias en 0 -->
            <div class="tab-pane fade" id="cero" role="tabpanel" aria-labelledby="cero-tab">
              <div class="tabla-contenedor">
                <table class="table tabla-moderna" id="mytable-cero">
                  <thead>
                    <tr>
                      <th><i class="fas fa-barcode"></i> Código</th>
                      <th><i class="fas fa-pills"></i> Medicamento</th>
                      <th><i class="fas fa-tag"></i> Lote</th>
                      <th><i class="fas fa-calendar-alt"></i> Caducidad</th>
                      <th><i class="fas fa-arrow-down"></i> Mínimo</th>
                      <th><i class="fas fa-arrow-up"></i> Máximo</th>
                      <th><i class="fas fa-repeat"></i> P.reorden</th>
                      <th><i class="fas fa-plus-circle"></i> Entradas</th>
                      <th><i class="fas fa-minus-circle"></i> Salidas</th>
                      <th><i class="fas fa-box"></i> Existencias</th>
                      <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
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


              <!-- Paginación modernizada para existencias en 0 -->
              <div class="contenedor-paginacion">
                <div class="paginacion-moderna">
                  <?php
                  // Establecer el rango de páginas a mostrar
                  $rango = 5;

                  // Determinar el inicio y fin del rango de páginas a mostrar
                  $inicio_cero = max(1, $page_cero - $rango);
                  $fin_cero = min($total_pages_cero, $page_cero + $rango);

                  // Mostrar el enlace a la primera página
                  if ($page_cero > 1) {
                    echo '<a href="?page_cero=1#cero" class="btn-paginacion"><i class="fas fa-angle-double-left"></i> Primero</a>';
                    echo '<a href="?page_cero=' . ($page_cero - 1) . '#cero" class="btn-paginacion"><i class="fas fa-angle-left"></i> Anterior</a>';
                  }

                  // Mostrar las páginas dentro del rango
                  for ($i = $inicio_cero; $i <= $fin_cero; $i++) {
                    echo '<a href="?page_cero=' . $i . '#cero" class="btn-paginacion ' . ($i == $page_cero ? 'activo' : '') . '">' . $i . '</a>';
                  }

                  // Mostrar el enlace a la siguiente página
                  if ($page_cero < $total_pages_cero) {
                    echo '<a href="?page_cero=' . ($page_cero + 1) . '#cero" class="btn-paginacion">Siguiente <i class="fas fa-angle-right"></i></a>';
                    echo '<a href="?page_cero=' . $total_pages_cero . '#cero" class="btn-paginacion">Último <i class="fas fa-angle-double-right"></i></a>';
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <style>
          /* ===== ESTILOS MODERNOS CON COLOR PREDETERMINADO #2b2d7f ===== */
          
          body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
          }

          /* ===== CONTENEDORES PRINCIPALES ===== */
          .container-fluid {
            max-width: 100%;
            margin: 0;
            padding: 20px;
          }

          .container-moderno {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(43, 45, 127, 0.15);
            overflow: hidden;
            margin: 20px auto;
            max-width: 98%;
          }

          .contenido-principal {
            padding: 30px;
          }

          /* ===== BOTONES MODERNOS ===== */
          .botones-superiores {
            margin-bottom: 30px;
          }

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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
          }

          .btn-regresar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
          }

          .btn-primary {
            background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
            color: white !important;
          }

          .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white !important;
          }

          .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white !important;
          }

          .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #000 !important;
          }

          .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
          }

          .gap-3 {
            gap: 1rem !important;
          }

          /* ===== HEADER SECTION ===== */
          .header-principal {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 0;
            background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
            margin: -30px -30px 40px -30px;
            color: white;
          }

          .titulo-contenedor {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
          }

          .icono-titulo {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
          }

          .header-principal h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
          }

          /* ===== BÚSQUEDA MODERNIZADA ===== */
          .contenedor-busqueda {
            background: #f8f9ff;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #e8ebff;
          }

          .fila-busqueda {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: end;
          }

          .campo-busqueda {
            position: relative;
            display: flex;
            align-items: stretch;
          }

          .input-con-icono {
            position: relative;
            width: 100%;
            display: flex;
            align-items: stretch;
          }

          .icono-input {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #2b2d7f;
            z-index: 2;
          }

          .input-moderno, .select-moderno {
            width: 100%;
            height: 56px; /* Altura fija para todos los elementos */
            padding: 15px 15px 15px 45px;
            border: 2px solid #e8ebff;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
            box-sizing: border-box;
            display: flex;
            align-items: center;
          }

          .input-moderno:focus, .select-moderno:focus {
            outline: none;
            border-color: #2b2d7f;
            box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
            transform: translateY(-1px);
          }

          .botones-busqueda {
            display: flex;
            gap: 10px;
            align-items: stretch;
          }

          .botones-busqueda .btn-moderno {
            height: 56px; /* Misma altura que los inputs */
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
          }

          /* ===== TABS MODERNIZADOS ===== */
          .contenedor-tabs {
            margin: 30px 0;
          }

          .tabs-modernos {
            border: none;
            background: #f8f9ff;
            border-radius: 12px;
            padding: 8px;
            margin-bottom: 0;
          }

          .tabs-modernos .nav-link {
            border: none;
            border-radius: 8px;
            padding: 15px 25px;
            color: #2b2d7f;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 4px;
          }

          .tabs-modernos .nav-link:hover {
            background: rgba(43, 45, 127, 0.1);
            color: #2b2d7f;
          }

          .tabs-modernos .nav-link.active {
            background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(43, 45, 127, 0.3);
          }

          /* ===== LEYENDA MODERNIZADA ===== */
          .leyenda-contenedor {
            background: white;
            border: 2px solid #e8ebff;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
          }

          .leyenda-titulo {
            font-weight: 600;
            color: #2b2d7f;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
          }

          .leyenda-items {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
          }

          .leyenda-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
          }

          .leyenda-item.peligro {
            background: #dc3545;
            color: white;
          }

          .leyenda-item.advertencia {
            background: #ffc107;
            color: #000;
          }

          .leyenda-item.exitoso {
            background: #28a745;
            color: white;
          }

          /* ===== TABLA MODERNIZADA ===== */
          .tabla-contenedor {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin: 20px 0;
            max-height: 70vh;
            overflow-y: auto;
          }

          .tabla-moderna {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            font-size: 14px;
          }

          .tabla-moderna thead tr {
            background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
            color: white;
          }

          .tabla-moderna th {
            padding: 18px 12px;
            text-align: center;
            font-weight: 600;
            font-size: 13px;
            border: none;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 10;
          }

          .tabla-moderna th i {
            margin-right: 6px;
            opacity: 0.9;
          }

          .tabla-moderna td {
            padding: 14px 12px;
            text-align: center;
            border-bottom: 1px solid #e8ebff;
            vertical-align: middle;
          }

          .tabla-moderna tbody tr {
            transition: all 0.3s ease;
          }

          .tabla-moderna tbody tr:hover {
            background: #f8f9ff;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(43, 45, 127, 0.1);
          }

          .tabla-moderna tbody tr:nth-child(even) {
            background: #fafbff;
          }

          .tabla-moderna tbody tr:nth-child(even):hover {
            background: #f0f2ff;
          }

          /* Columnas específicas */
          .tabla-moderna td:nth-child(2) {
            text-align: left;
            max-width: 300px;
            word-wrap: break-word;
          }

          .tabla-moderna td:nth-child(11) {
            text-align: left;
            max-width: 150px;
            word-wrap: break-word;
          }

          /* ===== PAGINACIÓN MODERNIZADA ===== */
          .contenedor-paginacion {
            display: flex;
            justify-content: center;
            margin: 30px 0;
          }

          .paginacion-moderna {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
          }

          .btn-paginacion {
            padding: 12px 16px;
            background: white;
            border: 2px solid #e8ebff;
            border-radius: 10px;
            color: #2b2d7f;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 44px;
            justify-content: center;
            font-size: 14px;
          }

          .btn-paginacion:hover {
            background: #2b2d7f;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(43, 45, 127, 0.3);
            text-decoration: none;
          }

          .btn-paginacion.activo {
            background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(43, 45, 127, 0.3);
          }

          .btn-paginacion i {
            font-size: 12px;
          }

          /* Ocultar paginación legacy */
          .pagination {
            display: none !important;
          }

          /* Mostrar paginación moderna */
          .contenedor-paginacion {
            display: flex !important;
          }

          /* ===== RESPONSIVE DESIGN ===== */
          @media (max-width: 1200px) {
            .fila-busqueda {
              grid-template-columns: 1fr;
              gap: 15px;
            }

            .botones-busqueda {
              justify-content: center;
            }
          }

          @media (max-width: 768px) {
            .container-fluid {
              padding: 10px;
            }

            .contenido-principal {
              padding: 20px;
            }

            .header-principal {
              margin: -20px -20px 30px -20px;
              padding: 20px;
            }

            .titulo-contenedor {
              flex-direction: column;
              gap: 15px;
            }

            .header-principal h1 {
              font-size: 24px;
            }

            .tabla-moderna {
              font-size: 12px;
            }

            .tabla-moderna th,
            .tabla-moderna td {
              padding: 10px 8px;
            }

            .leyenda-items {
              justify-content: center;
            }

            .botones-superiores .d-flex {
              flex-direction: column;
              gap: 10px;
            }

            .btn-moderno {
              width: 100%;
              justify-content: center;
            }
          }

          @media (max-width: 576px) {
            .contenedor-busqueda {
              padding: 20px;
            }

            .tabla-moderna {
              font-size: 11px;
            }

            .tabla-moderna th,
            .tabla-moderna td {
              padding: 8px 6px;
            }

            .icono-titulo {
              width: 50px;
              height: 50px;
              font-size: 20px;
            }
          }

          /* ===== COMPATIBILIDAD LEGACY ===== */
          .total-row,
          .ultima-existencia {
            background-color: #2b2d7f !important;
            color: white !important;
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

          .contenedor-busqueda,
          .tabla-contenedor {
            animation: fadeInUp 0.6s ease-out 0.1s both;
          }
        </style>
</body>

</html>