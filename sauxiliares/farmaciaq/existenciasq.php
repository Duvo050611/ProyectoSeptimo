<?php
session_start();
ob_start(); // Iniciar el buffering de salida
include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

// Variables para filtros
$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
$search_term = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';
$lote_filter = isset($_GET['lote']) ? mysqli_real_escape_string($conexion, $_GET['lote']) : '';

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
if (isset($_GET['ajax']) && (isset($_GET['search']) || isset($_GET['lote']) || isset($_GET['item_id']))) {
  $searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conexion, $_GET['search']) : '';
  $loteFilter = isset($_GET['lote']) ? mysqli_real_escape_string($conexion, $_GET['lote']) : '';
  $itemFilter = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : 'normales';

  // Limpiar cualquier salida previa
  ob_clean();

  // Construir WHERE clause dinámicamente
  $whereConditions = [];

  if ($tab === 'cero') {
    $whereConditions[] = "ea.existe_qty = 0";
  } else {
    $whereConditions[] = "ea.existe_qty > 0";
  }

  // Añadir condiciones de filtro
  if (!empty($searchTerm)) {
    $whereConditions[] = "(ia.item_code LIKE '%$searchTerm%' OR ia.item_name LIKE '%$searchTerm%')";
  }

  if (!empty($loteFilter)) {
    $whereConditions[] = "ea.existe_lote LIKE '%$loteFilter%'";
  }

  if ($itemFilter > 0) {
    $whereConditions[] = "ia.item_id = $itemFilter";
  }

  $whereClause = "WHERE " . implode(" AND ", $whereConditions);

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
    INNER JOIN existencias_almacenq ea ON ia.item_id = ea.item_id
    INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
    $whereClause
    ORDER BY ia.item_code ASC" . ($tab === 'normales' ? ", ea.existe_caducidad DESC" : "") . "
  ";

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
?>

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

  /* Estilos específicos para colores de fuente en la columna de caducidad */
  .table tbody td[style*="background-color: #28a745"] {
    color: white !important;
    font-weight: bold;
  }

  .table tbody td[style*="background-color: #ffc107"] {
    color: black !important;
    font-weight: bold;
  }

  .table tbody td[style*="background-color: #dc3545"] {
    color: white !important;
    font-weight: bold;
  }
</style>

<div class="container-fluid">
  <div class="container-main">

    <div class="page-header">
      <h1><i class="fas fa-boxes"></i> EXISTENCIAS QUIROFANO</h1>
    </div>

    <!-- Botones superiores con mismo margen arriba y abajo -->
    <div class="d-flex justify-content-end" style="margin: 20px 0;">
      <div class="d-flex">
        <!-- Botón Regresar -->
        <a href="../../template/menu_farmaciaq.php"
          style="color: white; background: linear-gradient(135deg, #2b2d7f 0%, #1a1c5a 100%);
        border: none; border-radius: 8px; padding: 10px 16px; cursor: pointer; display: inline-block; text-decoration: none;
        box-shadow: 0 2px 8px rgba(43, 45, 127, 0.3); transition: all 0.3s ease; margin-right: 10px;">
          ← Regresar
        </a>

        <!-- Botón Existencias Globales -->
        <a href="existencias_globalq.php" class="btn btn-custom btn-warning-custom mx-2">
          <i class="fas fa-globe"></i> Existencias Globales
        </a>

        <!-- Botón Exportar -->
        <a href="excelexistenciasq.php" class="btn btn-custom btn-success-custom">
          <i class="fas fa-file-excel"></i> Exportar a Excel
        </a>
      </div>
    </div>


    <!-- Formulario de búsqueda -->
    <div class="form-container">
      <div class="row align-items-end">
        <!-- Campo de búsqueda por código/nombre -->
        <div class="col-lg-3 col-md-6 col-sm-12">
          <label class="label-custom"><i class="fas fa-search"></i> Buscar por código/nombre:</label>
          <input type="text" class="form-control" id="search" placeholder="Código o nombre..." value="<?= htmlspecialchars($search_term) ?>">
        </div>

        <!-- Selector de medicamentos/insumos -->
        <div class="col-lg-4 col-md-12 col-sm-12">
          <label class="label-custom"><i class="fas fa-pills"></i> Medicamento/Insumo:</label>
          <select name="item_id" class="form-control" id="mibuscador">
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
        <!-- Campo de búsqueda por lote -->
        <div class="col-lg-2 col-md-6 col-sm-12">
          <label class="label-custom"><i class="fas fa-tag"></i> Lote:</label>
          <input type="text" class="form-control" id="lote" placeholder="Número de lote..." value="<?= htmlspecialchars($lote_filter) ?>">
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

      <!-- Tabs para separar existencias normales y existencias en 0 -->
      <ul class="nav nav-tabs" id="existenciaTabs" role="tablist" style="margin-top: 20px;">
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
      <div style="margin-top: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 10px; background-color: #f8f9fa;">
        <strong><i class="fas fa-info-circle"></i> Leyenda de caducidad:</strong>
        <span style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 5px; margin: 0 5px;"><i class="fas fa-exclamation"></i> ≤ 3 meses</span>
        <span style="background-color: #ffc107; color: black; padding: 5px 10px; border-radius: 5px; margin: 0 5px;"><i class="fas fa-clock"></i> 4-6 meses</span>
        <span style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 5px; margin: 0 5px;"><i class="fas fa-check"></i> > 6 meses</span>
      </div>

    </div>

    <div class="tab-content" id="existenciaTabContent">
      <!-- Tab para existencias normales -->
      <div class="tab-pane fade show active" id="normales" role="tabpanel" aria-labelledby="normales-tab">
        <div class="table-container">
          <table class="table table-bordered table-striped" id="mytable">
            <thead class="thead">
              <tr>
                <th>Código</th>
                <th>Medicamento / Insumo</th>
                <th>Lote</th>
                <th>Caducidad</th>
                <th>Máximo</th>
                <th>P.reorden</th>
                <th>Mínimo</th>
                <th>Entradas</th>
                <th>Salidas</th>
                <th>Existencias</th>
                <th>Ubicación</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Configuración de la paginación para existencias normales
              $records_per_page = 50;

              // Construir WHERE clause para los filtros
              $whereConditions = ["ea.existe_qty > 0"];

              if (!empty($search_term)) {
                $whereConditions[] = "(ia.item_code LIKE '%$search_term%' OR ia.item_name LIKE '%$search_term%')";
              }

              if (!empty($lote_filter)) {
                $whereConditions[] = "ea.existe_lote LIKE '%$lote_filter%'";
              }

              if ($item_id > 0) {
                $whereConditions[] = "ia.item_id = $item_id";
              }

              $whereClause = "WHERE " . implode(" AND ", $whereConditions);

              $query_normales_count = "
                                SELECT COUNT(*) as total
                                FROM item_almacen ia
                                INNER JOIN existencias_almacenq ea ON ia.item_id = ea.item_id
                                INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
                                $whereClause
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
                                INNER JOIN existencias_almacenq ea ON ia.item_id = ea.item_id
                                INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
                                $whereClause
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
          // Construir parámetros para mantener filtros en paginación
          $filter_params = [];
          if (!empty($search_term)) {
            $filter_params[] = "search=" . urlencode($search_term);
          }
          if (!empty($lote_filter)) {
            $filter_params[] = "lote=" . urlencode($lote_filter);
          }
          if ($item_id > 0) {
            $filter_params[] = "item_id=" . $item_id;
          }
          $filter_string = !empty($filter_params) ? "&" . implode("&", $filter_params) : "";

          // Establecer el rango de páginas a mostrar
          $rango = 5;

          // Determinar el inicio y fin del rango de páginas a mostrar
          $inicio = max(1, $page - $rango);
          $fin = min($total_pages, $page + $rango);

          // Mostrar el enlace a la primera página
          if ($page > 1) {
            echo '<a href="?page=1' . $filter_string . '">&laquo; Primero</a>';
            echo '<a href="?page=' . ($page - 1) . $filter_string . '">&lt; Anterior</a>';
          }

          // Mostrar las páginas dentro del rango
          for ($i = $inicio; $i <= $fin; $i++) {
            echo '<a href="?page=' . $i . $filter_string . '" class="' . ($i == $page ? 'current' : '') . '">' . $i . '</a>';
          }

          // Mostrar el enlace a la siguiente página
          if ($page < $total_pages) {
            echo '<a href="?page=' . ($page + 1) . $filter_string . '">Siguiente &gt;</a>';
            echo '<a href="?page=' . $total_pages . $filter_string . '">Último &raquo;</a>';
          }

          ?>
        </div>

      </div>

      <!-- Tab para existencias en 0 -->
      <div class="tab-pane fade" id="cero" role="tabpanel" aria-labelledby="cero-tab">
        <div class="table-container">
          <table class="table table-bordered table-striped" id="mytable-cero">
            <thead class="thead">
              <tr>
                <th>Código</th>
                <th>Medicamento</th>
                <th>Lote</th>
                <th>Caducidad</th>
                <th>Mínimo</th>
                <th>Máximo</th>
                <th>P.reorden</th>
                <th>Entradas</th>
                <th>Salidas</th>
                <th>Existencias</th>
                <th>Ubicación</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Configuración de la paginación para existencias en 0
              // Construir WHERE clause para existencias en 0
              $whereConditionsCero = ["ea.existe_qty = 0"];

              if (!empty($search_term)) {
                $whereConditionsCero[] = "(ia.item_code LIKE '%$search_term%' OR ia.item_name LIKE '%$search_term%')";
              }

              if (!empty($lote_filter)) {
                $whereConditionsCero[] = "ea.existe_lote LIKE '%$lote_filter%'";
              }

              if ($item_id > 0) {
                $whereConditionsCero[] = "ia.item_id = $item_id";
              }

              $whereClauseCero = "WHERE " . implode(" AND ", $whereConditionsCero);

              $query_cero_count = "
                                SELECT COUNT(*) as total
                                FROM item_almacen ia
                                INNER JOIN existencias_almacenq ea ON ia.item_id = ea.item_id
                                INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
                                $whereClauseCero
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
                                INNER JOIN existencias_almacenq ea ON ia.item_id = ea.item_id
                                INNER JOIN item_type it ON ia.item_type_id = it.item_type_id
                                $whereClauseCero
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

                // No aplicar color de fondo en la pestaña de existencias en 0
                $color_caducidad = '';

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
          // Construir parámetros para mantener filtros en paginación (reutilizar la variable ya definida)

          // Establecer el rango de páginas a mostrar
          $rango = 5;

          // Determinar el inicio y fin del rango de páginas a mostrar
          $inicio_cero = max(1, $page_cero - $rango);
          $fin_cero = min($total_pages_cero, $page_cero + $rango);

          // Mostrar el enlace a la primera página
          if ($page_cero > 1) {
            echo '<a href="?page_cero=1' . $filter_string . '#cero">&laquo; Primero</a>';
            echo '<a href="?page_cero=' . ($page_cero - 1) . $filter_string . '#cero">&lt; Anterior</a>';
          }

          // Mostrar las páginas dentro del rango
          for ($i = $inicio_cero; $i <= $fin_cero; $i++) {
            echo '<a href="?page_cero=' . $i . $filter_string . '#cero" class="' . ($i == $page_cero ? 'current' : '') . '">' . $i . '</a>';
          }

          // Mostrar el enlace a la siguiente página
          if ($page_cero < $total_pages_cero) {
            echo '<a href="?page_cero=' . ($page_cero + 1) . $filter_string . '#cero">Siguiente &gt;</a>';
            echo '<a href="?page_cero=' . $total_pages_cero . $filter_string . '#cero">Último &raquo;</a>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {
    // Forzar pestaña "Existencias Normales" siempre al cargar
    $('#normales-tab').tab('show');
    // Inicializar Select2
    $('#mibuscador').select2({
      placeholder: "🔍 Seleccione un medicamento...",
      allowClear: true,
      width: '100%'

    });

    // Verificar si hay parámetro page_cero en la URL o hash #cero para activar el tab correcto
    const urlParams = new URLSearchParams(window.location.search);
    const hash = window.location.hash;

    // Por defecto, forzar que "Existencias Normales" esté activa
    $('#normales-tab').addClass('active');
    $('#cero-tab').removeClass('active');
    $('#normales').addClass('active show');
    $('#cero').removeClass('active show');

    // Solo si hay parámetros específicos, cambiar a existencias en 0
    if (urlParams.has('page_cero') || hash === '#cero') {
      $('#normales-tab').removeClass('active');
      $('#cero-tab').addClass('active');
      $('#normales').removeClass('active show');
      $('#cero').addClass('active show');
    }

    // Mejorar el manejo de cambio de tabs
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
      // Forzar que todas las pestañas estén en la misma posición exacta
      $('.tab-pane').css({
        'position': 'absolute',
        'top': '0',
        'left': '0',
        'right': '0',
        'width': '100%',
        'margin': '0',
        'padding': '10px 0 0 0',
        'display': 'none'
      });

      // Mostrar solo la pestaña activa
      $('.tab-pane.active').css('display', 'block');

      // Asegurar que el contenedor tenga altura mínima
      $('.tab-content').css({
        'position': 'relative',
        'min-height': '500px',
        'margin-top': '0',
        'padding-top': '0'
      });
    });

    // Manejar clics directos en las pestañas
    $('#normales-tab, #cero-tab').on('click', function(e) {
      e.preventDefault();

      // Remover clase activa de todas las pestañas
      $('.nav-link').removeClass('active');
      $('.tab-pane').removeClass('active show');

      // Activar la pestaña clickeada
      $(this).addClass('active');

      // Mostrar el contenido correspondiente
      var target = $(this).attr('href');
      $(target).addClass('active show');

      // Forzar posicionamiento correcto
      setTimeout(function() {
        $('.tab-pane').css({
          'position': 'absolute',
          'top': '0',
          'left': '0',
          'right': '0',
          'width': '100%',
          'margin': '0',
          'padding': '10px 0 0 0',
          'display': 'none'
        });

        $('.tab-pane.active').css('display', 'block');
      }, 10);
    });

    // Aplicar el posicionamiento inmediatamente
    setTimeout(function() {
      $('a[data-toggle="tab"]').trigger('shown.bs.tab');
    }, 100);

    // Función para realizar búsqueda
    function realizarBusqueda() {
      var searchTerm = $("#search").val();
      var loteTerm = $("#lote").val();
      var itemId = $('#mibuscador').val();

      // Verificar que al menos uno de los campos tenga valor
      if (searchTerm.length === 0 && loteTerm.length === 0 && !itemId) {
        alert('Por favor, ingrese un término de búsqueda, número de lote o seleccione un medicamento.');
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
      } else {
        if (searchTerm.length > 0) {
          if (searchTerm.length < 3) {
            alert('El término de búsqueda debe tener al menos 3 caracteres.');
            return;
          }
          ajaxData['search'] = searchTerm;
        }
        if (loteTerm.length > 0) {
          ajaxData['lote'] = loteTerm;
        }
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
      $('#lote').val('');
      $('#mibuscador').val('').trigger('change');
      window.location.reload();
    });

    // Permitir búsqueda con Enter en los campos de texto
    $("#search, #lote").keypress(function(e) {
      if (e.which === 13) { // Enter key
        realizarBusqueda();
      }
    });

    // Limpiar otros campos cuando se usa uno
    $("#search").on('input', function() {
      if ($(this).val().length > 0) {
        $('#lote').val('');
        $('#mibuscador').val('').trigger('change');
      }
    });

    $("#lote").on('input', function() {
      if ($(this).val().length > 0) {
        $('#search').val('');
        $('#mibuscador').val('').trigger('change');
      }
    });

    $('#mibuscador').on('change', function() {
      if ($(this).val()) {
        $('#search').val('');
        $('#lote').val('');
      }
    });

    // Mostrar paginación cuando se cambie de tab
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
      $('.pagination').show();
    });
  });
</script>