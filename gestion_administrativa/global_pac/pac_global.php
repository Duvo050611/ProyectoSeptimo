    <?php
    session_start();
    include "../../conexionbd.php";
    include "../header_administrador.php";
   
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <script src="https://your-site-or-cdn.com/fontawesome/v6.1.1/js/all.js" data-auto-replace-svg="nest"></script>
<meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
              integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
              crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
                integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
                crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
                integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
                integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
                crossorigin="anonymous"></script>
        <!--  Bootstrap  -->
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
              integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ"
              crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>

    <link rel="stylesheet" href="css_busc/estilos2.css">
    <script src="js_busc/jquery.js"></script>
    <script src="js_busc/jquery.dataTables.min.js"></script>

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


        <title>Creación de Paciente</title>
        <link rel="shortcut icon" href="logp.png">

        <style>
    * {
        box-sizing: border-box;
    }

    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        overflow-x: hidden;
    }

    body {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
        font-family: 'Roboto', sans-serif !important;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
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

    /* Wrapper para AdminLTE */
    .wrapper {
        position: relative;
        z-index: 1;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Content wrapper debe crecer para empujar el footer */
    .content-wrapper {
        flex: 1;
        background: transparent !important;
        min-height: calc(100vh - 100px);
    }

    .container {
        position: relative;
        z-index: 1;
        padding-top: 30px;
        padding-bottom: 50px;
        max-width: 100%;
        margin: 0 auto;
        background: rgba(15, 52, 96, 0.1);
        border-radius: 15px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    /* Título principal mejorado */
    .thead {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px !important;
        padding: 20px 30px !important;
        margin: 20px 0 30px 0 !important;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        position: relative;
        overflow: hidden;
        color: #ffffff !important;
        font-size: 22px !important;
        text-align: center;
        letter-spacing: 1px;
        text-shadow: 0 0 20px rgba(64, 224, 255, 0.5);
    }

    .thead::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(64, 224, 255, 0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .thead strong {
        position: relative;
        z-index: 1;
    }

    /* Botones mejorados */
    .btn {
        border-radius: 25px !important;
        padding: 12px 25px !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
        transition: all 0.3s ease !important;
        border: 2px solid #40E0FF !important;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent,
            rgba(64, 224, 255, 0.1),
            transparent
        );
        transform: rotate(45deg);
        transition: all 0.6s ease;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-sm {
        padding: 8px 20px !important;
        font-size: 14px !important;
    }

    .btn-danger {
        background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%) !important;
        color: #ffffff !important;
        border-color: #f48fb1 !important;
    }

    .btn-danger:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(244, 143, 177, 0.4) !important;
        background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%) !important;
        border-color: #f8bbd0 !important;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%) !important;
        color: #ffffff !important;
        border-color: #ffa726 !important;
    }

    .btn-warning:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(255, 167, 38, 0.4) !important;
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%) !important;
        border-color: #ffcc80 !important;
    }

    /* Barra de búsqueda mejorada */
    .form-control {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 25px !important;
        padding: 15px 25px !important;
        color: #ffffff !important;
        font-size: 16px;
        box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2);
        transition: all 0.3s ease;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .form-control:focus {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border-color: #00D9FF !important;
        box-shadow: 0 8px 25px rgba(64, 224, 255, 0.4) !important;
        color: #ffffff !important;
        outline: none;
    }

    .form-control.pull-right {
        float: right;
    }

    /* Divider mejorado */
    hr {
        border: 0;
        height: 2px;
        background: linear-gradient(135deg, #40E0FF 0%, #00D9FF 100%) !important;
        margin: 20px 0 !important;
        border-radius: 1px;
        box-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Tabla mejorada */
    .table-responsive {
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.8) 0%, rgba(22, 33, 62, 0.8) 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 15px !important;
        padding: 20px;
        box-shadow: 0 8px 30px rgba(64, 224, 255, 0.3);
        margin-bottom: 30px;
        overflow-x: auto;
        overflow-y: visible;
    }

    .table {
        color: #ffffff !important;
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead {
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
    }

    .table thead th {
        color: #40E0FF !important;
        border: 2px solid #40E0FF !important;
        padding: 15px 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
        font-size: 14px;
    }

    .table tbody tr {
        background: rgba(15, 52, 96, 0.5) !important;
        border-bottom: 1px solid rgba(64, 224, 255, 0.2);
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(64, 224, 255, 0.1) !important;
        transform: translateX(5px);
    }

    .table tbody td {
        border: 1px solid rgba(64, 224, 255, 0.2) !important;
        padding: 12px 15px;
        color: #ffffff !important;
        vertical-align: middle;
        text-align: center;
        font-size: 14px;
    }

    .table tbody td strong {
        color: #ffffff !important;
    }

    /* Botones en tabla */
    .table .btn-block {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 10px !important;
        padding: 10px !important;
        transition: all 0.3s ease;
    }

    .table .btn-block:hover {
        transform: scale(1.05) rotate(2deg) !important;
        box-shadow: 0 5px 20px rgba(64, 224, 255, 0.4) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
    }

    .table .btn-block img {
        width: 32px;
        height: 32px;
        filter: brightness(0) invert(1);
        transition: transform 0.3s ease;
    }

    .table .btn-block:hover img {
        transform: scale(1.1);
    }

    .table .btn-danger.btn-sm {
        background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%) !important;
        border: 2px solid #f48fb1 !important;
        border-radius: 10px !important;
        padding: 8px 15px !important;
        transition: all 0.3s ease;
    }

    .table .btn-danger.btn-sm:hover {
        transform: scale(1.1) !important;
        box-shadow: 0 5px 20px rgba(244, 143, 177, 0.4) !important;
    }

    .table .btn-danger.btn-sm span {
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .table .btn-danger.btn-sm:hover span {
        transform: scale(1.2);
    }

    /* Iconos dentro de botones */
    .btn i, .btn span {
        transition: transform 0.3s ease;
    }

    .btn:hover i, .btn:hover span {
        transform: scale(1.2);
    }

    /* Imágenes en botones */
    .btn-warning img {
        width: 24px;
        height: 24px;
        margin-right: 8px;
        vertical-align: middle;
        transition: transform 0.3s ease;
    }

    .btn-warning:hover img {
        transform: scale(1.1);
    }

    /* Centrado de texto */
    .text-center {
        text-align: center !important;
    }

    center {
        text-align: center !important;
    }

    /* Footer corregido y centrado */
    .main-footer {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border-top: 2px solid #40E0FF !important;
        color: #ffffff !important;
        box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
        margin-top: 50px;
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding: 20px;
        text-align: center;
        width: 100%;
        position: relative;
        left: 0;
        right: 0;
    }

    /* Si el footer está dentro de .wrapper de AdminLTE */
    .wrapper > .main-footer {
        margin-left: 0 !important;
        width: 100% !important;
    }

    /* Para páginas con sidebar de AdminLTE */
    @media (min-width: 768px) {
        .sidebar-mini.sidebar-collapse .main-footer {
            margin-left: 50px !important;
        }
        
        .sidebar-mini:not(.sidebar-collapse) .main-footer {
            margin-left: 230px !important;
        }
    }

    /* Para páginas sin sidebar (como esta) */
    body:not(.sidebar-mini) .main-footer {
        margin-left: 0 !important;
    }

    .main-footer p,
    .main-footer a {
        color: #ffffff !important;
        margin: 5px 0;
    }

    .main-footer a:hover {
        color: #40E0FF !important;
        text-decoration: none;
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 12px;
        height: 12px;
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

    /* Row adjustments */
    .row {
        margin-left: 0;
        margin-right: 0;
        margin-bottom: 20px;
    }

    .row > [class*='col-'] {
        padding-left: 10px;
        padding-right: 10px;
    }

    /* Animaciones de entrada */
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

    .container > * {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Ajustes específicos para esta vista */
    .col-md-5 {
        width: auto !important;
        padding: 0 5px;
    }

    /* Estilos para los textos en blanco */
    font[color="white"] {
        color: #ffffff !important;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
        .thead {
            padding: 15px 20px !important;
            font-size: 18px !important;
            margin: 15px 0 20px 0 !important;
        }

        .btn {
            font-size: 0.85rem !important;
            padding: 10px 20px !important;
        }

        .btn-sm {
            padding: 6px 15px !important;
            font-size: 13px !important;
        }

        .form-control {
            padding: 12px 20px !important;
            font-size: 14px !important;
        }

        .form-control.pull-right {
            width: 100% !important;
            float: none;
            margin-bottom: 15px;
        }

        .table-responsive {
            padding: 15px;
            margin-bottom: 20px;
        }

        .table thead th,
        .table tbody td {
            padding: 10px 12px;
            font-size: 13px;
        }

        .container {
            padding: 20px 15px 40px 15px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .main-footer {
            padding: 15px;
            margin-top: 30px;
        }

        .table .btn-block img {
            width: 28px;
            height: 28px;
        }
    }

    @media screen and (max-width: 576px) {
        .thead {
            font-size: 16px !important;
            padding: 12px 15px !important;
        }

        .btn {
            font-size: 0.75rem !important;
            padding: 8px 15px !important;
        }

        .btn-sm {
            padding: 5px 12px !important;
            font-size: 12px !important;
        }

        .form-control {
            padding: 10px 15px !important;
            font-size: 13px !important;
        }

        .table-responsive {
            padding: 10px;
        }

        .table {
            font-size: 12px;
        }

        .table thead th,
        .table tbody td {
            padding: 8px 10px;
            font-size: 12px;
        }

        .col-5, .col-12 {
            text-align: center !important;
            margin-bottom: 10px;
        }

        .btn-warning img {
            width: 20px;
            height: 20px;
        }

        .table .btn-block img {
            width: 24px;
            height: 24px;
        }
    }

    /* Para pantallas muy pequeñas */
    @media screen and (max-width: 400px) {
        .table-responsive {
            padding: 5px;
        }

        .table thead th,
        .table tbody td {
            padding: 6px 8px;
            font-size: 11px;
        }

        .table .btn-block {
            padding: 6px !important;
        }

        .table .btn-block img {
            width: 20px;
            height: 20px;
        }

        .table .btn-danger.btn-sm {
            padding: 6px 10px !important;
            font-size: 11px !important;
        }

        .table .btn-danger.btn-sm span {
            font-size: 16px;
        }
    }

    /* Asegurar que no haya overflow horizontal */
    .content-wrapper,
    .container-fluid,
    .container {
        overflow-x: hidden;
    }
</style>
    </head>
        <div class="container">
             
            <div class="row">
                 <div class="col col-5">
                
                    <a href="../gestion_pacientes/registro_pac.php" class="btn btn-danger btn-sm">Regresar</a>
                   
             
                </div>
                <div class="form-group"> 
                
                    <a href="excelpacientes.php"><button type="button" class="btn btn-warning btn-sm">
                    <img src="https://img.icons8.com/color/48/000000/ms-excel.png" width="42"/><strong>Exportar a excel</strong></button></a>
             
                </div>
                  <div class="col col-12">
                    <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
                    <br>
                   <center>
                        
                        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                                <tr><strong><center>PACIENTES REGISTRADOS</center></strong>
                        </div>
                        <hr>
                    </center>
<?php
$usuario = $_SESSION['hospital'];
?>


                    
                    <h2>
                        <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
                    </h2>


                    <div class="form-group">
                        <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="BUSCAR...">
                    </div><br>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="mytable">
                            <thead class="thead" style="background-color: #2b2d7f;color:white;">
                        <tr>
                            <th scope="col"><font color="white">Ver Datos</th>
                            <th scope="col"><font color="white">Notas médicas</th>
                            <th scope="col"><font color="white">Exp.</th>
                            <th scope="col"><font color="white">No. Atención</th>
                            <th scope="col"><font color="white">Fec Ingreso</th>
                            <th scope="col"><font color="white">Fec Egreso</th>
                            <th scope="col"><font color="white">Nombre del paciente</th>
                            <th scope="col"><font color="white">Edad</th>
                            <th scope="col"><font color="white">Fec nacimiento</th>
                            <th scope="col"><font color="white">Área</th>
                            <th scope="col"><font color="white">Aseguradora</th>
                           
                            
                          
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                         $resultado = $conexion->query("SELECT p.*, d.* from paciente p, dat_ingreso d WHERE d.Id_exp=p.Id_exp order by p.Id_exp DESC") or die($conexion->error);

                        while ($f = mysqli_fetch_array($resultado)) {
                            $fec_ing = date_create($f['fecha']);
                            if ($f['fec_egreso']<>'NUll'){
                                $fec_egr = date_create($f['fec_egreso']);
                                $fec_egreso = date_format($fec_egr,'d/m/Y H:i a');
                            }
                            else 
                                $fec_egreso = 'Null';
                        ?>
                        
                        
                        <tr>
                                <td ><center><a href="vista_pac.php?id_atencion=<?php echo $f['id_atencion']; ?>&id_exp=<?php echo $f['Id_exp'] ?>"><button type="button" class="btn btn-block"><img src="https://img.icons8.com/fluency/48/000000/documents.png"/></button></a></center></td> 
                                
                                <td> <center>
                                    <a type="submit" class="btn btn-danger btn-sm"
                                    href="consent_lista.php?id_atencion=<?php echo $f['id_atencion']; ?>&id_exp=<?php echo $f['Id_exp'] ?>"
                                    target=""><span class="fa fa-file-pdf-o"
                                    style="font-size:20px"></span></a>
                                </center></td>
                               
                                 
                                <td><strong><?php echo $f['Id_exp']; ?></strong></td>
                                <td><strong><?php echo $f['id_atencion']; ?></strong></td>
                                <td><strong><?php echo date_format($fec_ing,'d/m/Y H:i a'); ?></strong></td>
                                <td><strong><?php echo $fec_egreso?></strong></td>
                                <td><strong><?php echo $f['papell'].' '.$f['sapell'].' '.$f['nom_pac']; ?></strong></td>
                                <td><strong><?php echo $f['edad']; ?></strong></td>
                                <td><strong><?php $date = date_create($f[5]); echo date_format($date, "d/m/Y"); ?></strong></td>
                                <td><strong><?php echo $f['area']; ?></strong></td>
                                <td><strong><?php echo $f['aseg']; ?></strong></td>
                                 
                            </tr>
                        <?php }
                        
                        ?>
                        </tbody>
                    </table>
                    </div>

                </div>
            </div>
        </div>
        

       
    </div>
    <footer class="main-footer">
        <?php
        include("../../template/footer.php");
        ?>
    </footer>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>

<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
<script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/main.js"></script>
<script>
    document.oncontextmenu = function () {
        return false;
    }
</script>
<script src="js_busc/search.js"></script>
    </body>
    </html>
