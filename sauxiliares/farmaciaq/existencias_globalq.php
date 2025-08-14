<?php
session_start();
ob_start(); // Iniciar el buffering de salida
include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

// Variables para filtros
$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
$search_term = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';

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
      FROM existencias_almacenq ea
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
      FROM existencias_almacenq ea
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
        echo '<td bgcolor="#28a745">' . $existencias . '</td>';
      } elseif ($existencias < $maximo && $existencias > $minimo) {
        echo '<td bgcolor="#ffc107">' . $existencias . '</td>';
      } elseif ($existencias <= $minimo) {
        echo '<td bgcolor="red" style="color: white;">' . $existencias . '</td>';
      }
    } else {
      echo '<td>' . $existencias . '</td>';
    }

    echo '</tr>';
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
      SELECT ia.item_id, ia.item_code, ia.item_name, ia.item_grams, ia.item_max, ia.item_min, ia.reorden, 
             SUM(ea.existe_qty) as cuantos
      FROM existencias_almacenq ea
      INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
      WHERE ia.item_id = $item_id
      GROUP BY ea.item_id
      HAVING cuantos = 0
      ORDER BY ia.item_code ASC
    ";
  } else {
    $query_item = "
      SELECT ia.item_id, ia.item_code, ia.item_name, ia.item_grams, ia.item_max, ia.item_min, ia.reorden, 
             SUM(ea.existe_qty) as cuantos
      FROM existencias_almacenq ea
      INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
      WHERE ia.item_id = $item_id
      GROUP BY ea.item_id
      HAVING cuantos > 0
      ORDER BY ia.item_code ASC
    ";
  }

  $resultado_item = $conexion->query($query_item) or die($conexion->error);

  // Solo generar las filas de la tabla
  while ($row_item = $resultado_item->fetch_assoc()) {
    $existencias = $row_item['cuantos'];
    $maximo = $row_item['item_max'];
    $minimo = $row_item['item_min'];
    $reordena = $row_item['reorden'];

    echo '<tr>'
      . '<td>' . $row_item['item_code'] . '</td>'
      . '<td>' . $row_item['item_name'] . ', ' . $row_item['item_grams'] . '</td>'
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

    echo '</tr>';
  }

  exit; // Importante: terminar la ejecución aquí para no enviar HTML adicional
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <meta charset="UTF-8">

<!-- CSS adicional para Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Estilos específicos de Existencias -->
<style>
  :root {
    --primary-color: #2b2d7f;
    --primary-dark: #1f2166;
    --primary-light: #3f418a;
  }

  .btn-custom {
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin: 5px;
  }

  .btn-danger-custom {
    background: linear-gradient(45deg, #dc3545, #c82333);
    border: none;
    color: white;
  }

  .btn-danger-custom:hover {
    background: linear-gradient(45deg, #c82333, #bd2130);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
    color: white;
  }

  .btn-success-custom {
    background: linear-gradient(45deg, #28a745, #1e7e34);
    border: none;
    color: white;
  }

  .btn-success-custom:hover {
    background: linear-gradient(45deg, #1e7e34, #155724);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
    color: white;
  }

  .btn-warning-custom {
    background: linear-gradient(45deg, #ffc107, #e0a800);
    border: none;
    color: #212529;
  }

  .btn-warning-custom:hover {
    background: linear-gradient(45deg, #e0a800, #d39e00);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 193, 7, 0.3);
    color: #212529;
  }

  .page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
    box-shadow: 0 8px 32px rgba(43, 45, 127, 0.3);
    text-align: center;
  }

  .page-header h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
  }

  .form-container {
    background: white;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 10px 15px;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(43, 45, 127, 0.25);
    border-color: var(--primary-light);
  }

  .table-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    overflow-x: auto;
    overflow-y: auto;
  }

  /* Eliminar cualquier altura fija de las tablas */
  .table {
    margin-bottom: 0;
    width: 100%;
  }

  .table thead th {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    padding: 15px 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 1rem;
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .table tbody tr {
    transition: all 0.3s ease;
  }

  .table tbody tr:hover {
    background-color: rgba(43, 45, 127, 0.1);
  }

  .table tbody td {
    padding: 12px 8px;
    font-size: 1.1rem;
    vertical-align: middle;
    line-height: 1.4;
  }

  /* Eliminar altura fija en tbody */
  .table tbody {
    height: auto;
    min-height: auto;
  }

  /* Forzar comportamiento normal de tabla */
  .table-bordered,
  .table-striped {
    display: table !important;
  }

  .table-bordered tbody,
  .table-striped tbody {
    display: table-row-group !important;
  }

  /* Eliminar cualquier espacio adicional en la pestaña cero */

  .container-main {
    max-width: 98%;
    margin: 0 auto;
  }

  .label-custom {
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 8px;
  }

  .pagination a {
    padding: 8px 12px;
    text-decoration: none;
    background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
    color: white;
    border-radius: 8px;
    margin: 0 5px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .pagination a:hover {
    background: linear-gradient(45deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(43, 45, 127, 0.3);
    color: white;
  }

  .pagination .current {
    background: linear-gradient(45deg, #ffc107, #e0a800);
    color: #212529;
    font-weight: bold;
  }

  /* Tabs responsivos */
  .nav-tabs {
    margin-top: 10px;
    border-bottom: 2px solid var(--primary-color);
  }

  .nav-tabs .nav-link {
    color: var(--primary-color);
    font-weight: bold;
    border-radius: 8px 8px 0 0;
    transition: all 0.3s ease;
  }

  .nav-tabs .nav-link.active {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border-color: var(--primary-color);
  }

  .nav-tabs .nav-link:hover {
    background-color: rgba(43, 45, 127, 0.1);
  }

  /* Mejorar el espaciado de los tab-content */
  .tab-content {
    margin-top: 0 !important;
    padding-top: 0 !important;
  }

  .tab-pane {
    margin-top: 0 !important;
    padding-top: 10px !important;
  }

  /* Eliminar cualquier espacio en las pestañas específicamente */
  #normales,
  #cero {
    margin: 0 !important;
    padding-top: 10px !important;
  }

  /* Forzar posicionamiento absoluto idéntico para ambas pestañas */
  .tab-content {
    position: relative !important;
    margin-top: 0 !important;
    padding-top: 0 !important;
    min-height: 500px;
  }

  .tab-pane {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 10px 0 0 0 !important;
  }

  /* Asegurar que solo la pestaña activa sea visible */
  .tab-pane {
    display: none !important;
  }

  .tab-pane.active {
    display: block !important;
  }

  /* Asegurar que por defecto "Existencias Normales" esté visible */
  #normales {
    display: block !important;
  }

  #cero {
    display: none !important;
  }

  #cero.active {
    display: block !important;
  }

  /* Forzar que las tablas tengan la misma posición */
  .table-container {
    margin-top: 0 !important;
    position: relative !important;
  }

  /* Anular transiciones de Bootstrap que pueden causar problemas */
  .tab-pane {
    transition: none !important;
    animation: none !important;
  }

  /* Asegurar que no haya scroll horizontal en las pestañas */
  .tab-content {
    overflow-x: hidden !important;
  }

  /* Forzar que la paginación esté en la misma posición en ambas pestañas */
  .pagination {
    position: relative !important;
    margin-top: 20px !important;
  }

  /* Remover espacios extra en la estructura */
  .container-fluid {
    padding-left: 10px;
    padding-right: 10px;
  }

  /* Asegurar que no haya espacios extra entre divs */
  .tab-pane .table-container {
    margin-bottom: 20px;
    margin-top: 0;
  }

  /* Centrar la paginación en ambos tabs */
  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    width: 100%;
  }

  .btn-group-form {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .btn-primary {
    background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
    border: none;
    color: white;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .btn-primary:hover {
    background: linear-gradient(45deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(43, 45, 127, 0.3);
    color: white;
  }

  .btn-secondary {
    background: linear-gradient(45deg, #6c757d, #545b62);
    border: none;
    color: white;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .btn-secondary:hover {
    background: linear-gradient(45deg, #545b62, #495057);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
    color: white;
  }

  /* Media Queries para Responsividad */
  @media (max-width: 1200px) {
    .table thead th {
      font-size: 0.9rem;
      padding: 10px 6px;
    }

    .table tbody td {
      font-size: 1rem;
      padding: 10px 6px;
    }
  }

  @media (max-width: 768px) {
    .btn-group-form .btn {
      width: 100% !important;
      margin: 0;
    }

    .table thead th {
      font-size: 0.8rem;
      padding: 8px 4px;
    }

    .table tbody td {
      font-size: 0.9rem;
      padding: 8px 4px;
    }

    .container-main {
      max-width: 100%;
      padding: 0 10px;
    }

    .form-container {
      padding: 15px;
    }
  }

  @media (max-width: 480px) {
    .table thead th {
      font-size: 0.7rem;
      padding: 6px 2px;
    }

    .table tbody td {
      font-size: 0.8rem;
      padding: 6px 2px;
    }

    .page-header h1 {
      font-size: 1.5rem;
    }
  }

  /* Estilos específicos para colores de fuente en la columna de existencias solo en la pestaña normales */
  #normales .table tbody td[bgcolor="#28a745"] {
    color: white !important;
    font-weight: bold;
  }

  #normales .table tbody td[bgcolor="#ffc107"] {
    color: black !important;
    font-weight: bold;
  }

  #normales .table tbody td[bgcolor="red"] {
    color: white !important;
    font-weight: bold;
  }
</style>
  
  <script>
    $(document).ready(function() {
      // Inicializar Select2
      $('#mibuscador').select2({
        placeholder: "🔍 Seleccione un medicamento o insumo",
        allowClear: true,
        width: '100%'
      });

      // Verificar si hay parámetro page_cero en la URL o hash #cero para activar el tab correcto
      const urlParams = new URLSearchParams(window.location.search);
      const hash = window.location.hash;
      
      if (urlParams.has('page_cero') || hash === '#cero') {
        // Activar el tab de existencias en 0
        $('#cero-tab').tab('show');
      }

      // Funciones para manejar el posicionamiento correcto de los tabs
      function setupTabPositioning() {
        $('.tab-pane').each(function() {
          if (!$(this).hasClass('active')) {
            $(this).css({
              'position': 'absolute',
              'top': '0',
              'left': '0',
              'right': '0',
              'opacity': '0',
              'visibility': 'hidden',
              'display': 'none'
            });
          } else {
            $(this).css({
              'position': 'relative',
              'opacity': '1',
              'visibility': 'visible',
              'display': 'block'
            });
          }
        });
      }

      // Manejar el cambio de tabs con posicionamiento correcto
      $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        var target = $(e.target).attr('href');
        var current = $(e.relatedTarget).attr('href');
        
        // Ocultar el tab actual completamente
        if (current) {
          $(current).css({
            'position': 'absolute',
            'top': '0',
            'left': '0',
            'right': '0',
            'opacity': '0',
            'visibility': 'hidden',
            'display': 'none'
          });
        }
      });

      $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr('href');
        
        // Ocultar todos los tabs primero
        $('.tab-pane').css({
          'position': 'absolute',
          'top': '0',
          'left': '0',
          'right': '0',
          'opacity': '0',
          'visibility': 'hidden',
          'display': 'none'
        });
        
        // Mostrar solo el tab activo
        $(target).css({
          'position': 'relative',
          'opacity': '1',
          'visibility': 'visible',
          'display': 'block'
        });
        
        // Mostrar paginación cuando se cambie de tab
        $('.pagination').show();
        
        // Hacer scroll hacia arriba del contenido de la tabla
        $('html, body').animate({
          scrollTop: $('#existenciaTabs').offset().top - 20
        }, 300);
      });

      // Configurar posicionamiento inicial
      setupTabPositioning();

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
    });
  </script>
</head>

<body>
<div class="container-fluid">
  <div class="container-main">
    <div class="page-header">
      <h1><i class="fas fa-globe"></i> EXISTENCIAS GLOBALES DE QUIROFANO</h1>
    </div>

   <!-- Botón superior con mismo margen arriba y abajo -->
<div class="d-flex justify-content-end" style="margin: 20px 0;">
    <div class="d-flex">
        <!-- Botón Regresar -->
        <a href="existenciasq.php"
            style="color: white; background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
            border: none; border-radius: 8px; padding: 10px 16px; cursor: pointer; display: inline-block; 
            text-decoration: none; box-shadow: 0 2px 8px rgba(43, 45, 127, 0.3); 
            transition: all 0.3s ease; margin-right: 10px;">
            ← Regresar
        </a>
    </div>
</div>

    <!-- Botones superiores -->
    <div class="d-flex justify-content-end mb-3">
    </div>

    <!-- Formulario de búsqueda -->
    <div class="form-container">
      <div class="row align-items-end">
        <!-- Campo de búsqueda por código/nombre -->
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label class="label-custom"><i class="fas fa-search"></i> Buscar por código/nombre:</label>
          <input type="text" class="form-control" id="search" placeholder="Código o nombre...">
        </div>

        <!-- Selector de medicamentos/insumos -->
        <div class="col-lg-4 col-md-12 col-sm-12">
          <label class="label-custom"><i class="fas fa-pills"></i> Medicamento/Insumo:</label>
          <select name="item_id" class="form-control" id="mibuscador">
            <option value="">🔍 Seleccione un medicamento o insumo</option>
            <?php
            $sql = "SELECT * FROM item_almacen ORDER BY item_name";
            $result = $conexion->query($sql);
            while ($row_datos = $result->fetch_assoc()) {
              echo "<option value='" . $row_datos['item_id'] . "'>" . $row_datos['item_name'] . ', ' . $row_datos['item_grams'] . "</option>";
            }
            ?>
          </select>
        </div>

        <!-- Botones -->
        <div class="col-lg-2 col-md-12 col-sm-12">
          <div class="btn-group-form">
            <button type="button" class="btn btn-primary" id="btnBuscar">
              <i class="fas fa-search"></i> Buscar
            </button>
            <button type="button" class="btn btn-secondary" id="btnLimpiar">
              <i class="fas fa-eraser"></i> Limpiar
            </button>
          </div>
        </div>
      </div>

      <!-- Tabs para separar existencias normales y existencias globales en 0 -->
      <ul class="nav nav-tabs" id="existenciaTabs" role="tablist" style="margin-top: 20px;">
        <li class="nav-item">
          <a class="nav-link active" id="normales-tab" data-toggle="tab" href="#normales" role="tab" aria-controls="normales" aria-selected="true">
            <i class="fas fa-check-circle"></i> Existencias Globales Normales
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
        <div class="table-container">
          <table class="table table-bordered table-striped" id="mytable">
            <thead class="thead">
              <tr>
                <th>Código</th>
                <th>Medicamento / Insumo</th>
                <th>Máximo</th>
                <th>P.reorden</th>
                <th>Mínimo</th>
                <th>Existencias</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Configuración de la paginación para existencias globales normales
              $records_per_page = 50;
              $query_normales_count = "
                SELECT COUNT(DISTINCT ea.item_id) as total
                FROM existencias_almacenq ea
                INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
                WHERE (SELECT SUM(ea2.existe_qty) FROM existencias_almacenq ea2 WHERE ea2.item_id = ea.item_id) > 0
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
                FROM existencias_almacenq ea
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
                  $fila .= '<td bgcolor="#28a745">' . $existencias . '</td>';  // Verde
                } elseif ($existencias < $maximo && $existencias > $minimo) {
                  $fila .= '<td bgcolor="#ffc107">' . $existencias . '</td>';  // Amarillo
                } elseif ($existencias <= $minimo) {
                  $fila .= '<td bgcolor="red" style="color: white;">' . $existencias . '</td>';  // Rojo
                }

                $fila .= '</tr>';
                echo $fila;
              }
              ?>
            </tbody>
          </table>
        </div>

        <!-- Paginación para existencias globales normales -->
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

      <!-- Tab para existencias globales en 0 -->
      <div class="tab-pane fade" id="cero" role="tabpanel" aria-labelledby="cero-tab">
        <div class="table-container">
          <table class="table table-bordered table-striped" id="mytable-cero">
            <thead class="thead">
              <tr>
                <th>Código</th>
                <th>Medicamento / Insumo</th>
                <th>Máximo</th>
                <th>P.reorden</th>
                <th>Mínimo</th>
                <th>Existencias</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Configuración de la paginación para existencias globales en 0
              $query_cero_count = "
                SELECT COUNT(DISTINCT ea.item_id) as total
                FROM existencias_almacenq ea
                INNER JOIN item_almacen ia ON ea.item_id = ia.item_id
                WHERE (SELECT SUM(ea2.existe_qty) FROM existencias_almacenq ea2 WHERE ea2.item_id = ea.item_id) = 0
              ";
              $result_cero_count = $conexion->query($query_cero_count);
              $total_records_cero = $result_cero_count->fetch_assoc()['total'];
              $total_pages_cero = ceil($total_records_cero / $records_per_page);

              $page_cero = isset($_GET['page_cero']) ? $_GET['page_cero'] : 1;
              $start_from_cero = ($page_cero - 1) * $records_per_page;

              $query_cero = "
                SELECT ia.item_id, ia.item_code, ia.item_name, ia.item_grams, ia.item_max, ia.item_min, ia.reorden, 
                       SUM(ea.existe_qty) as cuantos
                FROM existencias_almacenq ea
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

        <!-- Paginación para existencias globales en 0 -->
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
</div>

<script>
  $(document).ready(function() {
    $('#mibuscador').select2({
      placeholder: "🔍 Seleccione un medicamento o insumo",
      allowClear: true,
      width: '100%'
    });
  });
</script>
</body>

</html>
