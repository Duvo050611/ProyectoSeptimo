<?php
session_start();
include "../../conexionbd.php";
include "../header_enfermera.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);

?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <!--  Bootstrap  -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <!-- FontAwesome 6 para iconos más modernos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.magnific-popup.min.js"></script>
    <script src="../js/aos.js"></script>
    <script src="../js/main.js"></script>


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
  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search_dep").keyup(function() {
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
  <title>SIGNOS VITALES</title>
  <style type="text/css">
    #contenido{
        display: none;
    }
     #contenido3{
        display: none;
    }
     #contenido4{
        display: none;
    }
    
    /* Estilos mejorados para la gráfica */
    .chart-container {
        position: relative;
        height: 600px;
        width: 100%;
        margin: 20px 0;
        padding: 25px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        border: 2px solid #e9ecef;
        overflow: hidden;
    }
    
    .chart-header {
        background: linear-gradient(135deg, #2b2d7f 0%, #4a4ed1 100%);
        color: white;
        padding: 20px;
        border-radius: 12px 12px 0 0;
        margin: -25px -25px 25px -25px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(43, 45, 127, 0.4);
        position: relative;
    }
    
    .chart-header::before {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 15px solid transparent;
        border-right: 15px solid transparent;
        border-top: 10px solid #2b2d7f;
    }
    
    .chart-legend {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        padding: 15px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        border: 1px solid #dee2e6;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 15px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        border: 2px solid #dee2e6;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        user-select: none;
    }
    
    .legend-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        background: #f8f9fa;
    }
    
    .legend-item.inactive {
        opacity: 0.4;
        background: #f1f1f1;
    }
    
    .legend-item.inactive .legend-color {
        opacity: 0.3;
    }
    
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    
    .chart-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
        padding: 0 10px;
    }
    
    .stat-card {
        background: #f8f9fa;
        padding: 20px 15px;
        border-radius: 8px;
        border-left: 4px solid #2b2d7f;
        text-align: center;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .stat-title {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 5px;
        text-transform: uppercase;
        font-weight: bold;
    }
    
    .stat-value {
        font-size: 20px;
        font-weight: bold;
        color: #2b2d7f;
    }
    
    .stat-unit {
        font-size: 12px;
        color: #6c757d;
    }
    
    @media (max-width: 768px) {
        .chart-container {
            height: 400px;
            padding: 15px;
        }
        
        .chart-legend {
            gap: 10px;
        }
        
        .legend-item {
            font-size: 11px;
            padding: 3px 8px;
        }
        
        .chart-stats {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }
    }
    
    /* Estilos para los botones de navegación mejorados */
    .navigation-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .navigation-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    }
    
    .btn-group-custom {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-regresar {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        min-width: 180px;
        justify-content: center;
    }
    
    .btn-regresar:hover {
        background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }
    
    .btn-secundario {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        min-width: 180px;
        justify-content: center;
    }
    
    .btn-secundario:hover {
        background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
    }
    
    .btn-regresar i, .btn-secundario i {
        font-size: 16px;
    }
    
    /* Estilos para las tarjetas de estadísticas */
    .card {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        background: white;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .border-left-secondary {
        border-left: 0.25rem solid #858796 !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-dark {
        border-left: 0.25rem solid #5a5c69 !important;
    }
    
    .text-primary {
        color: #4e73df !important;
    }
    
    .text-warning {
        color: #f6c23e !important;
    }
    
    .text-info {
        color: #36b9cc !important;
    }
    
    .text-secondary {
        color: #858796 !important;
    }
    
    .text-success {
        color: #1cc88a !important;
    }
    
    .text-dark {
        color: #5a5c69 !important;
    }
    
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    
    .text-xs {
        font-size: 0.75rem;
    }
    
    .shadow {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }
    
    .h5 {
        font-size: 1.25rem;
    }
    
    .font-weight-bold {
        font-weight: 700 !important;
    }
    
    .text-uppercase {
        text-transform: uppercase !important;
    }
    
    .mb-1 {
        margin-bottom: 0.25rem !important;
    }
    
    .mb-0 {
        margin-bottom: 0 !important;
    }
    
    .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    
    .no-gutters {
        margin-right: 0;
        margin-left: 0;
    }
    
    .no-gutters > .col,
    .no-gutters > [class*="col-"] {
        padding-right: 0;
        padding-left: 0;
    }
    
    @media (max-width: 576px) {
        .btn-group-custom {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-regresar, .btn-secundario {
            width: 100%;
            max-width: 250px;
        }
    }
    
    /* Estilos para impresión/PDF */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .btn {
            display: none !important;
        }
        
        body {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .thead, .chart-header {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
            background-color: #2b2d7f !important;
            color: white !important;
        }
        
        .stat-card {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
            background-color: #f8f9fa !important;
            border-left: 4px solid #2b2d7f !important;
        }
        
        .chart-legend {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
            background-color: #f8f9fa !important;
        }
        
        .legend-item {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
            background-color: white !important;
            border: 1px solid #dee2e6 !important;
        }
        
        .chart-container {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
            page-break-inside: avoid;
        }
        
        .container {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        @page {
            margin: 1cm;
            size: A4;
        }
        
        /* Asegurar que los colores de la leyenda se vean */
        .legend-color {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Mejorar contraste de texto */
        .stat-value {
            color: #2b2d7f !important;
            font-weight: bold !important;
        }
        
        .stat-title {
            color: #000 !important;
            font-weight: bold !important;
        }
        
        /* Asegurar que el fondo de las tarjetas estadísticas se vea */
        .chart-stats .stat-card {
            border: 1px solid #ddd !important;
            background-color: #f8f9fa !important;
        }
        
        /* Estilos para las nuevas tarjetas de estadísticas en impresión */
        .card {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
            border: 1px solid #e3e6f0 !important;
            background: white !important;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-secondary {
            border-left: 0.25rem solid #858796 !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-dark {
            border-left: 0.25rem solid #5a5c69 !important;
        }
        
        .text-primary {
            color: #4e73df !important;
        }
        
        .text-warning {
            color: #f6c23e !important;
        }
        
        .text-info {
            color: #36b9cc !important;
        }
        
        .text-secondary {
            color: #858796 !important;
        }
        
        .text-success {
            color: #1cc88a !important;
        }
        
        .text-dark {
            color: #5a5c69 !important;
        }
        
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        
        /* Estilos específicos para las tarjetas de estadísticas en PDF - Formato 3x2 */
        .container.mt-4 .row:first-child .col-lg-4 {
            width: 33.333333% !important;
            float: left !important;
            margin-bottom: 15px !important;
        }
        
        .container.mt-4 .row.justify-content-center .col-lg-4 {
            width: 33.333333% !important;
            float: left !important;
            margin-bottom: 15px !important;
        }
        
        .container.mt-4::after {
            content: "";
            display: table;
            clear: both;
        }
        
        #grafica {
            max-height: 200px !important;
        }
    }
</style>
  
</head>

<body>
 
    <?php

    include "../../conexionbd.php";

    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];

      $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

      $result_pac = $conexion->query($sql_pac);

      while ($row_pac = $result_pac->fetch_assoc()) {
        $pac_papell = $row_pac['papell'];
        $pac_sapell = $row_pac['sapell'];
        $pac_nom_pac = $row_pac['nom_pac'];
        $pac_dir = $row_pac['dir'];
        $pac_id_edo = $row_pac['id_edo'];
        $pac_id_mun = $row_pac['id_mun'];
        $pac_tel = $row_pac['tel'];
        $pac_fecnac = $row_pac['fecnac'];
        $pac_fecing = $row_pac['fecha'];
        $pac_tip_sang = $row_pac['tip_san'];
        $pac_sexo = $row_pac['sexo'];
        $area = $row_pac['area'];
        $alta_med = $row_pac['alta_med'];
        $id_exp = $row_pac['Id_exp'];
        $alergias = $row_pac['alergias'];
      }

      $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_now = $conexion->query($sql_now);

      while ($row_now = $result_now->fetch_assoc()) {
        $dat_now = $row_now['dat_now'];
      }

      $sql_est = "SELECT DATEDIFF( '$dat_now' , fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
        $estancia = $row_est['estancia'];
      }

      function calculaedad($fechanacimiento)
      {
        list($ano, $mes, $dia) = explode("-", $fechanacimiento);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
          $ano_diferencia--;
        return $ano_diferencia;
      }

      $edad = calculaedad($pac_fecnac);

    ?>
  

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <a href="javascript:window.print()" class="btn btn-outline-primary">
      <i class="fa fa-print" aria-hidden="true"></i> Imprimir
    </a>
    <button id="downloadChart" class="btn btn-outline-success">
      <i class="fas fa-download"></i> Descargar Gráfica
    </button>
  </div>
  <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
  <div class="row">
    
       <div class="col-sm-3">
        <br><img src="../../imagenes/SI.PNG" height="60" width="185">
       </div>
       <div class="col-sm-7">
          <center><strong>INSTITUTO DE ENFERMEDADES OCULARES</strong></center>
          <center><strong>Av. Tecnológico #1020, Metepec, México CP 52140</strong></center>
          <center><strong>Teléfono: 722 232 8086 / 722 2386901</strong></center>
          <center><strong>https://ineo.simaclinicas.com</strong></center>
          <center><strong>REGISTRO GRÁFICO TRANS-ANESTÉSICO</strong></center>
      </div>
      <div class="col-sm-1">
          <br><img src="../../imagenes/SIF.PNG" height="60" width="165">
      </div>
    </div>
  </div>
</div><p>

<div class="container">
        <div class="mt-3">
            <?php if (isset($_SESSION['message']) && isset($_SESSION['message_type'])): ?>
            <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message_type']); ?> alert-dismissible fade show"
                role="alert">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
        // Limpiar el mensaje
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col">
                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;"><strong>
                        <center>DATOS DEL PACIENTE</center>
                    </strong></div>
                    <?php
                    if (isset($_SESSION['pac'])) {
                        $id_atencion = $_SESSION['pac'];
                        $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac, p.tip_san, di.fecha, di.area, di.alta_med, di.activo, p.sexo, di.alergias, p.ocup FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp AND di.id_atencion = ?";
                        $stmt = $conexion->prepare($sql_pac);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_pac = $stmt->get_result();
                        while ($row_pac = $result_pac->fetch_assoc()) {
                            $pac_papell = $row_pac['papell'];
                            $pac_sapell = $row_pac['sapell'];
                            $pac_nom_pac = $row_pac['nom_pac'];
                            $pac_dir = $row_pac['dir'];
                            $pac_id_edo = $row_pac['id_edo'];
                            $pac_id_mun = $row_pac['id_mun'];
                            $pac_tel = $row_pac['tel'];
                            $pac_fecnac = $row_pac['fecnac'];
                            $pac_fecing = $row_pac['fecha'];
                            $pac_tip_sang = $row_pac['tip_san'];
                            $pac_sexo = $row_pac['sexo'];
                            $area = $row_pac['area'];
                            $alta_med = $row_pac['alta_med'];
                            $id_exp = $row_pac['Id_exp'];
                            $folio = $row_pac['folio'];
                            $alergias = $row_pac['alergias'];
                            $ocup = $row_pac['ocup'];
                            $activo = $row_pac['activo'];
                        }
                        $stmt->close();
                        $stmt = $conexion->prepare("SELECT area FROM dat_ingreso WHERE id_atencion = ?");
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $resultado1 = $stmt->get_result();

                        $area = "No asignada"; // Default value
                        if ($f1 = $resultado1->fetch_assoc()) {
                            $area = $f1['area'];
                        }
                        $stmt->close();

                        if ($activo === 'SI') {
                            $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = ?";
                            $stmt = $conexion->prepare($sql_now);
                            $stmt->bind_param("i", $id_atencion);
                            $stmt->execute();
                            $result_now = $stmt->get_result();
                            while ($row_now = $result_now->fetch_assoc()) {
                                $dat_now = $row_now['dat_now'];
                            }
                            $stmt->close();
                            $sql_est = "SELECT DATEDIFF( ?, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
                            $stmt = $conexion->prepare($sql_est);
                            $stmt->bind_param("si", $dat_now, $id_atencion);
                            $stmt->execute();
                            $result_est = $stmt->get_result();
                            while ($row_est = $result_est->fetch_assoc()) {
                                $estancia = $row_est['estancia'];
                            }
                            $stmt->close();
                        } else {
                            $sql_est = "SELECT DATEDIFF(fec_egreso, fecha) as estancia FROM dat_ingreso WHERE id_atencion = ?";
                            $stmt = $conexion->prepare($sql_est);
                            $stmt->bind_param("i", $id_atencion);
                            $stmt->execute();
                            $result_est = $stmt->get_result();
                            while ($row_est = $result_est->fetch_assoc()) {
                                $estancia = ($row_est['estancia'] == 0) ? 1 : $row_est['estancia'];
                            }
                            $stmt->close();
                        }

                        $d = "";
                        $sql_motd = "SELECT diagprob_i FROM dat_not_ingreso WHERE id_atencion = ? ORDER BY id_not_ingreso DESC LIMIT 1";
                        $stmt = $conexion->prepare($sql_motd);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_motd = $stmt->get_result();
                        while ($row_motd = $result_motd->fetch_assoc()) {
                            $d = $row_motd['diagprob_i'];
                        }
                        $stmt->close();

                        if (!$d) {
                            $sql_motd = "SELECT diagprob_i FROM dat_nevol WHERE id_atencion = ? ORDER BY id_ne DESC LIMIT 1";
                            $stmt = $conexion->prepare($sql_motd);
                            $stmt->bind_param("i", $id_atencion);
                            $stmt->execute();
                            $result_motd = $stmt->get_result();
                            while ($row_motd = $result_motd->fetch_assoc()) {
                                $d = $row_motd['diagprob_i'];
                            }
                            $stmt->close();
                        }

                        $sql_mot = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = ? ORDER BY motivo_atn ASC LIMIT 1";
                        $stmt = $conexion->prepare($sql_mot);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_mot = $stmt->get_result();
                        while ($row_mot = $result_mot->fetch_assoc()) {
                            $m = $row_mot['motivo_atn'];
                        }
                        $stmt->close();

                        $sql_edo = "SELECT edo_salud FROM dat_ingreso WHERE id_atencion = ? ORDER BY edo_salud ASC LIMIT 1";
                        $stmt = $conexion->prepare($sql_edo);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_edo = $stmt->get_result();
                        while ($row_edo = $result_edo->fetch_assoc()) {
                            $edo_salud = $row_edo['edo_salud'];
                        }
                        $stmt->close();

                        $sql_hab = "SELECT num_cama FROM cat_camas WHERE id_atencion = ?";
                        $stmt = $conexion->prepare($sql_hab);
                        $stmt->bind_param("i", $id_atencion);
                        $stmt->execute();
                        $result_hab = $stmt->get_result();
                        $num_cama = $result_hab->fetch_assoc()['num_cama'] ?? '';
                        $stmt->close();

                        $sql_hclinica = "SELECT peso, talla FROM dat_hclinica WHERE Id_exp = ? ORDER BY id_hc DESC LIMIT 1";
                        $stmt = $conexion->prepare($sql_hclinica);
                        $stmt->bind_param("s", $id_exp);
                        $stmt->execute();
                        $result_hclinica = $stmt->get_result();
                        $peso = 0;
                        $talla = 0;
                        while ($row_hclinica = $result_hclinica->fetch_assoc()) {
                            $peso = $row_hclinica['peso'] ?? 0;
                            $talla = $row_hclinica['talla'] ?? 0;
                        }
                        $stmt->close();
                    } else {
                        echo '<script type="text/javascript">window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
                    }
                    ?>
                <div class="row">
                    <div class="col-sm-4">Expediente: <strong><?php echo $folio; ?></strong></div>
                    <div class="col-sm-4">Paciente:
                        <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac; ?></strong>
                    </div>
                    <div class="col-sm-4">Fecha de atención:
                        <strong><?php echo date_format(date_create($pac_fecing), "d/m/Y H:i:s"); ?></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">Fecha de nacimiento:
                        <strong><?php echo date_format(date_create($pac_fecnac), "d/m/Y"); ?></strong>
                    </div>
                    <div class="col-sm-4">Edad: <strong><?php
                        $fecha_actual = date("Y-m-d");
                        $fecha_nac = $pac_fecnac;
                        $array_nacimiento = explode("-", $fecha_nac);
                        $array_actual = explode("-", $fecha_actual);
                        $anos = $array_actual[0] - $array_nacimiento[0];
                        $meses = $array_actual[1] - $array_nacimiento[1];
                        $dias = $array_actual[2] - $array_nacimiento[2];
                        if ($dias < 0) { --$meses; $dias += ($array_actual[1] == 3 && date("L", strtotime($fecha_actual)) ? 29 : 28); }
                        if ($meses < 0) { --$anos; $meses += 12; }
                        echo ($anos > 0 ? $anos . " años" : ($meses > 0 ? $meses . " meses" : $dias . " días"));
                    ?></strong></div>
                    <div class="col-sm-4">Área: <strong><?php echo $num_cama .' - '.$area;?> </strong></div> 
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <?php echo $d ? "Diagnóstico: <strong>$d</strong>" : "Motivo de atención: <strong>$m</strong>"; 
                        ?>
                    </div>

                    <div class="col-sm-4">Alergias: <strong><?php echo $alergias; ?></strong></div>
                   
                    
                </div>

            </div>
        </div>
    </div>
<hr>

        <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
      }
        ?>
    
<body>
  
<div class="container">
    <div class="row">
        
            
            
         

            <!--<div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
            </div>-->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="container" >
  <div class="row">
  
 <div class="col-sm-12">
     
    
   <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from dat_trans_grafico WHERE id_atencion=$id_atencion ORDER BY id_trans_graf ASC limit 1") or die($conexion->error);
$usuario = $_SESSION['login'];

// Función para obtener el último registro de cada campo
function obtenerUltimoRegistro($conexion, $id_atencion, $campo, $id_tratamiento = null) {
    $where_tratamiento = "";
    if ($id_tratamiento) {
        $where_tratamiento = " AND id_tratamiento = $id_tratamiento";
    }
    
    $sql = "SELECT $campo FROM dat_trans_grafico WHERE id_atencion=$id_atencion $where_tratamiento AND $campo > 0 ORDER BY fecha_g DESC, id_trans_graf DESC LIMIT 1";
    $result = $conexion->query($sql);
    
    if ($result && $row = $result->fetch_assoc()) {
        return $row[$campo] ? $row[$campo] : 0;
    }
    return 0;
}

// Función para obtener datos de manera más eficiente con filtro por tratamiento
function obtenerDatosGrafico($conexion, $id_atencion, $campo, $id_tratamiento = null) {
    $datos = array();
    
    $where_tratamiento = "";
    if ($id_tratamiento) {
        $where_tratamiento = " AND id_tratamiento = $id_tratamiento";
    }
    
    for ($i = 1; $i <= 48; $i++) {
        $sql = "SELECT $campo FROM dat_trans_grafico WHERE id_atencion=$id_atencion $where_tratamiento AND (cuenta=$i OR hora=$i) ORDER BY fecha_g DESC LIMIT 1";
        $result = $conexion->query($sql);
        $valor = 0;
        if ($result && $row = $result->fetch_assoc()) {
            $valor = $row[$campo] ? $row[$campo] : 0;
        }
        $datos[] = $valor;
    }
    return $datos;
}

// Obtener el ID del tratamiento si se especifica
$id_tratamiento_filtro = isset($_GET['tratamiento_id']) ? (int)$_GET['tratamiento_id'] : null;

// Obtener datos para cada métrica (con filtro opcional por tratamiento)
$presion_sistolica = obtenerDatosGrafico($conexion, $id_atencion, 'sistg', $id_tratamiento_filtro);
$presion_diastolica = obtenerDatosGrafico($conexion, $id_atencion, 'diastg', $id_tratamiento_filtro);
$frecuencia_cardiaca = obtenerDatosGrafico($conexion, $id_atencion, 'fcardg', $id_tratamiento_filtro);
$frecuencia_respiratoria = obtenerDatosGrafico($conexion, $id_atencion, 'frespg', $id_tratamiento_filtro);
$saturacion = obtenerDatosGrafico($conexion, $id_atencion, 'satg', $id_tratamiento_filtro);
$temperatura = obtenerDatosGrafico($conexion, $id_atencion, 'tempg', $id_tratamiento_filtro);

// Calcular estadísticas - obtener último registro y rango
$stats = array(
    'presion_sistolica' => array(
        'ultimo' => obtenerUltimoRegistro($conexion, $id_atencion, 'sistg', $id_tratamiento_filtro),
        'min' => min(array_filter($presion_sistolica)),
        'max' => max(array_filter($presion_sistolica))
    ),
    'presion_diastolica' => array(
        'ultimo' => obtenerUltimoRegistro($conexion, $id_atencion, 'diastg', $id_tratamiento_filtro),
        'min' => min(array_filter($presion_diastolica)),
        'max' => max(array_filter($presion_diastolica))
    ),
    'frecuencia_cardiaca' => array(
        'ultimo' => obtenerUltimoRegistro($conexion, $id_atencion, 'fcardg', $id_tratamiento_filtro),
        'min' => min(array_filter($frecuencia_cardiaca)),
        'max' => max(array_filter($frecuencia_cardiaca))
    ),
    'frecuencia_respiratoria' => array(
        'ultimo' => obtenerUltimoRegistro($conexion, $id_atencion, 'frespg', $id_tratamiento_filtro),
        'min' => min(array_filter($frecuencia_respiratoria)),
        'max' => max(array_filter($frecuencia_respiratoria))
    ),
    'saturacion' => array(
        'ultimo' => obtenerUltimoRegistro($conexion, $id_atencion, 'satg', $id_tratamiento_filtro),
        'min' => min(array_filter($saturacion)),
        'max' => max(array_filter($saturacion))
    ),
    'temperatura' => array(
        'ultimo' => obtenerUltimoRegistro($conexion, $id_atencion, 'tempg', $id_tratamiento_filtro),
        'min' => min(array_filter($temperatura)),
        'max' => max(array_filter($temperatura))
    )
);
?>
   <?php
                $no=1;
                while($f = mysqli_fetch_array($resultado)){
                   
                    ?>

<div class="chart-container">
    <div class="chart-header">
        <i class="fas fa-chart-line"></i> GRÁFICO DE SIGNOS VITALES - REGISTRO TRANS-ANESTÉSICO
        <?php if ($id_tratamiento_filtro): ?>
            <?php
            // Obtener el nombre del tratamiento seleccionado
            $sql_nombre_trat = "SELECT tipo FROM tratamientos WHERE id = $id_tratamiento_filtro";
            $result_nombre_trat = $conexion->query($sql_nombre_trat);
            if ($result_nombre_trat && $row_nombre_trat = $result_nombre_trat->fetch_assoc()) {
                echo '<br><small style="color: #2b2d7f; font-weight: bold;">Tratamiento: ' . strtoupper($row_nombre_trat['tipo']) . '</small>';
            }
            ?>
        <?php endif; ?>
    </div>
    
    <!-- Selector de tratamiento -->
    <div class="mb-3 no-print" style="padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
        <label for="filtro_tratamiento" style="font-weight: bold; color: #2b2d7f;">
            <i class="fas fa-filter"></i> Filtrar por tratamiento:
        </label>
        <div class="row">
            <div class="col-md-8">
                <select id="filtro_tratamiento" class="form-control" onchange="filtrarPorTratamiento()">
                    <option value="">Todos los tratamientos</option>
                    <?php
                    // Obtener los tratamientos que tienen registros en dat_trans_grafico para esta atención
                    $sql_tratamientos = "SELECT DISTINCT t.id, t.tipo FROM tratamientos t 
                                       INNER JOIN dat_trans_grafico dtg ON t.id = dtg.id_tratamiento 
                                       WHERE dtg.id_atencion = $id_atencion 
                                       ORDER BY t.tipo";
                    $result_tratamientos = $conexion->query($sql_tratamientos);
                    if ($result_tratamientos) {
                        while ($row_trat = $result_tratamientos->fetch_assoc()) {
                            $selected = ($row_trat['id'] == $id_tratamiento_filtro) ? 'selected' : '';
                            echo '<option value="' . $row_trat['id'] . '" ' . $selected . '>' . strtoupper($row_trat['tipo']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-info" onclick="verTodosLosTratamientos()">
                    <i class="fas fa-eye"></i> Ver Todos
                </button>
            </div>
        </div>
    </div>
    
    <div class="chart-legend">
        <div class="legend-item" data-dataset="0">
            <div class="legend-color" style="background-color: #e74c3c;"></div>
            <span>Presión Sistólica</span>
        </div>
        <div class="legend-item" data-dataset="1">
            <div class="legend-color" style="background-color: #f39c12;"></div>
            <span>Presión Diastólica</span>
        </div>
        <div class="legend-item" data-dataset="2">
            <div class="legend-color" style="background-color: #3498db;"></div>
            <span>Frecuencia Cardíaca</span>
        </div>
        <div class="legend-item" data-dataset="3">
            <div class="legend-color" style="background-color: #9b59b6;"></div>
            <span>Frecuencia Respiratoria</span>
        </div>
        <div class="legend-item" data-dataset="4">
            <div class="legend-color" style="background-color: #27ae60;"></div>
            <span>Saturación O₂</span>
        </div>
        <div class="legend-item" data-dataset="5">
            <div class="legend-color" style="background-color:rgb(255, 234, 0);"></div>
            <span>Temperatura</span>
        </div>
    </div>
    
    <canvas id="grafica" style="max-height: 300px;"></canvas>
</div>

<!-- Tarjetas de estadísticas en el formato de la imagen -->
<div class="container mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                PRESIÓN SISTÓLICA</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['presion_sistolica']['ultimo']; ?> <span class="text-xs">mmHg</span></div>
              <small class="text-muted">Rango: <?php echo $stats['presion_sistolica']['min']; ?> - <?php echo $stats['presion_sistolica']['max']; ?> mmHg</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                PRESIÓN DIASTÓLICA</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['presion_diastolica']['ultimo']; ?> <span class="text-xs">mmHg</span></div>
              <small class="text-muted">Rango: <?php echo $stats['presion_diastolica']['min']; ?> - <?php echo $stats['presion_diastolica']['max']; ?> mmHg</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                FRECUENCIA CARDÍACA</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['frecuencia_cardiaca']['ultimo']; ?> <span class="text-xs">lpm</span></div>
              <small class="text-muted">Rango: <?php echo $stats['frecuencia_cardiaca']['min']; ?> - <?php echo $stats['frecuencia_cardiaca']['max']; ?> lpm</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row justify-content-center">
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="card border-left-secondary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                FRECUENCIA RESPIRATORIA</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['frecuencia_respiratoria']['ultimo']; ?> <span class="text-xs">rpm</span></div>
              <small class="text-muted">Rango: <?php echo $stats['frecuencia_respiratoria']['min']; ?> - <?php echo $stats['frecuencia_respiratoria']['max']; ?> rpm</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                SATURACIÓN DE O₂</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['saturacion']['ultimo']; ?> <span class="text-xs">%</span></div>
              <small class="text-muted">Rango: <?php echo $stats['saturacion']['min']; ?> - <?php echo $stats['saturacion']['max']; ?>%</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                TEMPERATURA</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['temperatura']['ultimo']; ?> <span class="text-xs">°C</span></div>
              <small class="text-muted">Rango: <?php echo $stats['temperatura']['min']; ?> - <?php echo $stats['temperatura']['max']; ?>°C</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    
    <!-- Sección de botones de navegación -->
    <div class="container my-4 no-print">
      <div class="row justify-content-center">
        <div class="col-md-6 text-center">
          <div class="navigation-card p-4">
            <h5 class="mb-3" style="color: #2b2d7f; font-weight: bold;">
              <i class="fas fa-clipboard-check"></i> Navegación
            </h5>
            <p class="text-muted mb-4">¿Desea continuar con el registro o regresar al formulario anterior?</p>
            
            <div class="btn-group-custom">
              <a href="nota_registro_grafico.php" class="btn btn-regresar">
                <i class="fas fa-arrow-left"></i>
                <span>Regresar al Registro</span>
              </a>
              
              <button onclick="window.print()" class="btn btn-secundario">
                <i class="fas fa-print"></i>
                <span>Imprimir Gráfica</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>


                <?php
                }
                ?>
    </div>
    
   
  
  </div><p>
<div class="container">
  <div class="row">
    <div class="col-sm-9">
    </div>
    <div class="col-sm">
    </div>

    <div class="col-sm">
      <small><strong>CMSI-15.10</strong></small>
    </div>
  </div>
</div>

<hr>
  
</div>
   

        </div>
    </div>
    
</div>



<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
      
 



<script>
const $grafica = document.querySelector("#grafica");

// Etiquetas del eje X (horas)
const etiquetas = Array.from({length: 48}, (_, i) => `${Math.floor(i/2)}:${i%2 === 0 ? '00' : '30'}`);

// Datos obtenidos de PHP
const presionSistolica = <?php echo json_encode($presion_sistolica); ?>;
const presionDiastolica = <?php echo json_encode($presion_diastolica); ?>;
const frecuenciaCardiaca = <?php echo json_encode($frecuencia_cardiaca); ?>;
const frecuenciaRespiratoria = <?php echo json_encode($frecuencia_respiratoria); ?>;
const saturacionO2 = <?php echo json_encode($saturacion); ?>;
const temperatura = <?php echo json_encode($temperatura); ?>;

// Configuración de datasets
const datasets = [
    {
        label: "Presión Sistólica",
        data: presionSistolica,
        backgroundColor: 'rgba(231, 76, 60, 0.1)',
        borderColor: '#e74c3c',
        borderWidth: 3,
        pointBackgroundColor: '#e74c3c',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        tension: 0.4,
        fill: false
    },
    {
        label: "Presión Diastólica",
        data: presionDiastolica,
        backgroundColor: 'rgba(243, 156, 18, 0.1)',
        borderColor: '#f39c12',
        borderWidth: 3,
        pointBackgroundColor: '#f39c12',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        tension: 0.4,
        fill: false
    },
    {
        label: "Frecuencia Cardíaca",
        data: frecuenciaCardiaca,
        backgroundColor: 'rgba(52, 152, 219, 0.1)',
        borderColor: '#3498db',
        borderWidth: 3,
        pointBackgroundColor: '#3498db',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        tension: 0.4,
        fill: false
    },
    {
        label: "Frecuencia Respiratoria",
        data: frecuenciaRespiratoria,
        backgroundColor: 'rgba(155, 89, 182, 0.1)',
        borderColor: '#9b59b6',
        borderWidth: 3,
        pointBackgroundColor: '#9b59b6',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        tension: 0.4,
        fill: false
    },
    {
        label: "Saturación O₂",
        data: saturacionO2,
        backgroundColor: 'rgba(39, 174, 96, 0.1)',
        borderColor: '#27ae60',
        borderWidth: 3,
        pointBackgroundColor: '#27ae60',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        tension: 0.4,
        fill: false
    },
    {
        label: "Temperatura",
        data: temperatura,
        backgroundColor: 'rgba(255, 234, 0, 0.1)',
        borderColor: 'rgb(255, 234, 0)',
        borderWidth: 3,
        pointBackgroundColor: 'rgb(255, 234, 0)',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        tension: 0.4,
        fill: false
    }
];

// Crear la gráfica
const chart = new Chart($grafica, {
    type: 'line',
    data: {
        labels: etiquetas,
        datasets: datasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            title: {
                display: true,
                text: 'Monitoreo de Signos Vitales por Horas',
                font: {
                    size: 16,
                    weight: 'bold'
                },
                color: '#2b2d7f',
                padding: 20
            },
            legend: {
                display: false // Usamos la leyenda personalizada
            },
            tooltip: {
                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                titleColor: '#2b2d7f',
                bodyColor: '#333',
                borderColor: '#ddd',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true,
                callbacks: {
                    title: function(context) {
                        return 'Hora: ' + context[0].label;
                    },
                    label: function(context) {
                        let unit = '';
                        if (context.datasetIndex === 0 || context.datasetIndex === 1) {
                            unit = ' mmHg';
                        } else if (context.datasetIndex === 2) {
                            unit = ' lpm';
                        } else if (context.datasetIndex === 3) {
                            unit = ' rpm';
                        } else if (context.datasetIndex === 4) {
                            unit = '%';
                        } else if (context.datasetIndex === 5) {
                            unit = '°C';
                        }
                        return context.dataset.label + ': ' + context.parsed.y + unit;
                    }
                }
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'Tiempo (Horas)',
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    color: '#2b2d7f'
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)',
                    lineWidth: 1
                },
                ticks: {
                    maxTicksLimit: 12,
                    color: '#666'
                }
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: 'Valores',
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    color: '#2b2d7f'
                },

                beginAtZero: true,
                min: 0,
                max: 150,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)',
                    lineWidth: 1
                },
                ticks: {
                    color: '#666',
                    stepSize: 15,
                    callback: function(value, index, values) {
                        return value;
                    }
                }
            }
        },
        elements: {
            point: {
                hoverBorderWidth: 3
            }
        }
    }
});

// Funcionalidad para descargar la gráfica
document.getElementById('downloadChart').addEventListener('click', function() {
    const link = document.createElement('a');
    link.download = 'grafica_signos_vitales.png';
    link.href = chart.toBase64Image('image/png', 1.0);
    link.click();
});

// Funcionalidad para la leyenda interactiva
document.querySelectorAll('.legend-item').forEach(function(legendItem) {
    legendItem.addEventListener('click', function() {
        const datasetIndex = parseInt(this.getAttribute('data-dataset'));
        const meta = chart.getDatasetMeta(datasetIndex);
        
        // Alternar la visibilidad del dataset
        meta.hidden = meta.hidden === null ? !chart.data.datasets[datasetIndex].hidden : null;
        
        // Actualizar la apariencia visual de la leyenda
        if (meta.hidden) {
            this.classList.add('inactive');
        } else {
            this.classList.remove('inactive');
        }
        
        // Actualizar la gráfica
        chart.update();
    });
});

// Función para filtrar por tratamiento
function filtrarPorTratamiento() {
    const select = document.getElementById('filtro_tratamiento');
    const tratamientoId = select.value;
    
    let url = 'ver_grafica.php';
    if (tratamientoId) {
        url += '?tratamiento_id=' + tratamientoId;
    }
    
    window.location.href = url;
}

// Función para ver todos los tratamientos
function verTodosLosTratamientos() {
    window.location.href = 'ver_grafica.php';
}
</script>

  
</body>

</html>
