<?php
session_start();
ob_start();
include "../../conexionbd.php";
date_default_timezone_set('America/Guatemala');

$usuario = $_SESSION['login'];

if ($usuario['id_rol'] == 7) {
  include "../header_farmaciah.php";
} elseif ($usuario['id_rol'] == 3) {
  include "../../enfermera/header_enfermera.php";
} elseif ($usuario['id_rol'] == 4 || $usuario['id_rol'] == 5) {
  include "../header_farmaciah.php";
} else {
  echo "<script>window.location='../../index.php';</script>";
  exit;
}

if (isset($_SESSION['login']['id_usua'])) {
  $id_usua = $_SESSION['login']['id_usua'];
} else {
  echo "Error: No se pudo obtener el ID del usuario logueado.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['medicamento'], $_POST['motivo'], $_POST['cantidad'])) {
  list($existe_id, $item_id, $existe_lote, $existe_caducidad) = explode('|', $_POST['medicamento']);
  $motivo = $_POST['motivo'];
  $cantidad = $_POST['cantidad'];

  $stmt = $conexion->prepare("SELECT ea.existe_qty, ea.ubicacion_id, ia.item_name, ia.item_costs 
                              FROM existencias_almacenh ea
                              JOIN item_almacen ia ON ia.item_id = ea.item_id
                              WHERE existe_id = ?");
  $stmt->bind_param('i', $existe_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if ($row && $row['existe_qty'] >= $cantidad) {
    $nuevo_stock = $row['existe_qty'] - $cantidad;
    $ubicacion_id = $row['ubicacion_id'];
    $item_name = $row['item_name'];
    $item_costsu = $row['item_costs'];

    // Iniciar transacción
    $conexion->autocommit(FALSE);

    try {
      // Preparar todas las consultas antes de ejecutar
      $stmt_update = $conexion->prepare("UPDATE existencias_almacenh SET existe_qty = ?, existe_salidas = existe_salidas + ? WHERE existe_id = ?");
      if (!$stmt_update) {
        throw new Exception("Error al preparar la consulta de actualización de existencias: " . $conexion->error);
      }

      $fecha_salida = date('Y-m-d H:i:s');
      $id_atencion = NULL;
      $solicita = NULL;
      $fecha_solicitud = NULL;
      $tipo = 'Salida por Ajuste';

      $stmt_insert = $conexion->prepare("INSERT INTO salidas_almacenh 
                                         (item_id, item_name, salida_fecha, salida_lote, salida_caducidad, salida_qty, salida_costsu, id_usua, id_atencion, solicita, fecha_solicitud, tipo, motivo,salio) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,'FARMACIA')");
      if (!$stmt_insert) {
        throw new Exception("Error al preparar la consulta de inserción en salidas_almacenh: " . $conexion->error);
      }

      $stmt_kardex = $conexion->prepare("INSERT INTO kardex_almacenh 
                                         (kardex_fecha, item_id, kardex_lote, kardex_caducidad, kardex_inicial, kardex_entradas, kardex_salidas, kardex_qty, kardex_dev_stock, kardex_dev_merma, kardex_movimiento, kardex_ubicacion, kardex_destino, id_usua, id_surte, motivo) 
                                         VALUES (?, ?, ?, ?, 0, 0, ?, 0, 0, 0, 'Salida por Ajuste', ?, NULL, ?, NULL, ?)");
      if (!$stmt_kardex) {
        throw new Exception("Error al preparar la consulta de inserción en kardex_almacenh: " . $conexion->error);
      }

      // Ejecutar las consultas en orden
      // 1. Insertar en salidas_almacenh
      $stmt_insert->bind_param('issssisisssss', $item_id, $item_name, $fecha_salida, $existe_lote, $existe_caducidad, $cantidad, $item_costsu, $id_usua, $id_atencion, $solicita, $fecha_solicitud, $tipo, $motivo);
      if (!$stmt_insert->execute()) {
        throw new Exception("Error al insertar en salidas_almacenh: " . $stmt_insert->error);
      }

      // 2. Insertar en kardex_almacenh
      $stmt_kardex->bind_param('sissisis', $fecha_salida, $item_id, $existe_lote, $existe_caducidad, $cantidad, $ubicacion_id, $id_usua, $motivo);
      if (!$stmt_kardex->execute()) {
        throw new Exception("Error al insertar en kardex_almacenh: " . $stmt_kardex->error);
      }

      // 3. Actualizar existencias (solo si las inserciones fueron exitosas)
      $stmt_update->bind_param('iii', $nuevo_stock, $cantidad, $existe_id);
      if (!$stmt_update->execute()) {
        throw new Exception("Error al actualizar existencias: " . $stmt_update->error);
      }

      // Si llegamos aquí, todas las operaciones fueron exitosas
      $conexion->commit();
      $conexion->autocommit(TRUE);

      ob_clean();
      header("Location: salidasPorAjusteh.php?success=1");
      exit;
    } catch (Exception $e) {
      // Si hay algún error, hacer rollback
      $conexion->rollback();
      $conexion->autocommit(TRUE);

      // Log del error (opcional)
      error_log("Error en salidasPorAjusteh.php: " . $e->getMessage());

      ob_clean();
      header("Location: salidasPorAjusteh.php?error=1");
      exit;
    }
  } else {
    ob_clean();
    header("Location: salidasPorAjusteh.php?error=1");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Salidas por Ajuste - Farmacia Hospitalaria</title>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  
  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  
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

    .btn-registrar {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

    /* ===== FORMULARIO DE AJUSTE ===== */
    .contenedor-formulario {
      background: white;
      border: 2px solid var(--color-borde);
      border-radius: 15px;
      padding: 25px;
      margin: 30px 0;
      box-shadow: var(--sombra);
    }

    .titulo-formulario {
      color: var(--color-primario);
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-control {
      border: 2px solid var(--color-borde);
      border-radius: 10px;
      padding: 12px 15px;
      transition: all 0.3s ease;
      font-size: 14px;
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

    /* ===== SELECT DE MEDICAMENTOS MEJORADO ===== */
    .select2-medicamento {
      width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
      border: 2px solid var(--color-borde) !important;
      border-radius: 10px !important;
      height: 50px !important;
      padding: 8px 15px !important;
      font-size: 14px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 32px !important;
      color: #495057 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 46px !important;
      right: 10px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
      border-color: var(--color-primario) !important;
      box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1) !important;
    }

    .select2-dropdown {
      border: 2px solid var(--color-primario) !important;
      border-radius: 10px !important;
      margin-top: 5px !important;
      z-index: 9999 !important;
    }

    .select2-results__option {
      padding: 12px 15px !important;
      font-size: 13px !important;
      line-height: 1.4 !important;
      border-bottom: 1px solid #f1f3f4 !important;
    }

    .select2-results__option--highlighted {
      background-color: var(--color-fondo) !important;
      color: var(--color-primario) !important;
    }

    .select2-results__option[aria-selected="true"] {
      background-color: var(--color-primario) !important;
      color: white !important;
    }

    /* ===== INFORMACIÓN DEL MEDICAMENTO SELECCIONADO ===== */
    .info-medicamento-seleccionado {
      margin-top: 15px;
      animation: slideInDown 0.3s ease-out;
    }

    .card-info-medicamento {
      background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
      border: 2px solid var(--color-primario);
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 15px rgba(43, 45, 127, 0.1);
    }

    .card-info-medicamento h6 {
      color: var(--color-primario);
      font-weight: 700;
      margin-bottom: 10px;
      font-size: 16px;
    }

    .card-info-medicamento p {
      color: #495057;
      font-size: 14px;
      margin-bottom: 5px;
    }

    .stock-info {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .stock-disponible {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      padding: 8px 12px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      display: inline-block;
      box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .costo-unitario {
      background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
      color: white;
      padding: 8px 12px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      display: inline-block;
      box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
    }

    /* ===== CONTENEDOR DE HISTORIAL ===== */
    .contenedor-historial {
      background: white;
      border: 2px solid var(--color-borde);
      border-radius: 15px;
      padding: 25px;
      margin: 30px 0;
      box-shadow: var(--sombra);
    }

    .titulo-historial {
      color: var(--color-primario);
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .busqueda-container {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
    }

    .input-busqueda {
      position: relative;
      max-width: 250px;
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
      padding-left: 45px;
    }

    /* ===== TABLA MODERNIZADA ===== */
    .tabla-contenedor {
      background: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: var(--sombra);
      border: 2px solid var(--color-borde);
      max-height: 60vh;
      overflow-y: auto;
    }

    .table-moderna {
      margin: 0;
      font-size: 12px;
      min-width: 100%;
    }

    .table-moderna thead th {
      background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
      color: white;
      border: none;
      padding: 15px 10px;
      font-weight: 600;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 10;
      font-size: 11px;
    }

    .table-moderna thead th i {
      margin-right: 5px;
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
      padding: 10px 8px;
      vertical-align: middle;
      border: none;
      text-align: center;
      white-space: nowrap;
    }

    /* ===== MENSAJE SIN RESULTADOS ===== */
    .mensaje-sin-resultados {
      text-align: center;
      padding: 40px 20px;
      color: var(--color-primario);
      font-size: 16px;
      font-weight: 600;
    }

    .mensaje-sin-resultados i {
      font-size: 48px;
      margin-bottom: 15px;
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

    /* ===== ALERTAS MODERNIZADAS ===== */
    .alerta-exito {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      padding: 15px 20px;
      border-radius: 12px;
      margin: 20px 0;
      box-shadow: var(--sombra);
    }

    .alerta-error {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      color: white;
      padding: 15px 20px;
      border-radius: 12px;
      margin: 20px 0;
      box-shadow: var(--sombra);
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

      .busqueda-container {
        justify-content: center;
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

    .contenedor-formulario,
    .contenedor-historial {
      animation: fadeInUp 0.6s ease-out 0.1s both;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="container-moderno">
      
      <!-- Botón de regreso modernizado -->
      <div class="d-flex justify-content-start mb-4">
        <a href="salidas_almacenh_historial.php" class="btn-moderno btn-regresar">
          <i class="fas fa-arrow-left"></i> Regresar
        </a>
      </div>

      <!-- Mensajes de éxito o error -->
      <?php if (isset($_GET['success'])): ?>
        <div class="alerta-exito">
          <i class="fas fa-check-circle"></i> Ajuste registrado correctamente.
        </div>
      <?php endif; ?>
      
      <?php if (isset($_GET['error'])): ?>
        <div class="alerta-error">
          <i class="fas fa-exclamation-triangle"></i> Error al registrar el ajuste. Verifique los datos e intente nuevamente.
        </div>
      <?php endif; ?>

      <!-- Header principal modernizado -->
      <div class="header-principal">
        <div class="contenido-header">
          <div class="icono-header">
            <i class="fas fa-adjust icono-principal"></i>
          </div>
          <h1>SALIDAS POR AJUSTE - FARMACIA HOSPITALARIA</h1>
        </div>
      </div>

      <!-- Formulario de ajuste modernizado -->
      <div class="contenedor-formulario">
        <div class="titulo-formulario">
          <i class="fas fa-cog"></i> Registro de Salida por Ajuste
        </div>
        
        <form method="POST" action="">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">
                  <i class="fas fa-pills"></i> Medicamento:
                </label>
                <select name="medicamento" id="select-medicamento" class="form-control select2-medicamento" required>
                  <option value="">🔍 Buscar y seleccionar medicamento...</option>
                  <?php
                  $res = $conexion->query("SELECT ea.existe_id, ia.item_id, ia.item_name, ia.item_grams, ea.existe_lote, ea.existe_caducidad, ea.existe_qty, ia.item_costs
                                          FROM existencias_almacenh ea
                                          JOIN item_almacen ia ON ia.item_id = ea.item_id
                                          WHERE ea.existe_qty > 0
                                          ORDER BY ia.item_name, ea.existe_caducidad ASC");
                  while ($row = $res->fetch_assoc()):
                    $value = $row['existe_id'] . "|" . $row['item_id'] . "|" . $row['existe_lote'] . "|" . $row['existe_caducidad'];
                    
                    // Formatear el nombre del medicamento de manera más legible
                    $nombre_medicamento = strtoupper($row['item_name']);
                    $gramos = $row['item_grams'];
                    $lote = $row['existe_lote'];
                    $caducidad = date('d/m/Y', strtotime($row['existe_caducidad']));
                    $stock = $row['existe_qty'];
                    $costo = number_format($row['item_costs'], 2);
                    
                    $label = "💊 {$nombre_medicamento} ({$gramos}) | 🏷️ Lote: {$lote} | 📅 Vence: {$caducidad} | 📦 Stock: {$stock} | 💰 ${$costo}";
                  ?>
                    <option value="<?= htmlspecialchars($value) ?>" 
                            data-qty="<?= $row['existe_qty'] ?>"
                            data-medicamento="<?= htmlspecialchars($nombre_medicamento) ?>"
                            data-gramos="<?= htmlspecialchars($gramos) ?>"
                            data-lote="<?= htmlspecialchars($lote) ?>"
                            data-caducidad="<?= htmlspecialchars($caducidad) ?>"
                            data-costo="<?= $costo ?>">
                      <?= htmlspecialchars($label) ?>
                    </option>
                  <?php endwhile; ?>
                </select>
                
                <!-- Información del medicamento seleccionado -->
                <div id="info-medicamento" class="info-medicamento-seleccionado" style="display: none;">
                  <div class="card-info-medicamento">
                    <div class="row">
                      <div class="col-md-8">
                        <h6><i class="fas fa-pills text-primary"></i> <span id="nombre-seleccionado"></span></h6>
                        <p class="mb-1"><strong>Presentación:</strong> <span id="gramos-seleccionado"></span></p>
                        <p class="mb-0"><strong>Lote:</strong> <span id="lote-seleccionado"></span> | <strong>Vence:</strong> <span id="caducidad-seleccionado"></span></p>
                      </div>
                      <div class="col-md-4 text-right">
                        <div class="stock-info">
                          <span class="stock-disponible">
                            <i class="fas fa-cubes"></i> Stock: <strong id="stock-seleccionado"></strong>
                          </span>
                          <span class="costo-unitario">
                            <i class="fas fa-dollar-sign"></i> $<span id="costo-seleccionado"></span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">
                  <i class="fas fa-comment"></i> Motivo:
                </label>
                <select name="motivo" class="form-control" required>
                  <option value="">Seleccione un motivo</option>
                  <option value="Caducidad">Caducidad</option>
                  <option value="Consumo Interno">Consumo Interno</option>
                  <option value="Ajuste de Inventario">Ajuste de Inventario</option>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">
                  <i class="fas fa-calculator"></i> Cantidad:
                </label>
                <input type="number" name="cantidad" class="form-control" min="1" required>
              </div>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn-moderno btn-registrar">
              <i class="fas fa-save"></i> Registrar Ajuste
            </button>
          </div>
        </form>
      </div>

      <!-- Historial de Ajustes -->
      <div class="contenedor-historial">
        <div class="titulo-historial">
          <i class="fas fa-history"></i> Historial de Ajustes de Medicamentos
        </div>

        <?php
        // Paginación para ajustes
        $registros_por_pagina_ajustes = 20;
        $pagina_ajustes = isset($_GET['pagina_ajustes']) ? (int)$_GET['pagina_ajustes'] : 1;
        if ($pagina_ajustes < 1) {
          $pagina_ajustes = 1;
        }

        // Calcular el número total de registros de ajustes
        $query_total_ajustes = "SELECT COUNT(*) AS total FROM salidas_almacenh WHERE tipo = 'Salida por Ajuste'";
        $resultado_total_ajustes = $conexion->query($query_total_ajustes) or die($conexion->error);
        $total_registros_ajustes = $resultado_total_ajustes->fetch_assoc()['total'];

        // Calcular el total de páginas
        $total_paginas_ajustes = ceil($total_registros_ajustes / $registros_por_pagina_ajustes);

        // Asegurar que la página esté dentro del rango
        if ($pagina_ajustes > $total_paginas_ajustes && $total_paginas_ajustes > 0) {
          $pagina_ajustes = $total_paginas_ajustes;
        }

        // Calcular el desplazamiento (OFFSET)
        $offset_ajustes = ($pagina_ajustes - 1) * $registros_por_pagina_ajustes;
        if ($offset_ajustes < 0) {
          $offset_ajustes = 0;
        }

        // Consulta con LIMIT y OFFSET para obtener los registros paginados
        $query_ajustes = "SELECT * FROM salidas_almacenh WHERE tipo = 'Salida por Ajuste' ORDER BY salida_fecha DESC LIMIT $registros_por_pagina_ajustes OFFSET $offset_ajustes";
        $ajustes = $conexion->query($query_ajustes);
        ?>

        <!-- Filtros modernizados -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-search"></i> Buscar:
              </label>
              <div class="input-busqueda">
                <i class="fas fa-search icono-busqueda"></i>
                <input type="text" id="buscar-ajustes" class="form-control" placeholder="Buscar medicamento o lote...">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex align-items-end justify-content-end">
              <span class="badge badge-info">
                <i class="fas fa-info-circle"></i>
                Total de registros: <?= $total_registros_ajustes ?>
              </span>
            </div>
          </div>
        </div>

        <!-- Tabla modernizada -->
        <div class="tabla-contenedor">
          <table class="table table-moderna" id="tabla-ajustes">
            <thead>
              <tr>
                <th><i class="fas fa-calendar"></i> Fecha</th>
                <th><i class="fas fa-pills"></i> Medicamento</th>
                <th><i class="fas fa-tag"></i> Lote</th>
                <th><i class="fas fa-calendar-check"></i> Caducidad</th>
                <th><i class="fas fa-calculator"></i> Cantidad</th>
                <th><i class="fas fa-comment"></i> Motivo</th>
                <th><i class="fas fa-adjust"></i> Tipo</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($ajustes && $ajustes->num_rows > 0):
                while ($r = $ajustes->fetch_assoc()):
              ?>
                <tr>
                  <td>
                    <span class="badge badge-primary">
                      <?= date('d/m/Y H:i', strtotime($r['salida_fecha'])) ?>
                    </span>
                  </td>
                  <td>
                    <div class="medicamento-info">
                      <strong><?= htmlspecialchars($r['item_name']) ?></strong>
                    </div>
                  </td>
                  <td>
                    <span class="badge badge-secondary"><?= htmlspecialchars($r['salida_lote']) ?></span>
                  </td>
                  <td>
                    <span class="badge badge-warning">
                      <?= date('d/m/Y', strtotime($r['salida_caducidad'])) ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-info">
                      <i class="fas fa-cubes"></i> <?= intval($r['salida_qty']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-dark">
                      <i class="fas fa-<?= 
                        $r['motivo'] == 'Caducidad' ? 'clock' : 
                        ($r['motivo'] == 'Consumo Interno' ? 'utensils' : 'adjust') 
                      ?>"></i>
                      <?= htmlspecialchars($r['motivo']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-success">
                      <i class="fas fa-adjust"></i> <?= htmlspecialchars($r['tipo']) ?>
                    </span>
                  </td>
                </tr>
              <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center">
                    <div class="mensaje-sin-resultados">
                      <i class="fas fa-inbox"></i>
                      <p>No hay registros de ajustes.</p>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Paginación modernizada -->
        <div class="contenedor-paginacion">
          <div class="paginacion-moderna">
            <?php if ($pagina_ajustes > 1): ?>
              <a href="?pagina_ajustes=1" class="btn-paginacion">
                <i class="fas fa-angle-double-left"></i>
              </a>
              <a href="?pagina_ajustes=<?= $pagina_ajustes - 1 ?>" class="btn-paginacion">
                <i class="fas fa-angle-left"></i>
              </a>
            <?php endif; ?>

            <?php
            $pagina_inicio = max(1, $pagina_ajustes - 2);
            $pagina_fin = min($total_paginas_ajustes, $pagina_ajustes + 2);

            for ($i = $pagina_inicio; $i <= $pagina_fin; $i++):
            ?>
              <a href="?pagina_ajustes=<?= $i ?>" class="btn-paginacion <?= $i == $pagina_ajustes ? 'active' : '' ?>">
                <?= $i ?>
              </a>
            <?php endfor; ?>

            <?php if ($pagina_ajustes < $total_paginas_ajustes): ?>
              <a href="?pagina_ajustes=<?= $pagina_ajustes + 1 ?>" class="btn-paginacion">
                <i class="fas fa-angle-right"></i>
              </a>
              <a href="?pagina_ajustes=<?= $total_paginas_ajustes ?>" class="btn-paginacion">
                <i class="fas fa-angle-double-right"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Inicializar Select2 para el selector de medicamentos
      $('#select-medicamento').select2({
        placeholder: '🔍 Buscar y seleccionar medicamento...',
        allowClear: true,
        dropdownAutoWidth: true,
        width: '100%',
        language: {
          noResults: function() {
            return "No se encontraron medicamentos";
          },
          searching: function() {
            return "Buscando medicamentos...";
          },
          loadingMore: function() {
            return "Cargando más resultados...";
          }
        },
        templateResult: function(option) {
          if (!option.id) {
            return option.text;
          }
          
          // Formato personalizado para las opciones
          var $option = $(
            '<div class="select2-result-medicamento">' +
              '<div class="medicamento-nombre">' + option.text.split('|')[0] + '</div>' +
              '<div class="medicamento-detalles">' + option.text.split('|').slice(1).join(' | ') + '</div>' +
            '</div>'
          );
          return $option;
        }
      });

      // Manejar la selección de medicamento
      $('#select-medicamento').on('select2:select', function(e) {
        var selectedOption = $(this).find('option:selected');
        
        if (selectedOption.val()) {
          // Extraer datos del medicamento seleccionado
          var medicamento = selectedOption.data('medicamento');
          var gramos = selectedOption.data('gramos');
          var lote = selectedOption.data('lote');
          var caducidad = selectedOption.data('caducidad');
          var stock = selectedOption.data('qty');
          var costo = selectedOption.data('costo');
          
          // Mostrar información del medicamento
          $('#nombre-seleccionado').text(medicamento);
          $('#gramos-seleccionado').text(gramos);
          $('#lote-seleccionado').text(lote);
          $('#caducidad-seleccionado').text(caducidad);
          $('#stock-seleccionado').text(stock);
          $('#costo-seleccionado').text(costo);
          
          // Configurar el campo de cantidad
          $('input[name="cantidad"]').attr('max', stock);
          $('input[name="cantidad"]').attr('placeholder', `Máximo disponible: ${stock} unidades`);
          
          // Mostrar la información con animación
          $('#info-medicamento').slideDown(300);
          
          // Alerta si el stock es bajo
          if (stock <= 10) {
            Swal.fire({
              icon: 'warning',
              title: 'Stock Bajo',
              text: `Este medicamento tiene stock bajo (${stock} unidades).`,
              timer: 3000,
              showConfirmButton: false,
              toast: true,
              position: 'top-end'
            });
          }
        }
      });

      // Ocultar información cuando se deselecciona
      $('#select-medicamento').on('select2:clear', function(e) {
        $('#info-medicamento').slideUp(300);
        $('input[name="cantidad"]').removeAttr('max').attr('placeholder', 'Ingrese la cantidad');
      });

      // Función de búsqueda en tiempo real mejorada
      $('#buscar-ajustes').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#tabla-ajustes tbody tr').filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });

      // Validación completa del formulario
      $('form').on('submit', function(e) {
        var medicamento = $('#select-medicamento').val();
        var motivo = $('select[name="motivo"]').val();
        var cantidad = $('input[name="cantidad"]').val();

        if (!medicamento || !motivo || !cantidad || cantidad <= 0) {
          e.preventDefault();
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, complete todos los campos correctamente.',
            confirmButtonColor: '#2b2d7f'
          });
          return false;
        }

        // Validar que la cantidad no exceda el stock disponible
        var selectedOption = $('#select-medicamento option:selected');
        var stockDisponible = selectedOption.data('qty');
        
        if (parseInt(cantidad) > parseInt(stockDisponible)) {
          e.preventDefault();
          Swal.fire({
            icon: 'warning',
            title: 'Stock Insuficiente',
            text: `La cantidad no puede exceder el stock disponible (${stockDisponible} unidades).`,
            confirmButtonColor: '#2b2d7f'
          });
          return false;
        }

        // Confirmación con SweetAlert
        e.preventDefault();
        var nombreMedicamento = selectedOption.data('medicamento');
        var lote = selectedOption.data('lote');
        
        Swal.fire({
          title: '¿Confirmar Ajuste?',
          html: `
            <div class="confirmacion-ajuste">
              <p><strong>Medicamento:</strong> ${nombreMedicamento}</p>
              <p><strong>Lote:</strong> ${lote}</p>
              <p><strong>Cantidad:</strong> ${cantidad} unidades</p>
              <p><strong>Motivo:</strong> ${motivo}</p>
            </div>
          `,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#dc3545',
          confirmButtonText: 'Sí, registrar ajuste',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            $(this).off('submit').submit();
          }
        });
      });

      // Animaciones y efectos visuales
      $('.contenedor-formulario, .contenedor-historial').hide().fadeIn(800);
      
      // Hover effects para filas de tabla
      $('#tabla-ajustes tbody tr').hover(
        function() {
          $(this).addClass('table-row-hover');
        },
        function() {
          $(this).removeClass('table-row-hover');
        }
      );

      // Contador de caracteres para campos de texto
      $('#buscar-ajustes').on('input', function() {
        var texto = $(this).val();
        if (texto.length > 0) {
          $(this).addClass('input-con-texto');
        } else {
          $(this).removeClass('input-con-texto');
        }
      });
    });
  </script>

  <!-- SweetAlert2 para alertas modernas -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Estilos adicionales -->
  <style>
    /* ===== ESTILOS PARA SELECT2 PERSONALIZADO ===== */
    .select2-result-medicamento {
      padding: 8px 0;
    }

    .medicamento-nombre {
      font-weight: 600;
      color: var(--color-primario);
      font-size: 14px;
      margin-bottom: 4px;
    }

    .medicamento-detalles {
      font-size: 12px;
      color: #6c757d;
      line-height: 1.2;
    }

    .select2-container--default .select2-results__option .select2-result-medicamento {
      margin: 0;
    }

    .select2-dropdown {
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
      border-radius: 12px !important;
    }

    .select2-search--dropdown .select2-search__field {
      border: 2px solid var(--color-borde) !important;
      border-radius: 8px !important;
      padding: 8px 12px !important;
    }

    .select2-search--dropdown .select2-search__field:focus {
      border-color: var(--color-primario) !important;
      outline: none !important;
    }

    /* ===== ANIMACIONES PARA LA INFORMACIÓN DEL MEDICAMENTO ===== */
    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ===== CONFIRMACIÓN DE AJUSTE ===== */
    .confirmacion-ajuste {
      text-align: left;
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      margin: 10px 0;
    }

    .confirmacion-ajuste p {
      margin: 8px 0;
      font-size: 14px;
    }

    .confirmacion-ajuste strong {
      color: var(--color-primario);
    }

    .table-row-hover {
      background-color: var(--color-fondo) !important;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .badge {
      font-size: 11px;
      padding: 6px 8px;
      border-radius: 8px;
      font-weight: 600;
    }

    .badge-primary {
      background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
    }

    .badge-secondary {
      background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    }

    .badge-warning {
      background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
      color: #212529;
    }

    .badge-info {
      background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    }

    .badge-dark {
      background: linear-gradient(135deg, #343a40 0%, #23272b 100%);
    }

    .badge-success {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .medicamento-info {
      text-align: left;
    }

    .medicamento-info strong {
      color: var(--color-primario);
      font-size: 12px;
      display: block;
    }

    .input-con-texto {
      border-color: var(--color-primario) !important;
      box-shadow: 0 0 0 2px rgba(43, 45, 127, 0.1);
    }

    /* Animación para elementos que aparecen */
    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .badge {
      animation: slideInUp 0.3s ease-out;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .badge {
        font-size: 10px;
        padding: 4px 6px;
      }
      
      .select2-container--default .select2-selection--single {
        height: 45px !important;
      }
      
      .medicamento-nombre {
        font-size: 13px;
      }
      
      .medicamento-detalles {
        font-size: 11px;
      }
      
      .card-info-medicamento {
        padding: 15px;
      }
      
      .stock-info {
        flex-direction: row;
        justify-content: space-between;
      }
      
      .stock-disponible, .costo-unitario {
        font-size: 12px;
        padding: 6px 10px;
      }
    }
  </style>

</body>
</html>