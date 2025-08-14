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
    /* Estilos modernos para editar habitación */
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
      background: linear-gradient(135deg, #2b2d7f 0%, #3b3f8f 100%);
      color: white;
      padding: 25px 40px;
      text-align: center;
      margin: 0;
    }

    .form-header h3 {
      margin: 0;
      font-size: 24px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
    }

    .form-header i {
      font-size: 28px;
    }

    /* Cuerpo del formulario */
    .form-body {
      padding: 40px;
      background: white;
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

    .form-control-modern:disabled {
      background-color: #e9ecef !important;
      opacity: 0.7 !important;
      cursor: not-allowed !important;
    }

    /* Selects modernos */
    .select-modern {
      border: 4px solid #e9ecef !important;
      border-radius: 10px !important;
      padding: 15px 20px !important;
      font-size: 16px !important;
      transition: all 0.3s ease !important;
      background: white !important;
      appearance: none !important;
      -webkit-appearance: none !important;
      -moz-appearance: none !important;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232b2d7f' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
      background-position: right 15px center !important;
      background-repeat: no-repeat !important;
      background-size: 22px !important;
      padding-right: 50px !important;
  min-width: 500px !important;
  width: 100% !important;
      box-sizing: border-box !important;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
      cursor: pointer !important;
      height: 55px !important;
      display: block !important;
      font-weight: 600 !important;
    }

    .select-modern:focus {
      border-color: #2b2d7f !important;
      border-width: 4px !important;
      box-shadow: 0 0 0 0.4rem rgba(43, 45, 127, 0.25) !important;
      outline: none !important;
      background-color: white !important;
      transform: translateY(-2px) !important;
    }

    .select-modern:hover {
      border-color: #2b2d7f !important;
      border-width: 4px !important;
      background-color: #f8f9fa !important;
      transform: translateY(-1px) !important;
    }

    /* Form groups modernos */
    .form-group-modern {
      margin-bottom: 25px;
      display: flex;
      flex-direction: column;
    }

    /* Campo de estado actual */
    .current-status {
      background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
      border: 4px solid #2196f3;
      color: #0d47a1;
  padding: 20px 40px;
      border-radius: 12px;
      font-weight: 700;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      box-shadow: 0 6px 20px rgba(33, 150, 243, 0.25);
      transition: all 0.3s ease;
      font-size: 17px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
  min-height: 60px;
  min-width: 500px;
  width: 100%;
    }

    .current-status:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(33, 150, 243, 0.35);
      border-width: 5px;
    }

    .current-status i {
      margin-right: 15px;
      color: #1976d2;
      font-size: 22px;
      background: white;
      padding: 10px;
      border-radius: 50%;
      box-shadow: 0 3px 12px rgba(33, 150, 243, 0.4);
      border: 2px solid #2196f3;
    }

    /* Campo de área actual */
    .area-status {
      background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
      border: 4px solid #4caf50;
      color: #2e7d32;
  padding: 20px 40px;
      border-radius: 12px;
      font-weight: 700;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      box-shadow: 0 6px 20px rgba(76, 175, 80, 0.25);
      transition: all 0.3s ease;
      font-size: 17px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
  min-height: 60px;
  min-width: 500px;
  width: 100%;
    }

    .area-status:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(76, 175, 80, 0.35);
      border-width: 5px;
    }

    .area-status i {
      margin-right: 15px;
      color: #388e3c;
      font-size: 22px;
      background: white;
      padding: 10px;
      border-radius: 50%;
      box-shadow: 0 3px 12px rgba(76, 175, 80, 0.4);
      border: 2px solid #4caf50;
    }

    /* Botones modernos */
    .btn-actions {
      text-align: center;
      padding: 30px 0;
      margin-top: 20px;
    }

    .btn-save-modern {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      border: none;
      color: white;
      padding: 15px 30px;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-right: 15px;
      min-width: 130px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-save-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
      color: white;
      text-decoration: none;
    }

    .btn-cancel-modern {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      border: none;
      color: white;
      padding: 15px 30px;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      min-width: 130px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-cancel-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
      color: white;
      text-decoration: none;
    }

    /* Efectos adicionales */
    .form-container:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(43, 45, 127, 0.15);
    }

    /* Animaciones */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-container {
      animation: fadeIn 0.5s ease-in-out;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .form-container {
        margin: 10px;
        border-radius: 10px;
      }
      
      .form-body {
        padding: 20px;
      }
      
      .btn-actions {
        flex-direction: column;
        gap: 10px;
      }
      
      .btn-save-modern,
      .btn-cancel-modern {
        width: 100%;
        margin: 5px 0;
      }
    }
  </style>

</head>

<body>

  <section class="content container-fluid">
    <div class="form-container">
      <!-- Header del formulario (mantener tal como está) -->
      <div class="form-header">
        <h3><i class="fas fa-edit"></i>EDITAR HABITACIÓN</h3>
      </div>

      <!-- Cuerpo del formulario -->
      <div class="form-body">
        <?php
        $id = $_GET['id'];

        $sql = "SELECT id,estatus,tipo, num_cama,piso,seccion from cat_camas where id = $id";
        $result = $conexion->query($sql);
        while ($row_datos = $result->fetch_assoc()) {
        ?>
          <form class="form-modern" action="" method="post" enctype="multipart/form-data">
            
            <!-- Campo: Habitación -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-hashtag"></i>Habitación:
              </label>
              <input type="text" 
                     name="num_cama" 
                     class="form-control-modern" 
                     value="<?php echo $row_datos['num_cama'] ?>" 
                     disabled>
            </div>

            <!-- Campo: Estatus -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-flag"></i>Estatus:
              </label>
              <div class="current-status">
                <i class="fas fa-info-circle"></i>
                ESTATUS ACTUAL: <?php
                if ($row_datos['estatus'] == "MANTENIMIENTO") {
                  echo 'MANTENIMIENTO';
                } else {
                  echo $row_datos['estatus'];
                }
                ?>
              </div>
              <select name="estatus" class="select-modern" required>
                <option value="<?php echo $row_datos['estatus'] ?>"><?php echo $row_datos['estatus'] ?> (Mantener actual)</option>
                <option value="">--- Cambiar a nuevo estatus ---</option>
                <option value="LIBRE">LIBRE</option>
                <option value="MANTENIMIENTO">MANTENIMIENTO</option>
              </select>
            </div>

            <!-- Campo: Área -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-hospital"></i>Área:
              </label>
              <div class="area-status">
                <i class="fas fa-map-marker-alt"></i>
                ÁREA ACTUAL: <?php echo $row_datos['tipo'] ?>
              </div>
              <select name="tipo" class="select-modern" required> 
                <option value="<?php echo $row_datos['tipo'] ?>"><?php echo $row_datos['tipo'] ?> (Mantener actual)</option>
                <option value="">--- Cambiar a nueva área ---</option>
                <option value="HOSPITALIZACIÓN">HOSPITALIZACIÓN</option>
                <option value="OBSERVACIÓN">OBSERVACIÓN</option>
                <option value="TERAPIA INTENSIVA">TERAPIA INTENSIVA</option>
                <option value="ENDOSCOPÍA">ENDOSCOPÍA</option>
                <option value="CONSULTA">CONSULTA</option>
              </select>
            </div>

            <!-- Campo: Piso -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-layer-group"></i>Piso:
              </label>
              <input type="number" 
                     name="piso" 
                     class="form-control-modern" 
                     id="piso" 
                     value="<?php echo $row_datos['piso'] ?>">
            </div>

            <!-- Campo: Sección -->
            <div class="form-group-modern">
              <label class="form-label-modern">
                <i class="fas fa-th-large"></i>Sección:
              </label>
              <input type="number" 
                     name="seccion" 
                     class="form-control-modern" 
                     id="seccion" 
                     value="<?php echo $row_datos['seccion'] ?>">
            </div>

          <?php
        } ?>

          <!-- Botones de acción -->
          <div class="btn-actions">
            <button type="submit" name="del" class="btn-save-modern">
              <i class="fas fa-save" style="margin-right: 8px;"></i>Guardar
            </button>
            <a href="cat_camas.php" class="btn-cancel-modern">
              <i class="fas fa-times" style="margin-right: 8px;"></i>Cancelar
            </a>
          </div>

          </form>
      </div>
    </div>

    <?php
    if (isset($_POST['del'])) {
      $estatus    = mysqli_real_escape_string($conexion, (strip_tags($_POST["estatus"], ENT_QUOTES))); //Escanpando caracteres 
      $tipo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES))); //Escanpando caracteres 
      $piso   = mysqli_real_escape_string($conexion, (strip_tags($_POST["piso"], ENT_QUOTES))); //Escanpando caracteres 
      $seccion    = mysqli_real_escape_string($conexion, (strip_tags($_POST["seccion"], ENT_QUOTES))); //Escanpando caracteres 
      $sql2 = "UPDATE cat_camas SET estatus = '$estatus', tipo = '$tipo', piso = '$piso', seccion = '$seccion'  WHERE id = $id";
      $result = $conexion->query($sql2);

      echo "<div class='alert alert-success' style='position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;'>
              <i class='fa fa-check'></i> Dato editado correctamente...
            </div>";
      echo '<script type="text/javascript">
              setTimeout(function() {
                window.location.href="cat_camas.php";
              }, 1500);
            </script>';
    }
    ?>

  </section>
  </div>
  <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
  </footer>
</body>

</html>