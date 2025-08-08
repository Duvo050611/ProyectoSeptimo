<?php
session_start();
ob_start(); // Iniciar el buffering de salida
include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

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
      SELECT ia.item_id, ia.item_code, ia.item_name, ia.item_grams, ia.item_max, ia.item_min, ia.reorden, 
             SUM(ea.existe_qty) as cuantos
      FROM existencias_almacenh ea
      INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
      WHERE (ia.item_code LIKE '%$searchTerm%' 
             OR ia.item_name LIKE '%$searchTerm%')
      GROUP BY ea.item_id
      HAVING cuantos = 0
      ORDER BY ia.item_code ASC
    ";
  } else {
    $query_search = "
      SELECT ia.item_id, ia.item_code, ia.item_name, ia.item_grams, ia.item_max, ia.item_min, ia.reorden, 
             SUM(ea.existe_qty) as cuantos
      FROM existencias_almacenh ea
      INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
      WHERE (ia.item_code LIKE '%$searchTerm%' 
             OR ia.item_name LIKE '%$searchTerm%')
      GROUP BY ea.item_id
      HAVING cuantos > 0
      ORDER BY ia.item_code ASC
    ";
  }

  $resultado_search = $conexion->query($query_search) or die($conexion->error);

  // Solo generar las filas de la tabla
  while ($row_search = $resultado_search->fetch_assoc()) {
    $existencias = $row_search['cuantos'];
    $maximo = $row_search['item_max'];
    $minimo = $row_search['item_min'];
    $reordena = $row_search['reorden'];

    echo '<tr>'
      . '<td>' . $row_search['item_code'] . '</td>'
      . '<td>' . $row_search['item_name'] . ', ' . $row_search['item_grams'] . '</td>'
      . '<td>' . $maximo . '</td>'
      . '<td>' . $reordena . '</td>'
      . '<td>' . $minimo . '</td>';

    if ($tab === 'normales') {
      if ($existencias >= $maximo) {
        echo '<td class="stock-alto">' . $existencias . '</td>';
      } elseif ($existencias < $maximo && $existencias > $minimo) {
        echo '<td class="stock-medio">' . $existencias . '</td>';
      } elseif ($existencias <= $minimo) {
        echo '<td class="stock-bajo">' . $existencias . '</td>';
      }
    } else {
      echo '<td>' . $existencias . '</td>';
    }

    echo '</tr>';
  }

  exit; // Importante: terminar la ejecución aquí para no enviar HTML adicional
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Existencias Globales - Farmacia Hospitalaria</title>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
  
  <style>
    /* ===== VARIABLES CSS ===== */
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

    .container-fluid {
      padding: 0;
      margin: 0;
    }

    .container-moderno {
      background: white;
      border-radius: 20px;
      padding: 30px;
      margin: 20px auto;
      max-width: 1400px;
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

    /* ===== FORMULARIO DE BÚSQUEDA ===== */
    .contenedor-busqueda {
      background: white;
      border: 2px solid var(--color-borde);
      border-radius: 15px;
      padding: 25px;
      margin: 30px 0;
      box-shadow: var(--sombra);
    }

    .input-busqueda {
      position: relative;
      max-width: 400px;
      margin: 0 auto;
    }

    .input-busqueda .icono-busqueda {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--color-primario);
      z-index: 2;
    }

    .input-busqueda input {
      width: 100%;
      padding: 15px 15px 15px 45px;
      border: 2px solid var(--color-borde);
      border-radius: 12px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: white;
    }

    .input-busqueda input:focus {
      outline: none;
      border-color: var(--color-primario);
      box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
      transform: translateY(-1px);
    }

    /* ===== TABS MODERNIZADOS ===== */
    .contenedor-tabs {
      margin: 30px 0;
    }

    .tabs-modernos {
      border: none;
      background: var(--color-fondo);
      border-radius: 12px;
      padding: 8px;
      margin-bottom: 0;
    }

    .tabs-modernos .nav-link {
      border: none;
      border-radius: 8px;
      padding: 15px 25px;
      color: var(--color-primario);
      font-weight: 600;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 0 4px;
    }

    .tabs-modernos .nav-link:hover {
      background: rgba(43, 45, 127, 0.1);
      color: var(--color-primario);
    }

    .tabs-modernos .nav-link.active {
      background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(43, 45, 127, 0.3);
    }

    /* ===== TABLA MODERNIZADA ===== */
    .tabla-contenedor {
      background: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: var(--sombra);
      border: 2px solid var(--color-borde);
    }

    .table-moderna {
      margin: 0;
      font-size: 14px;
    }

    .table-moderna thead th {
      background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
      color: white;
      border: none;
      padding: 18px 15px;
      font-weight: 600;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .table-moderna thead th i {
      margin-right: 8px;
    }

    .table-moderna tbody tr {
      transition: all 0.3s ease;
      border-bottom: 1px solid #f1f3f4;
    }

    .table-moderna tbody tr:hover {
      background-color: var(--color-fondo);
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table-moderna tbody td {
      padding: 15px;
      vertical-align: middle;
      border: none;
    }

    /* ===== INDICADORES DE STOCK ===== */
    .stock-alto {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
      color: white !important;
      font-weight: bold;
      border-radius: 8px;
      padding: 8px 12px !important;
      text-align: center;
      box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .stock-medio {
      background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
      color: #000 !important;
      font-weight: bold;
      border-radius: 8px;
      padding: 8px 12px !important;
      text-align: center;
      box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    .stock-bajo {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
      color: white !important;
      font-weight: bold;
      border-radius: 8px;
      padding: 8px 12px !important;
      text-align: center;
      box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
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
      width: 45px;
      height: 45px;
      border: 2px solid var(--color-borde);
      background: white;
      color: var(--color-primario);
      text-decoration: none;
      border-radius: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
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

    .btn-paginacion.disabled {
      opacity: 0.5;
      cursor: not-allowed;
      pointer-events: none;
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
        font-size: 12px;
      }

      .table-moderna thead th,
      .table-moderna tbody td {
        padding: 10px 8px;
      }

      .contenedor-paginacion {
        margin: 15px 0 5px 0;
      }
    }

    /* ===== ELIMINAR ESPACIOS INNECESARIOS ===== */
    html, body {
      height: auto;
      overflow-x: hidden;
    }
    
    .tab-content {
      margin-bottom: 0;
    }
    
    .tab-pane {
      padding-bottom: 0;
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
                $('#cero .contenedor-paginacion').hide();
              } else {
                $('#mytable tbody').empty().html(cleanResponse);
                // Ocultar paginación durante búsqueda
                $('#normales .contenedor-paginacion').hide();
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
      $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('.contenedor-paginacion').show();
      });
    });
  </script>
</head>

<body>
  <div class="container-fluid">
    <div class="container-moderno">
      
      <!-- Botón de regreso modernizado -->
      <div class="d-flex justify-content-start mb-4">
        <a href="existenciash.php" class="btn-moderno btn-regresar">
          <i class="fas fa-arrow-left"></i> Regresar
        </a>
      </div>

      <!-- Header principal modernizado -->
      <div class="header-principal">
        <div class="contenido-header">
          <div class="icono-header">
            <i class="fas fa-chart-bar icono-principal"></i>
          </div>
          <h1>EXISTENCIAS GLOBALES DE FARMACIA HOSPITALARIA</h1>
        </div>
      </div>

      <!-- Formulario de búsqueda modernizado -->
      <div class="contenedor-busqueda">
        <div class="input-busqueda">
          <i class="fas fa-search icono-busqueda"></i>
          <input type="text" class="form-control" id="search" placeholder="Buscar por código o nombre del medicamento...">
        </div>
      </div>

      <!-- Tabs modernizados -->
      <div class="contenedor-tabs">
        <ul class="nav nav-tabs tabs-modernos" id="existenciaTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="normales-tab" data-toggle="tab" href="#normales" role="tab" aria-controls="normales" aria-selected="true">
              <i class="fas fa-chart-line"></i> Existencias Globales Normales
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="cero-tab" data-toggle="tab" href="#cero" role="tab" aria-controls="cero" aria-selected="false">
              <i class="fas fa-exclamation-triangle"></i> Existencias Globales en 0
            </a>
          </li>
        </ul>
      </div>

      <div class="tab-content" id="existenciaTabContent">
        <!-- Tab para existencias globales normales -->
        <div class="tab-pane fade show active" id="normales" role="tabpanel" aria-labelledby="normales-tab">
          <div class="tabla-contenedor">
            <div class="table-responsive">
              <table class="table table-moderna" id="mytable">
                <thead>
                  <tr>
                    <th><i class="fas fa-barcode"></i> Código</th>
                    <th><i class="fas fa-pills"></i> Medicamento / Insumo</th>
                    <th><i class="fas fa-arrow-up"></i> Máximo</th>
                    <th><i class="fas fa-refresh"></i> P.reorden</th>
                    <th><i class="fas fa-arrow-down"></i> Mínimo</th>
                    <th><i class="fas fa-boxes"></i> Existencias</th>
                  </tr>
                </thead>
                <tbody>
              <?php
              // Configuración de la paginación para existencias globales normales
              $records_per_page = 50;
              $query_normales_count = "
                SELECT COUNT(DISTINCT ea.item_id) as total
                FROM existencias_almacenh ea
                INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
                WHERE (SELECT SUM(ea2.existe_qty) FROM existencias_almacenh ea2 WHERE ea2.item_id = ea.item_id) > 0
              ";
              $result_count = $conexion->query($query_normales_count);
              $total_records = $result_count->fetch_assoc()['total'];
              $total_pages = ceil($total_records / $records_per_page);

              // Obtener la página actual
              $page = isset($_GET['page']) ? $_GET['page'] : 1;
              $start_from = ($page - 1) * $records_per_page;

              // Consulta con limit para obtener solo los registros necesarios
              $query_normales = "
                SELECT ia.item_id, ia.item_code, ia.item_name, ia.item_grams, ia.item_max, ia.item_min, ia.reorden, 
                       SUM(ea.existe_qty) as cuantos
                FROM existencias_almacenh ea
                INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
                GROUP BY ea.item_id
                HAVING cuantos > 0
                ORDER BY ia.item_code ASC
                LIMIT $start_from, $records_per_page
              ";
              $resultado_normales = $conexion->query($query_normales) or die($conexion->error);

              // Mostrar las filas de existencias globales normales
              while ($row_normales = $resultado_normales->fetch_assoc()) {
                $existencias = $row_normales['cuantos'];
                $maximo = $row_normales['item_max'];
                $minimo = $row_normales['item_min'];
                $reordena = $row_normales['reorden'];

                // Mostrar fila
                $fila = '<tr>'
                  . '<td>' . $row_normales['item_code'] . '</td>'
                  . '<td>' . $row_normales['item_name'] . ', ' . $row_normales['item_grams'] . '</td>'
                  . '<td>' . $maximo . '</td>'
                  . '<td>' . $reordena . '</td>'
                  . '<td>' . $minimo . '</td>';

                // Cambiar color de "Existencias" según los criterios especificados
                if ($existencias >= $maximo) {
                  $fila .= '<td class="stock-alto">' . $existencias . '</td>';
                } elseif ($existencias < $maximo && $existencias > $minimo) {
                  $fila .= '<td class="stock-medio">' . $existencias . '</td>';
                } elseif ($existencias <= $minimo) {
                  $fila .= '<td class="stock-bajo">' . $existencias . '</td>';
                }

                $fila .= '</tr>';
                echo $fila;
              }
              ?>
                </tbody>
              </table>
            </div>

            <!-- Paginación modernizada -->
            <div class="contenedor-paginacion">
              <div class="paginacion-moderna">
                <?php
                $rango = 2;
                $inicio = max(1, $page - $rango);
                $fin = min($total_pages, $page + $rango);

                // Enlace a primera página
                if ($page > 1) {
                  echo '<a href="?page=1" class="btn-paginacion"><i class="fas fa-angle-double-left"></i></a>';
                  echo '<a href="?page=' . ($page - 1) . '" class="btn-paginacion"><i class="fas fa-angle-left"></i></a>';
                }

                // Páginas del rango
                for ($i = $inicio; $i <= $fin; $i++) {
                  $active = ($i == $page) ? 'active' : '';
                  echo '<a href="?page=' . $i . '" class="btn-paginacion ' . $active . '">' . $i . '</a>';
                }

                // Enlace a última página
                if ($page < $total_pages) {
                  echo '<a href="?page=' . ($page + 1) . '" class="btn-paginacion"><i class="fas fa-angle-right"></i></a>';
                  echo '<a href="?page=' . $total_pages . '" class="btn-paginacion"><i class="fas fa-angle-double-right"></i></a>';
                }
                ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab para existencias globales en 0 -->
      <div class="tab-pane fade" id="cero" role="tabpanel" aria-labelledby="cero-tab">
        <div class="tabla-contenedor">
          <div class="table-responsive">
            <table class="table table-moderna" id="mytable-cero">
              <thead>
                <tr>
                  <th><i class="fas fa-barcode"></i> Código</th>
                  <th><i class="fas fa-pills"></i> Medicamento / Insumo</th>
                  <th><i class="fas fa-arrow-up"></i> Máximo</th>
                  <th><i class="fas fa-refresh"></i> P.reorden</th>
                  <th><i class="fas fa-arrow-down"></i> Mínimo</th>
                  <th><i class="fas fa-exclamation-circle"></i> Existencias</th>
                </tr>
              </thead>
              <tbody>
              <?php
              // Configuración de la paginación para existencias globales en 0
              $query_cero_count = "
                SELECT COUNT(DISTINCT ea.item_id) as total
                FROM existencias_almacenh ea
                INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
                WHERE (SELECT SUM(ea2.existe_qty) FROM existencias_almacenh ea2 WHERE ea2.item_id = ea.item_id) = 0
              ";
              $result_cero_count = $conexion->query($query_cero_count);
              $total_records_cero = $result_cero_count->fetch_assoc()['total'];
              $total_pages_cero = ceil($total_records_cero / $records_per_page);

              $page_cero = isset($_GET['page_cero']) ? $_GET['page_cero'] : 1;
              $start_from_cero = ($page_cero - 1) * $records_per_page;

              $query_cero = "
                SELECT ia.item_id, ia.item_code, ia.item_name, ia.item_grams, ia.item_max, ia.item_min, ia.reorden, 
                       SUM(ea.existe_qty) as cuantos
                FROM existencias_almacenh ea
                INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
                GROUP BY ea.item_id
                HAVING cuantos = 0
                ORDER BY ia.item_code ASC
                LIMIT $start_from_cero, $records_per_page
              ";
              $resultado_cero = $conexion->query($query_cero) or die($conexion->error);

              // Mostrar filas de existencias globales en 0
              while ($row_cero = $resultado_cero->fetch_assoc()) {
                $existencias = $row_cero['cuantos'];
                $maximo = $row_cero['item_max'];
                $minimo = $row_cero['item_min'];
                $reordena = $row_cero['reorden'];

                // Mostrar fila
                $fila = '<tr>'
                  . '<td>' . $row_cero['item_code'] . '</td>'
                  . '<td>' . $row_cero['item_name'] . ', ' . $row_cero['item_grams'] . '</td>'
                  . '<td>' . $maximo . '</td>'
                  . '<td>' . $reordena . '</td>'
                  . '<td>' . $minimo . '</td>';

                $fila .= '<td>' . $existencias . '</td>'
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
              $rango = 2;
              $inicio_cero = max(1, $page_cero - $rango);
              $fin_cero = min($total_pages_cero, $page_cero + $rango);

              // Enlace a primera página
              if ($page_cero > 1) {
                echo '<a href="?page_cero=1#cero" class="btn-paginacion"><i class="fas fa-angle-double-left"></i></a>';
                echo '<a href="?page_cero=' . ($page_cero - 1) . '#cero" class="btn-paginacion"><i class="fas fa-angle-left"></i></a>';
              }

              // Páginas del rango
              for ($i = $inicio_cero; $i <= $fin_cero; $i++) {
                $active = ($i == $page_cero) ? 'active' : '';
                echo '<a href="?page_cero=' . $i . '#cero" class="btn-paginacion ' . $active . '">' . $i . '</a>';
              }

              // Enlace a última página
              if ($page_cero < $total_pages_cero) {
                echo '<a href="?page_cero=' . ($page_cero + 1) . '#cero" class="btn-paginacion"><i class="fas fa-angle-right"></i></a>';
                echo '<a href="?page_cero=' . $total_pages_cero . '#cero" class="btn-paginacion"><i class="fas fa-angle-double-right"></i></a>';
              }
              ?>
            </div>
          </div>
        </div>
      </div>

    </div> <!-- Cerrar tab-content -->
    </div> <!-- Cerrar container-moderno -->
  </div> <!-- Cerrar container-fluid -->
</body>

</html>