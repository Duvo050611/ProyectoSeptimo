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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <script>
    $(document).ready(function() {
      $("#search").keyup(function() {
        _this = this;
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

    /* Header personalizado */
    .main-header {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
      border-bottom: 2px solid #40E0FF !important;
      box-shadow: 0 4px 20px rgba(64, 224, 255, 0.2);
    }

    .main-header .logo {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      border-right: 2px solid #40E0FF !important;
      color: #40E0FF !important;
    }

    .main-header .navbar {
      background: transparent !important;
    }

    /* Sidebar personalizado */
    .main-sidebar {
      background: linear-gradient(180deg, #16213e 0%, #0f3460 100%) !important;
      border-right: 2px solid #40E0FF !important;
      box-shadow: 4px 0 20px rgba(64, 224, 255, 0.15);
    }

    .sidebar-menu > li > a {
      color: #ffffff !important;
      border-left: 3px solid transparent;
      transition: all 0.3s ease;
    }

    .sidebar-menu > li > a:hover,
    .sidebar-menu > li.active > a {
      background: rgba(64, 224, 255, 0.1) !important;
      border-left: 3px solid #40E0FF !important;
      color: #40E0FF !important;
    }

    /* Content wrapper */
    .content-wrapper {
      background: transparent !important;
      min-height: 100vh;
    }

    /* Contenedor principal moderno con tema cyberpunk */
    .camas-container {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
      border: 2px solid #40E0FF;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                  0 0 30px rgba(64, 224, 255, 0.2);
      margin: 20px auto;
      overflow: hidden;
      max-width: 1400px;
      padding: 0;
      position: relative;
    }

    /* Efecto de brillo en el contenedor */
    .camas-container::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
      animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.5; }
      50% { transform: scale(1.1); opacity: 0.8; }
    }

    /* Botón agregar moderno con estilo cyberpunk */
    .btn-add-modern {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
      border: 2px solid #40E0FF;
      border-radius: 25px;
      padding: 12px 30px;
      color: #ffffff;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 8px 25px rgba(64, 224, 255, 0.3);
      transition: all 0.3s ease;
      margin-bottom: 25px;
      width: 100%;
      text-decoration: none;
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
      z-index: 1;
      display: inline-block;
    }

    .btn-add-modern:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(64, 224, 255, 0.5);
      color: #40E0FF;
      text-decoration: none;
      border-color: #00D9FF;
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
    }

    /* Buscador moderno con tema cyberpunk */
    .search-container {
      position: relative;
      margin-bottom: 25px;
      z-index: 1;
    }

    .search-input {
      border: 2px solid #40E0FF;
      border-radius: 25px;
      padding: 12px 20px 12px 45px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
      color: #ffffff;
      box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
      width: 100%;
    }

    .search-input::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    .search-input:focus {
      border-color: #00D9FF;
      box-shadow: 0 6px 25px rgba(64, 224, 255, 0.4);
      outline: none;
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%);
      color: #ffffff;
    }

    .search-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #40E0FF;
      font-size: 16px;
      text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Tabla moderna con estilo cyberpunk */
    .table-responsive {
      position: relative;
      z-index: 1;
    }

    .table-modern {
      margin-bottom: 0;
      border-collapse: separate;
      border-spacing: 0;
      background: transparent;
    }

    .table-modern thead th {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
      color: #40E0FF;
      font-weight: 600;
      border: none;
      border-bottom: 2px solid #40E0FF;
      padding: 18px 15px;
      text-align: center;
      font-size: 14px;
      letter-spacing: 1px;
      position: sticky;
      top: 0;
      z-index: 10;
      text-transform: uppercase;
      text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
    }

    .table-modern tbody td {
      padding: 15px;
      vertical-align: middle;
      border-bottom: 1px solid rgba(64, 224, 255, 0.2);
      text-align: center;
      font-weight: 500;
      color: #ffffff;
      background: transparent;
    }

    .table-modern tbody tr {
      transition: all 0.3s ease;
      position: relative;
    }

    .table-modern tbody tr::before {
      content: '';
      position: absolute;
      left: 0;
      width: 4px;
      height: 100%;
      background: transparent;
      transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
      background: rgba(64, 224, 255, 0.1);
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
    }

    .table-modern tbody tr:hover::before {
      background: #40E0FF;
      box-shadow: 0 0 10px rgba(64, 224, 255, 0.8);
    }

    /* Estados de las camas con tema cyberpunk */
    .status-libre {
      background: rgba(40, 167, 69, 0.1) !important;
      border-left: 4px solid #28a745;
    }

    .status-libre:hover {
      background: rgba(40, 167, 69, 0.2) !important;
    }

    .status-ocupada {
      background: rgba(220, 53, 69, 0.1) !important;
      border-left: 4px solid #dc3545;
    }

    .status-ocupada:hover {
      background: rgba(220, 53, 69, 0.2) !important;
    }

    .status-mantenimiento {
      background: rgba(255, 193, 7, 0.1) !important;
      border-left: 4px solid #ffc107;
    }

    .status-mantenimiento:hover {
      background: rgba(255, 193, 7, 0.2) !important;
    }

    .status-por-liberar {
      background: rgba(253, 126, 20, 0.1) !important;
      border-left: 4px solid #fd7e14;
    }

    .status-por-liberar:hover {
      background: rgba(253, 126, 20, 0.2) !important;
    }

    /* Badges para estatus con efecto neón */
    .badge-status {
      font-size: 12px;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 15px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .badge-libre {
      background: #28a745;
      color: white;
      box-shadow: 0 0 15px rgba(40, 167, 69, 0.5);
    }

    .badge-ocupada {
      background: #dc3545;
      color: white;
      box-shadow: 0 0 15px rgba(220, 53, 69, 0.5);
    }

    .badge-mantenimiento {
      background: #ffc107;
      color: #212529;
      box-shadow: 0 0 15px rgba(255, 193, 7, 0.5);
    }

    .badge-por-liberar {
      background: #fd7e14;
      color: white;
      box-shadow: 0 0 15px rgba(253, 126, 20, 0.5);
    }

    /* Botones de acción con estilo cyberpunk */
    .btn-action {
      border: 2px solid;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 2px;
      transition: all 0.3s ease;
      font-size: 14px;
      position: relative;
      overflow: hidden;
    }

    .btn-action::before {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      transform: scale(0);
      transition: transform 0.3s ease;
    }

    .btn-action:hover::before {
      transform: scale(1);
    }

    .btn-edit-modern {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      border-color: #28a745;
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-edit-modern:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 25px rgba(40, 167, 69, 0.5);
      color: white;
      border-color: #20c997;
    }

    .btn-delete-modern {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      color: white;
      border-color: #dc3545;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .btn-delete-modern:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 25px rgba(220, 53, 69, 0.5);
      color: white;
      border-color: #c82333;
    }

    .status-text {
      font-weight: 600;
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 0.5px;
    }

    .text-ocupada {
      color: #dc3545;
      text-shadow: 0 0 10px rgba(220, 53, 69, 0.3);
    }

    .text-no-disponible {
      color: #ffc107;
      text-shadow: 0 0 10px rgba(255, 193, 7, 0.3);
    }

    .text-por-liberar {
      color: #fd7e14;
      text-shadow: 0 0 10px rgba(253, 126, 20, 0.3);
    }

    /* Padding para el contenedor */
    .p-4 {
      position: relative;
      z-index: 1;
    }

    /* Modal */
    .modal-content {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      border: 2px solid #40E0FF !important;
      border-radius: 20px !important;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9),
                  0 0 40px rgba(64, 224, 255, 0.4);
    }

    .modal-header {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
      border-bottom: 2px solid #40E0FF !important;
      border-radius: 20px 20px 0 0 !important;
    }

    .modal-footer {
      border-top: 2px solid #40E0FF !important;
      background: rgba(15, 52, 96, 0.5) !important;
    }

    /* Dropdown menu */
    .dropdown-menu {
      background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
      border: 2px solid #40E0FF !important;
      border-radius: 10px;
    }

    .dropdown-menu > li > a {
      color: #ffffff !important;
    }

    .dropdown-menu > li > a:hover {
      background: rgba(64, 224, 255, 0.1) !important;
      color: #40E0FF !important;
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
    @media (max-width: 1200px) {
      .camas-container {
        margin: 15px;
      }
    }

    @media (max-width: 992px) {
      .table-modern thead th {
        padding: 15px 10px;
        font-size: 13px;
      }

      .table-modern tbody td {
        padding: 12px 8px;
        font-size: 13px;
      }

      .btn-action {
        width: 38px;
        height: 38px;
        font-size: 13px;
      }
    }

    @media (max-width: 768px) {
      .camas-container {
        margin: 10px;
        border-radius: 15px;
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

      .btn-add-modern {
        font-size: 14px;
        padding: 10px 20px;
      }
    }

    @media (max-width: 576px) {
      .table-modern thead th {
        padding: 10px 5px;
        font-size: 11px;
      }

      .table-modern tbody td {
        padding: 8px 3px;
        font-size: 11px;
      }
    }

    /* Animaciones */
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .table-modern tbody tr {
      animation: fadeInUp 0.3s ease-in-out;
    }

    /* Efecto de brillo en hover */
    @keyframes glow {
      0%, 100% {
        box-shadow: 0 0 20px rgba(64, 224, 255, 0.3);
      }
      50% {
        box-shadow: 0 0 40px rgba(64, 224, 255, 0.6);
      }
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    @keyframes ripple {
      0% {
        transform: scale(1);
        opacity: 0.8;
      }
      100% {
        transform: scale(1.3);
        opacity: 0;
      }
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
