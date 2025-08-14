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
  <!--  Bootstrap  -->
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
  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

  <style>
    /* Estilos modernos para agregar habitación */
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Asegurar que los estilos se apliquen correctamente */
    * {
      box-sizing: border-box;
    }

    /* Override Bootstrap conflictos */
    .form-control, .form-control:focus {
      border: none !important;
      box-shadow: none !important;
    }

    /* Contenedor principal moderno */
    .form-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(43, 45, 127, 0.1);
      margin: 20px auto;
      overflow: hidden;
      max-width: 800px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    /* Header del formulario */
    .form-header {
      background: linear-gradient(135deg, #2b2d7f 0%, #1a1d5f 100%);
      color: white;
      padding: 25px;
      text-align: center;
      margin-bottom: 0;
    }

    .form-header h3 {
      margin: 0;
      font-weight: 600;
      font-size: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-header i {
      margin-right: 12px;
      font-size: 28px;
    }

    /* Cuerpo del formulario */
    .form-body {
      padding: 40px;
      background: white;
    }

    /* Grupos de formulario modernos */
    .form-section {
      background: #f8f9fa;
      border-radius: 16px;
      padding: 25px;
      margin-bottom: 25px;
      border-left: 4px solid #2b2d7f;
      transition: all 0.3s ease;
      border: 1px solid #e9ecef;
    }

    .section-title {
      color: #2b2d7f;
      font-weight: 700;
      margin-bottom: 20px;
      font-size: 18px;
      display: flex;
      align-items: center;
      padding-bottom: 10px;
      border-bottom: 2px solid #e9ecef;
    }

    .section-title i {
      margin-right: 10px;
      font-size: 18px;
    }

    /* Labels modernos */
    .form-label-modern {
      color: #2b2d7f !important;
      font-weight: 600 !important;
      margin-bottom: 10px !important;
      display: flex !important;
      align-items: center !important;
      font-size: 16px !important;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    .form-label-modern i {
      margin-right: 10px !important;
      color: #2b2d7f !important;
      width: 20px !important;
      font-size: 16px !important;
      text-align: center !important;
    }

    /* Inputs modernos */
    .form-control-modern {
      border: 2px solid #e9ecef !important;
      border-radius: 10px !important;
      padding: 15px 20px !important;
      font-size: 16px !important;
      transition: all 0.3s ease !important;
      background: white !important;
      width: 100% !important;
      box-sizing: border-box !important;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
      height: 50px !important;
      display: block !important;
    }

    .form-control-modern:focus {
      border-color: #2b2d7f !important;
      box-shadow: 0 0 0 0.3rem rgba(43, 45, 127, 0.15) !important;
      outline: none !important;
      background: white !important;
      transform: translateY(-1px) !important;
    }

    .form-control-modern:hover {
      border-color: #2b2d7f !important;
      background: #f8f9fa !important;
    }

    .form-control-modern::placeholder {
      color: #6c757d !important;
      opacity: 0.8 !important;
    }

    /* Selects modernos */
    .select-modern {
      border: 1px solid #e9ecef !important;
      border-radius: 10px !important;
      padding: 15px 20px !important;
      font-size: 16px !important;
      transition: all 0.3s ease !important;
      background: white !important;
      appearance: none !important;
      -webkit-appearance: none !important;
      -moz-appearance: none !important;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232b2d7f' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
      background-position: right 15px center !important;
      background-repeat: no-repeat !important;
      background-size: 20px !important;
      padding-right: 50px !important;
      width: 100% !important;
      box-sizing: border-box !important;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
      cursor: pointer !important;
      height: 50px !important;
      display: block !important;
    }

    .select-modern:focus {
      border-color: #2b2d7f !important;
      box-shadow: 0 0 0 0.3rem rgba(43, 45, 127, 0.15) !important;
      outline: none !important;
      background-color: white !important;
      transform: translateY(-1px) !important;
    }

    .select-modern:hover {
      border-color: #2b2d7f !important;
      background-color: #f8f9fa !important;
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

    @media (max-width: 768px) {
      .form-grid-two {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
      }
    }

    /* Contenedor de campos individuales */
    .form-grid > div,
    .form-grid-two > div {
      display: flex !important;
      flex-direction: column !important;
    }

    /* Estados de validación */
    .form-control-modern.is-invalid,
    .select-modern.is-invalid {
      border-color: #dc3545;
      background-color: #fff5f5;
    }

    .form-control-modern.is-invalid:focus,
    .select-modern.is-invalid:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.3rem rgba(220, 53, 69, 0.15);
    }

    /* Mejoras visuales adicionales */
    .form-control-modern:disabled,
    .select-modern:disabled {
      background-color: #e9ecef;
      opacity: 0.6;
      cursor: not-allowed;
    }

    /* Botones modernos */
    .btn-actions {
      text-align: center;
      padding: 30px;
      background: #f8f9fa;
      border-radius: 0 0 15px 15px;
      margin: 0 -40px -40px -40px;
    }

    .btn-save-modern {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      border: none;
      border-radius: 25px;
      padding: 12px 35px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
      transition: all 0.3s ease;
      margin-right: 15px;
    }

    .btn-save-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
      color: white;
    }

    .btn-cancel-modern {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      border: none;
      border-radius: 25px;
      padding: 12px 35px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-cancel-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
      color: white;
      text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .form-container {
        margin: 10px;
        border-radius: 10px;
      }
      
      .form-body {
        padding: 25px;
      }
      
      .form-section {
        padding: 20px;
        margin-bottom: 20px;
      }
      
      .form-grid-two {
        grid-template-columns: 1fr;
      }

      .form-header h3 {
        font-size: 20px;
      }
    }

    /* Efectos adicionales */
    .form-section:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(43, 45, 127, 0.15);
    }

    /* Animaciones */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-container {
      animation: fadeIn 0.5s ease-in-out;
    }
  </style>

  <script>
    // JavaScript para mejorar la experiencia del usuario
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
          $num_cama    = mysqli_real_escape_string($conexion, (strip_tags($_POST["num_cama"], ENT_QUOTES))); //Escanpando caracteres 
          $estatus    = mysqli_real_escape_string($conexion, (strip_tags($_POST["estatus"], ENT_QUOTES))); //Escanpando caracteres 
          $tipo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES))); //Escanpando caracteres 
          $hab    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hab"], ENT_QUOTES))); //Escanpando caracteres 
          $piso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["piso"], ENT_QUOTES))); //Escanpando caracteres 
          $seccion    = mysqli_real_escape_string($conexion, (strip_tags($_POST["seccion"], ENT_QUOTES))); //Escanpando caracteres 

          $sql2 = "INSERT INTO cat_camas(num_cama,estatus,tipo,habitacion,id_atencion,piso,seccion,serv_cve)VALUES($num_cama,'$estatus', '$tipo','$hab',0, '$piso','$seccion','$hab')";
          $result = $conexion->query($sql2);

          echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
          echo '<script type="text/javascript">window.location.href="cat_camas.php";
          </script>';
        }
        ?>
      </div>
    </div>
  </section>
  </div>
  <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
  </footer>


</body>

</html>