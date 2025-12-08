<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include("../header_configuracion.php");
include("../../conexionbd.php");
?>

<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

  <script src="../../js/jquery-3.3.1.min.js"></script>
  <script src="../../js/jquery-ui.js"></script>
  <script src="../../js/popper.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="../../js/jquery.magnific-popup.min.js"></script>
  <script src="../../js/aos.js"></script>
  <script src="../../js/main.js"></script>

  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

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

    /* Content wrapper */
    .content-wrapper {
      background: transparent !important;
      min-height: 100vh;
    }

    /* Contenedor principal moderno con tema cyberpunk */
    .form-container {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
      border: 2px solid #40E0FF;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                  0 0 30px rgba(64, 224, 255, 0.2);
      margin: 20px auto;
      overflow: hidden;
      max-width: 900px;
      position: relative;
      animation: fadeInUp 0.6s ease-out;
    }

    /* Efecto de brillo en el contenedor */
    .form-container::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
      animation: pulse 3s ease-in-out infinite;
      pointer-events: none;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.5; }
      50% { transform: scale(1.1); opacity: 0.8; }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Header del formulario con estilo cyberpunk */
    .form-header {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
      color: #ffffff;
      padding: 30px;
      text-align: center;
      margin-bottom: 0;
      border-bottom: 2px solid #40E0FF;
      position: relative;
      overflow: hidden;
    }

    .form-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
      animation: pulse 3s ease-in-out infinite;
    }

    .form-header h3 {
      margin: 0;
      font-weight: 700;
      font-size: 26px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-transform: uppercase;
      letter-spacing: 2px;
      text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
      position: relative;
      z-index: 1;
    }

    .form-header i {
      margin-right: 15px;
      font-size: 30px;
      color: #40E0FF;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    /* Cuerpo del formulario */
    .form-body {
      padding: 40px;
      background: transparent;
      position: relative;
      z-index: 1;
    }

    /* Secciones del formulario con tema cyberpunk */
    .form-section {
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.8) 0%, rgba(15, 52, 96, 0.8) 100%);
      border-radius: 16px;
      padding: 25px;
      margin-bottom: 25px;
      border: 2px solid rgba(64, 224, 255, 0.3);
      border-left: 4px solid #40E0FF;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3),
                  0 0 20px rgba(64, 224, 255, 0.1);
      position: relative;
      overflow: hidden;
    }

    .form-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(64, 224, 255, 0.05),
        transparent
      );
      transform: translateX(-100%);
      transition: transform 0.6s ease;
    }

    .form-section:hover::before {
      transform: translateX(100%);
    }

    .form-section:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4),
                  0 0 30px rgba(64, 224, 255, 0.2);
      border-color: #40E0FF;
    }

    .section-title {
      color: #40E0FF;
      font-weight: 700;
      margin-bottom: 20px;
      font-size: 18px;
      display: flex;
      align-items: center;
      padding-bottom: 12px;
      border-bottom: 2px solid rgba(64, 224, 255, 0.3);
      text-transform: uppercase;
      letter-spacing: 1px;
      text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
    }

    .section-title i {
      margin-right: 12px;
      font-size: 20px;
      color: #40E0FF;
      text-shadow: 0 0 10px rgba(64, 224, 255, 0.8);
    }

    /* Labels modernos con tema cyberpunk */
    .form-label-modern {
      color: #ffffff !important;
      font-weight: 600 !important;
      margin-bottom: 10px !important;
      display: flex !important;
      align-items: center !important;
      font-size: 15px !important;
      font-family: 'Roboto', sans-serif !important;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-label-modern i {
      margin-right: 10px !important;
      color: #40E0FF !important;
      width: 20px !important;
      font-size: 16px !important;
      text-align: center !important;
      text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Inputs modernos con estilo cyberpunk */
    .form-control-modern {
      border: 2px solid rgba(64, 224, 255, 0.3) !important;
      border-radius: 10px !important;
      padding: 15px 20px !important;
      font-size: 15px !important;
      transition: all 0.3s ease !important;
      background: linear-gradient(135deg, rgba(15, 52, 96, 0.5) 0%, rgba(22, 33, 62, 0.5) 100%) !important;
      color: #ffffff !important;
      width: 100% !important;
      box-sizing: border-box !important;
      font-family: 'Roboto', sans-serif !important;
      height: 50px !important;
      display: block !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2),
                  inset 0 1px 3px rgba(64, 224, 255, 0.1);
    }

    .form-control-modern:focus {
      border-color: #40E0FF !important;
      box-shadow: 0 0 20px rgba(64, 224, 255, 0.4),
                  inset 0 0 10px rgba(64, 224, 255, 0.1) !important;
      outline: none !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
      transform: translateY(-2px) !important;
      color: #ffffff !important;
    }

    .form-control-modern:hover {
      border-color: #40E0FF !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
    }

    .form-control-modern::placeholder {
      color: rgba(255, 255, 255, 0.5) !important;
      opacity: 1 !important;
    }

    /* Selects modernos con tema cyberpunk */
    .select-modern {
      border: 2px solid rgba(64, 224, 255, 0.3) !important;
      border-radius: 10px !important;
      padding: 15px 20px !important;
      font-size: 15px !important;
      transition: all 0.3s ease !important;
      background: linear-gradient(135deg, rgba(15, 52, 96, 0.5) 0%, rgba(22, 33, 62, 0.5) 100%) !important;
      color: #ffffff !important;
      appearance: none !important;
      -webkit-appearance: none !important;
      -moz-appearance: none !important;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2340E0FF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
      background-position: right 15px center !important;
      background-repeat: no-repeat !important;
      background-size: 20px !important;
      padding-right: 50px !important;
      width: 100% !important;
      box-sizing: border-box !important;
      font-family: 'Roboto', sans-serif !important;
      cursor: pointer !important;
      height: 50px !important;
      display: block !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2),
                  inset 0 1px 3px rgba(64, 224, 255, 0.1);
    }

    .select-modern option {
      background: #16213e;
      color: #ffffff;
      padding: 10px;
    }

    .select-modern:focus {
      border-color: #40E0FF !important;
      box-shadow: 0 0 20px rgba(64, 224, 255, 0.4),
                  inset 0 0 10px rgba(64, 224, 255, 0.1) !important;
      outline: none !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
      transform: translateY(-2px) !important;
      color: #ffffff !important;
    }

    .select-modern:hover {
      border-color: #40E0FF !important;
      background: linear-gradient(135deg, rgba(22, 33, 62, 0.6) 0%, rgba(15, 52, 96, 0.6) 100%) !important;
    }

    /* Grid de campos */
    .form-grid {
      display: grid !important;
      grid-template-columns: 1fr !important;
      gap: 25px !important;
      margin-bottom: 10px !important;
    }

    .form-grid-two {
      display: grid !important;
      grid-template-columns: 1fr 1fr !important;
      gap: 25px !important;
      margin-bottom: 10px !important;
    }

    /* Contenedor de campos individuales */
    .form-grid > div,
    .form-grid-two > div {
      display: flex !important;
      flex-direction: column !important;
    }

    /* Estados de validación con tema cyberpunk */
    .form-control-modern.is-invalid,
    .select-modern.is-invalid {
      border-color: #dc3545 !important;
      background: rgba(220, 53, 69, 0.1) !important;
      box-shadow: 0 0 15px rgba(220, 53, 69, 0.3) !important;
    }

    .form-control-modern.is-invalid:focus,
    .select-modern.is-invalid:focus {
      border-color: #dc3545 !important;
      box-shadow: 0 0 20px rgba(220, 53, 69, 0.5) !important;
    }

    /* Mejoras visuales adicionales */
    .form-control-modern:disabled,
    .select-modern:disabled {
      background: rgba(15, 52, 96, 0.3) !important;
      opacity: 0.6;
      cursor: not-allowed;
      color: rgba(255, 255, 255, 0.5) !important;
    }

    /* Botones modernos con tema cyberpunk */
    .btn-actions {
      text-align: center;
      padding: 30px;
      background: linear-gradient(135deg, rgba(15, 52, 96, 0.5) 0%, rgba(22, 33, 62, 0.5) 100%);
      border-radius: 0 0 18px 18px;
      margin: 0 -40px -40px -40px;
      border-top: 2px solid rgba(64, 224, 255, 0.3);
      position: relative;
      z-index: 1;
    }

    .btn-save-modern {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      border: 2px solid #28a745;
      border-radius: 25px;
      padding: 12px 35px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
      transition: all 0.3s ease;
      margin-right: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .btn-save-modern:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(40, 167, 69, 0.6);
      color: white;
      border-color: #20c997;
    }

    .btn-cancel-modern {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      border: 2px solid #dc3545;
      border-radius: 25px;
      padding: 12px 35px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .btn-cancel-modern:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(220, 53, 69, 0.6);
      color: white;
      text-decoration: none;
      border-color: #c82333;
    }

    /* Alert con tema cyberpunk */
    .alert-success {
      background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(32, 201, 151, 0.2) 100%) !important;
      border: 2px solid #28a745 !important;
      border-radius: 15px !important;
      color: #ffffff !important;
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3) !important;
    }

    .alert-danger {
      background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(200, 35, 51, 0.2) 100%) !important;
      border: 2px solid #dc3545 !important;
      border-radius: 15px !important;
      color: #ffffff !important;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3) !important;
    }

    /* Footer */
    .main-footer {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      border-top: 2px solid #40E0FF !important;
      color: #ffffff !important;
      box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
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

    /* Responsive */
    @media (max-width: 768px) {
      .form-container {
        margin: 10px;
        border-radius: 15px;
      }

      .form-body {
        padding: 25px;
      }

      .form-section {
        padding: 20px;
        margin-bottom: 20px;
      }

      .form-grid-two {
        grid-template-columns: 1fr !important;
      }

      .form-header h3 {
        font-size: 20px;
      }

      .btn-actions {
        padding: 20px;
        margin: 0 -25px -25px -25px;
      }

      .btn-save-modern,
      .btn-cancel-modern {
        margin: 5px;
        width: calc(100% - 10px);
      }
    }

    @media (max-width: 576px) {
      .form-body {
        padding: 20px;
      }

      .form-section {
        padding: 15px;
      }

      .form-header {
        padding: 20px;
      }

      .form-header h3 {
        font-size: 18px;
      }
    }
  </style>

  <script>
    $(document).ready(function() {
      // Validación en tiempo real
      $('#main-form').on('submit', function(e) {
        var valid = true;
        var inputs = $(this).find('input[required], select[required]');

        inputs.each(function() {
          if (!$(this).val()) {
            $(this).addClass('is-invalid');
            valid = false;
          } else {
            $(this).removeClass('is-invalid');
          }
        });

        if (!valid) {
          e.preventDefault();
          showToast('Por favor, complete todos los campos requeridos', 'error');
        }
      });

      // Remover clase de error al escribir
      $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
      });
    });

    // Función para mostrar notificaciones
    function showToast(message, type = 'info') {
      var alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
      var icon = type === 'error' ? 'fa-exclamation-triangle' : 'fa-check';

      var toast = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
          <i class="fa ${icon}"></i> ${message}
          <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
        </div>
      `;

      $('body').append(toast);

      setTimeout(function() {
        $('.alert').fadeOut();
      }, 3000);
    }
  </script>

</head>

<body>
  <section class="content container-fluid">
    <div class="form-container">
      <!-- Header del formulario -->
      <div class="form-header">
        <h3><i class="fas fa-bed"></i>Agregar habitación</h3>
      </div>

      <!-- Cuerpo del formulario -->
      <div class="form-body">
        <form id="main-form" action="" method="post" enctype="multipart/form-data">

          <!-- Sección: Información básica -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-info-circle"></i>
              Información Básica
            </div>
            <div class="form-grid">
              <div>
                <label class="form-label-modern">
                  <i class="fas fa-hashtag"></i>Habitación:
                </label>
                <input id="num_cama"
                       placeholder="Número de habitación"
                       name="num_cama"
                       type="number"
                       min="0"
                       required
                       class="form-control-modern">
              </div>
            </div>
          </div>

          <!-- Sección: Configuración de estado -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-cog"></i>
              Configuración de Estado
            </div>
            <div class="form-grid-two">
              <div>
                <label class="form-label-modern">
                  <i class="fas fa-flag"></i>Estatus:
                </label>
                <select name="estatus" class="select-modern" required>
                  <option value="LIBRE">LIBRE</option>
                  <option value="MANTENIMIENTO">MANTENIMIENTO</option>
                </select>
              </div>
              <div>
                <label class="form-label-modern">
                  <i class="fas fa-hospital"></i>Área:
                </label>
                <select name="tipo" class="select-modern" required>
                  <option value="HOSPITALIZACIÓN">HOSPITALIZACIÓN</option>
                  <option value="OBSERVACIÓN">OBSERVACIÓN</option>
                  <option value="TERAPIA INTENSIVA">TERAPIA INTENSIVA</option>
                  <option value="ENDOSCOPÍA">ENDOSCOPÍA</option>
                  <option value="CONSULTA">CONSULTA</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Sección: Tipo de habitación -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-door-open"></i>
              Tipo de Habitación
            </div>
            <div class="form-grid">
              <div>
                <label class="form-label-modern">
                  <i class="fas fa-bed"></i>Habitación:
                </label>
                <select name="hab" class="select-modern" required>
                  <option value="1">CUARTO ESTÁNDAR</option>
                  <option value="7">ESTANCIA EN OBSERVACIÓN</option>
                  <option value="3">TERAPIA INTENSIVA</option>
                  <option value="980">ENDOSCOPÍA</option>
                  <option value="11">CONSULTA</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Sección: Ubicación -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-map-marker-alt"></i>
              Ubicación
            </div>
            <div class="form-grid-two">
              <div>
                <label class="form-label-modern">
                  <i class="fas fa-layer-group"></i>Piso:
                </label>
                <input id="piso"
                       placeholder="Piso"
                       name="piso"
                       type="number"
                       min="0"
                       required
                       class="form-control-modern">
              </div>
              <div>
                <label class="form-label-modern">
                  <i class="fas fa-th-large"></i>Sección:
                </label>
                <input id="seccion"
                       placeholder="Sección"
                       name="seccion"
                       type="number"
                       min="0"
                       required
                       class="form-control-modern">
              </div>
            </div>
          </div>

          <!-- Botones de acción -->
          <div class="btn-actions">
            <button type="submit" name="add" class="btn btn-save-modern">
              <i class="fas fa-save mr-2"></i>Guardar
            </button>
            <a href="../camas/cat_camas.php" class="btn btn-cancel-modern">
              <i class="fas fa-times mr-2"></i>Cancelar
            </a>
          </div>

        </form>
      </div>
    </div>
        <?php

        if (isset($_POST['add'])) {
          $num_cama    = mysqli_real_escape_string($conexion, (strip_tags($_POST["num_cama"], ENT_QUOTES)));
          $estatus    = mysqli_real_escape_string($conexion, (strip_tags($_POST["estatus"], ENT_QUOTES)));
          $tipo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES)));
          $hab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hab"], ENT_QUOTES)));
          $piso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["piso"], ENT_QUOTES)));
          $seccion    = mysqli_real_escape_string($conexion, (strip_tags($_POST["seccion"], ENT_QUOTES)));

          $sql2 = "INSERT INTO cat_camas(num_cama,estatus,tipo,habitacion,id_atencion,piso,seccion,serv_cve)VALUES($num_cama,'$estatus', '$tipo','$hab',0, '$piso','$seccion','$hab')";
          $result = $conexion->query($sql2);

          echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
          echo '<script type="text/javascript">window.location.href="cat_camas.php";
          </script>';
        }
        ?>
  </section>

  <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
  </footer>

</body>

</html>
