<?php
session_start();
ob_start(); // Iniciar el buffering de salida
include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
  include "../header_farmaciac.php";
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
          ea.ubicacion_id
      FROM item_almacen ia
      INNER JOIN existencias_almacen ea ON ia.item_id = ea.item_id
      INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
      WHERE ea.existe_qty = 0
      AND (ia.item_code LIKE '%$searchTerm%' 
           OR ia.item_name LIKE '%$searchTerm%' 
           OR ea.existe_lote LIKE '%$searchTerm%'
           OR it.item_type_desc LIKE '%$searchTerm%')
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
          ea.ubicacion_id
      FROM item_almacen ia
      INNER JOIN existencias_almacen ea ON ia.item_id = ea.item_id
      INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
      WHERE ea.existe_qty > 0
      AND (ia.item_code LIKE '%$searchTerm%' 
           OR ia.item_name LIKE '%$searchTerm%' 
           OR ea.existe_lote LIKE '%$searchTerm%'
           OR it.item_type_desc LIKE '%$searchTerm%')
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

    $result_ubica = $conexion->query("SELECT * FROM ubicaciones_almacen WHERE ubicacion_id = $id_ubica") or die($conexion->error);
    $ubicacion = 'Sin ubicación';
    while ($row_ubica = $result_ubica->fetch_assoc()) {
      $ubicacion = $row_ubica['nombre_ubicacion'];
    }

    echo '<tr>'
      . '<td>' . $row_search['item_code'] . '</td>'
      . '<td>' . $row_search['item_name'] . ', ' . $row_search['item_grams'] . ', ' . $row_search['item_type_desc'] . '</td>'
      . '<td>' . $row_search['existe_lote'] . '</td>'
      . '<td>' . date_format($caduca, "d/m/Y") . '</td>'
      . '<td>' . $maximo . '</td>'
      . '<td>' . $reordena . '</td>'
      . '<td>' . $minimo . '</td>';

    if ($tab === 'normales') {
      if ($existencias >= $maximo) {
        echo '<td bgcolor="#28a745">' . $existencias . '</td>';
      } elseif ($existencias < $maximo && $existencias > $minimo) {
        echo '<td bgcolor="#ffc107">' . $existencias . '</td>';
      } elseif ($existencias <= $minimo) {
        echo '<td bgcolor="red" style="color: white;">' . $existencias . '</td>';
      }
    } else {
      echo '<td>' . $existencias . '</td>';
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
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

    <!-- Select2 CSS -->
    <link rel="stylesheet" type="text/css" href="css/select2.css">

    <!-- FontAwesome -->
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- Bootstrap 4.5 CSS (usar sólo uno, aquí no incluiste el CSS pero lo ideal sería incluirlo) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">

    <!-- jQuery (usar solo una versión) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <!-- Select2 JS -->
    <script src="js/select2.js"></script>

    <!-- Popper.js necesario para Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>

    <!-- Bootstrap 4.5 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>

    <!-- Tus scripts adicionales -->
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
  <script>
    $(document).ready(function() {
      // Verificar si hay parámetro page_cero en la URL o hash #cero para activar el tab correcto
      const urlParams = new URLSearchParams(window.location.search);
      const hash = window.location.hash;
      
      if (urlParams.has('page_cero') || hash === '#cero') {
        // Activar el tab de existencias en 0
        $('#cero-tab').tab('show');
      }

      // Filtrar datos por búsqueda en toda la base de datos
      $("#search").keyup(function() {
        var searchTerm = $(this).val();
        
        if (searchTerm.length > 2) {
          // Determinar qué tab está activo
          var activeTab = $('.nav-link.active').attr('id');
          var isZeroTab = (activeTab === 'cero-tab');
          
          // Realizar búsqueda AJAX en toda la base de datos
          $.ajax({
            url: window.location.pathname,
            type: 'GET',
            data: {
              'search': searchTerm,
              'ajax': 1,
              'tab': isZeroTab ? 'cero' : 'normales'
            },
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
            }
          });
        } else if (searchTerm.length === 0) {
          // Si no hay término de búsqueda, recargar la página para mostrar todos los resultados
          window.location.reload();
        } else {
          // Para términos de menos de 3 caracteres, usar búsqueda local
          let visibleRows = $("#mytable tbody tr:visible, #mytable-cero tbody tr:visible");
          visibleRows.each(function() {
            let rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(searchTerm.toLowerCase()) === -1)
              $(this).hide();
            else
              $(this).show();
          });
        }
      });

      // Mostrar paginación cuando se cambie de tab
      $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('.pagination').show();
      });
    });
  </script>
</head>

<body>
  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col-12 d-flex justify-content-center">
          <a type="submit" class="btn btn-danger mx-2" href="../../template/menu_farmaciacentral.php">Regresar</a>
          <a type="submit" class="btn btn-warning mx-2" href="existencias_global.php">Existencias Globales</a>
          <a href="excelexistenciasc.php" class="btn btn-success mx-2">Exportar a Excel</a>
        </div>
      </div>
    </div>

   <div class="form-group">
      <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar por código, nombre, lote o tipo...">
    </div>


    <!-- Tabs para separar existencias normales y existencias en 0 -->
    <ul class="nav nav-tabs" id="existenciaTabs" role="tablist">
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

    <div class="tab-content" id="existenciaTabContent">
      <!-- Tab para existencias normales -->
      <div class="tab-pane fade show active" id="normales" role="tabpanel" aria-labelledby="normales-tab">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="mytable">
            <thead class="thead" style="background-color: #2b2d7f; color: white;">
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
                                INNER JOIN existencias_almacen ea ON ia.item_id = ea.item_id
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
                                    ea.ubicacion_id
                                FROM item_almacen ia
                                INNER JOIN existencias_almacen ea ON ia.item_id = ea.item_id
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
                $maximo = $row_normales['item_max'];
                $minimo = $row_normales['item_min'];
                $reordena = $row_normales['reorden'];
                $id_ubica = $row_normales['ubicacion_id'];

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
                  . '<td>' . date_format($caduca, "d/m/Y") . '</td>'
                  . '<td>' . $maximo . '</td>'
                  . '<td>' . $reordena . '</td>'
                  . '<td>' . $minimo . '</td>';

                // Cambiar color de "Existencias" según los criterios especificados
                if ($existencias >= $maximo) {
                  $fila .= '<td bgcolor="#28a745">' . $existencias . '</td>';  // Verde
                } elseif ($existencias < $maximo && $existencias > $minimo) {
                  $fila .= '<td bgcolor="#ffc107">' . $existencias . '</td>';  // Amarillo
                } elseif ($existencias <= $minimo) {
                  $fila .= '<td bgcolor="red" style="color: white;">' . $existencias . '</td>';  // Rojo
                }

                $fila .= '<td>' . $ubicacion . '</td>'
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
            <thead class="thead" style="background-color: #2b2d7f; color: white;">
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
                                INNER JOIN existencias_almacen ea ON ia.item_id = ea.item_id
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
                                INNER JOIN existencias_almacen ea ON ia.item_id = ea.item_id
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
                  . '<td>' . date_format($caduca, "d/m/Y") . '</td>'
                  . '<td>' . $minimo . '</td>'
                  . '<td>' . $maximo . '</td>'
                  . '<td>' . $reordena . '</td>';
                $fila .= '<td>' . $entradas . '</td>';
                $fila .= '<td>' . $salidas . '</td>';
                $fila .= '<td>' . $existencias . '</td>';
                $fila .= '<td>' . $ubicacion . '</td>';
                $fila .= '</tr>';
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
      background-color: #2b2d7f;
      color: white;
    }

    .ultima-existencia {
      background-color: #2b2d7f;
      color: white;
    }

    .table-responsive {
      max-height: 80vh;
      overflow-x: auto;
      overflow-y: auto;
      width: 100%;
    }

    .container.box {
      max-width: 98%;
      width: 98%;
      margin: 0 auto;
    }

    .pagination {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .table {
      font-size: 12px;
      min-width: 100%;
    }

    .table th,
    .table td {
      padding: 4px 6px;
      white-space: nowrap;
      text-align: center;
      vertical-align: middle;
    }

    .table th {
      font-size: 11px;
      font-weight: bold;
    }

    .pagination a {
      padding: 8px 12px;
      text-decoration: none;
      background-color: #2b2d7f;
      color: white;
      border-radius: 5px;
      margin: 0 5px;
    }

    .pagination a:hover {
      background-color: #2b2d7f;
    }

    .pagination .current {
      background-color: #ff7f50;
      color: white;
      font-weight: bold;
    }
  </style>
</body>

</html>