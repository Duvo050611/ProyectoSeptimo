<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include("../header_configuracion.php");
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable tbody tr"), function() {
          if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
            $(this).hide();
          else
            $(this).show();
        });
      });
    });
  </script>

  <style>
    /* Estilos modernos para gestión de camas */
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Contenedor principal moderno */
    .camas-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(43, 45, 127, 0.1);
      margin: 20px auto;
      overflow: hidden;
      max-width: 1400px;
      padding: 0;
    }

    /* Botón agregar moderno */
    .btn-add-modern {
      background: linear-gradient(135deg, #2b2d7f 0%, #1a1d5f 100%);
      border: none;
      border-radius: 25px;
      padding: 12px 30px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 4px 15px rgba(43, 45, 127, 0.3);
      transition: all 0.3s ease;
      margin-bottom: 25px;
      width: 100%;
      text-decoration: none;
    }

    .btn-add-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(43, 45, 127, 0.4);
      color: white;
      text-decoration: none;
    }

    /* Buscador moderno */
    .search-container {
      position: relative;
      margin-bottom: 25px;
    }

    .search-input {
      border: 2px solid #e9ecef;
      border-radius: 25px;
      padding: 12px 20px 12px 45px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .search-input:focus {
      border-color: #2b2d7f;
      box-shadow: 0 4px 15px rgba(43, 45, 127, 0.2);
      outline: none;
    }

    .search-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
      font-size: 16px;
    }

    /* Tabla moderna */
    .table-modern {
      margin-bottom: 0;
      border-collapse: separate;
      border-spacing: 0;
      background: white;
    }

    .table-modern thead th {
      background: #2b2d7f;
      color: white;
      font-weight: 600;
      border: none;
      padding: 18px 15px;
      text-align: center;
      font-size: 14px;
      letter-spacing: 0.5px;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .table-modern tbody td {
      padding: 15px;
      vertical-align: middle;
      border-bottom: 1px solid #e9ecef;
      text-align: center;
      font-weight: 500;
    }

    .table-modern tbody tr {
      transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Estados de las camas */
    .status-libre {
      background: #d4edda !important;
      color: #155724;
      border-left: 4px solid #28a745;
    }

    .status-ocupada {
      background: #f8d7da !important;
      color: #721c24;
      border-left: 4px solid #dc3545;
    }

    .status-mantenimiento {
      background: #fff3cd !important;
      color: #856404;
      border-left: 4px solid #ffc107;
    }

    .status-por-liberar {
      background: #ffeaa7 !important;
      color: #8b4513;
      border-left: 4px solid #fd79a8;
    }

    /* Badges para estatus */
    .badge-status {
      font-size: 12px;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 15px;
      text-transform: uppercase;
    }

    .badge-libre {
      background: #28a745;
      color: white;
    }

    .badge-ocupada {
      background: #dc3545;
      color: white;
    }

    .badge-mantenimiento {
      background: #ffc107;
      color: #212529;
    }

    .badge-por-liberar {
      background: #fd7e14;
      color: white;
    }

    /* Botones de acción modernos */
    .btn-action {
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 2px;
      transition: all 0.3s ease;
      font-size: 14px;
    }

    .btn-edit-modern {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
    }

    .btn-edit-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
      color: white;
    }

    .btn-delete-modern {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      color: white;
      box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
    }

    .btn-delete-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
      color: white;
    }

    .status-text {
      font-weight: 600;
      text-transform: uppercase;
      font-size: 12px;
    }

    .text-ocupada {
      color: #dc3545;
    }

    .text-no-disponible {
      color: #ffc107;
    }

    .text-por-liberar {
      color: #fd7e14;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .camas-container {
        margin: 10px;
        border-radius: 10px;
      }
      
      .table-modern thead th {
        padding: 12px 8px;
        font-size: 12px;
      }
      
      .table-modern tbody td {
        padding: 10px 5px;
        font-size: 12px;
      }
      
      .btn-action {
        width: 35px;
        height: 35px;
        font-size: 12px;
      }
    }

    /* Animaciones */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .table-modern tbody tr {
      animation: fadeIn 0.3s ease-in-out;
    }
  </style>

</head>

<body>
  <section class="content container-fluid">
    <div class="camas-container">
      <div class="container-fluid p-4">
        <div class="row">
          <div class="col-12">
            <!-- Botón agregar moderno -->
            <a href="alta_cama.php" class="btn btn-add-modern">
              <i class="fas fa-plus mr-2"></i>Agregar habitación
            </a>

            <!-- Buscador moderno -->
            <div class="search-container">
              <i class="fas fa-search search-icon"></i>
              <input type="text" class="form-control search-input" id="search" placeholder="Buscar habitación, estatus, área...">
            </div>

            <!-- Tabla moderna -->
            <div class="table-responsive">
              <table class="table table-modern" id="mytable">
                <thead>
                  <tr>
                    <th scope="col"><i class="fas fa-bed mr-2"></i>Habitación</th>
                    <th scope="col"><i class="fas fa-info-circle mr-2"></i>Estatus</th>
                    <th scope="col"><i class="fas fa-hospital mr-2"></i>Área</th>
                    <th scope="col"><i class="fas fa-layer-group mr-2"></i>Piso</th>
                    <th scope="col"><i class="fas fa-th-large mr-2"></i>Sección</th>
                    <th scope="col"><i class="fas fa-edit mr-2"></i>Editar</th>
                    <th scope="col"><i class="fas fa-trash mr-2"></i>Eliminar</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT id,estatus,tipo, num_cama,id_atencion,piso,seccion from cat_camas ORDER BY num_cama ASC ";
                    $result = $conexion->query($sql);
                    while ($row = $result->fetch_assoc()) {
                      if($row['id_atencion'] == 0 && $row['estatus'] == 'LIBRE'){
                        $estatus = $row['estatus'];
                        echo '<tr class="status-libre">';
                        echo '<td><strong>' . $row['num_cama'] . '</strong></td>';
                        echo '<td><span class="badge badge-status badge-libre">' . $estatus . '</span></td>';
                        echo '<td>' . $row['tipo'] . '</td>';
                        echo '<td>' . $row['piso'] . '</td>';
                        echo '<td>' . $row['seccion'] . '</td>';
                        echo '<td><a class="btn btn-action btn-edit-modern" href="edita_cama.php?id=' . $row['id'] . '" title="Editar habitación"><i class="fa fa-edit"></i></a></td>';
                        echo '<td><a class="btn btn-action btn-delete-modern" href="elimina_cama.php?id=' . $row['id'] . '" title="Eliminar habitación"><i class="fa fa-trash-alt"></i></a></td>';
                        echo '</tr>';
                      }elseif($row['id_atencion'] != 0 && $row['estatus'] == 'OCUPADA'){
                        $estatus = $row['estatus'];
                        echo '<tr class="status-ocupada">';
                        echo '<td><strong>' . $row['num_cama'] . '</strong></td>';
                        echo '<td><span class="badge badge-status badge-ocupada">' . $estatus . '</span></td>';
                        echo '<td>' . $row['tipo'] . '</td>';
                        echo '<td>' . $row['piso'] . '</td>';
                        echo '<td>' . $row['seccion'] . '</td>';
                        echo '<td><span class="status-text text-ocupada"><i class="fas fa-user-injured mr-1"></i>OCUPADA</span></td>';
                        echo '<td></td>';
                        echo '</tr>';
                      }elseif($row['estatus'] == 'MANTENIMIENTO'){
                        $estatus = "NO DISPONIBLE";
                        echo '<tr class="status-mantenimiento">';
                        echo '<td><strong>' . $row['num_cama'] . '</strong></td>';
                        echo '<td><span class="badge badge-status badge-mantenimiento">' . $estatus . '</span></td>';
                        echo '<td>' . $row['tipo'] . '</td>';
                        echo '<td>' . $row['piso'] . '</td>';
                        echo '<td>' . $row['seccion'] . '</td>';
                        echo '<td><a class="btn btn-action btn-edit-modern" href="edita_cama.php?id=' . $row['id'] . '" title="Editar habitación"><i class="fa fa-edit"></i></a></td>';
                        echo '<td><span class="status-text text-no-disponible"><i class="fas fa-tools mr-1"></i>NO DISPONIBLE</span></td>';
                        echo '</tr>';
                      }else{
                        $estatus = "POR LIBERAR";
                        echo '<tr class="status-por-liberar">';
                        echo '<td><strong>' . $row['num_cama'] . '</strong></td>';
                        echo '<td><span class="badge badge-status badge-por-liberar">' . $estatus . '</span></td>';
                        echo '<td>' . $row['tipo'] . '</td>';
                        echo '<td>' . $row['piso'] . '</td>';
                        echo '<td>' . $row['seccion'] . '</td>';
                        echo '<td><a class="btn btn-action btn-edit-modern" href="edita_cama.php?id=' . $row['id'] . '" title="Editar habitación"><i class="fa fa-edit"></i></a></td>';
                        echo '<td><span class="status-text text-por-liberar"><i class="fas fa-clock mr-1"></i>POR LIBERAR</span></td>';
                        echo '</tr>';
                      }
                    }
                    ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
  </footer>

  <script>
    document.oncontextmenu = function() {
      return false;
    }
  </script>
</body>

</html>