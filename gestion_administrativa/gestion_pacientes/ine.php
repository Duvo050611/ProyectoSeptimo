<?php
session_start();
require "../../estados.php";
include "../../conexionbd.php";
include "../header_administrador.php";
if( isset($_GET['Id_exp'])) { //si existe el id mandado por metodo GET, se hara la consulta donde el id debe ser igual al id mandado por el metodo GET
  $resultado = $conexion ->query("SELECT * FROM paciente WHERE Id_exp=".$_GET['Id_exp'])or die($conexion->error);
  if(mysqli_num_rows($resultado) > 0 ){ //se mostrara si existe mas de 1
    $f=mysqli_fetch_row($resultado);

  }else{
    header("location: registro_pac.php"); //te regresa a la página principal
  }
}else{
  header("location: registro_pac.php"); //te regresa a la página principal
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
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
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function () {
            $("#search").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function () {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>
    <title>AGREGAR INE</title>
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
        padding: 10px 20px !important;
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

    .btn-success {
        background: linear-gradient(135deg, #388e3c 0%, #4caf50 100%) !important;
        color: #ffffff !important;
        border-color: #66bb6a !important;
    }

    .btn-success:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(102, 187, 106, 0.4) !important;
        background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%) !important;
        border-color: #a5d6a7 !important;
    }

    /* Formulario mejorado */
    .form-group {
        margin-bottom: 25px;
    }

    .form-control {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 8px !important;
        padding: 12px 15px !important;
        color: #ffffff !important;
        font-size: 14px;
        box-shadow: 0 2px 10px rgba(64, 224, 255, 0.1) !important;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #00D9FF !important;
        box-shadow: 0 0 0 0.2rem rgba(64, 224, 255, 0.25) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        color: #ffffff !important;
        outline: none;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    .form-control:disabled {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
        border-color: rgba(64, 224, 255, 0.3) !important;
        color: rgba(255, 255, 255, 0.7) !important;
    }

    input[type="file"] {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        border: 2px solid #40E0FF !important;
        border-radius: 8px !important;
        padding: 10px !important;
        color: #ffffff !important;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    input[type="file"]:hover {
        border-color: #00D9FF !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
    }

    input[type="file"]::file-selector-button {
        background: linear-gradient(135deg, #40E0FF 0%, #00D9FF 100%) !important;
        border: none !important;
        border-radius: 6px !important;
        padding: 8px 15px !important;
        color: #ffffff !important;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-right: 10px;
    }

    input[type="file"]::file-selector-button:hover {
        background: linear-gradient(135deg, #00D9FF 0%, #40E0FF 100%) !important;
        transform: translateY(-2px);
    }

    label {
        color: #ffffff !important;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        text-shadow: 0 0 5px rgba(64, 224, 255, 0.5);
    }

    /* Información del paciente */
    .col strong {
        color: #40E0FF !important;
        font-weight: 600;
        text-shadow: 0 0 5px rgba(64, 224, 255, 0.5);
    }

    .col {
        color: #ffffff !important;
        margin-bottom: 15px;
        padding: 15px;
        background: linear-gradient(135deg, rgba(15, 52, 96, 0.3) 0%, rgba(22, 33, 62, 0.3) 100%);
        border-radius: 10px;
        border: 1px solid rgba(64, 224, 255, 0.2);
    }

    h3 {
        color: #40E0FF !important;
        font-weight: 600;
        margin-bottom: 15px;
        text-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
        padding: 10px 0;
        border-bottom: 2px solid rgba(64, 224, 255, 0.3);
    }

    /* Divider mejorado */
    hr {
        border: 0;
        height: 2px;
        background: linear-gradient(135deg, #40E0FF 0%, #00D9FF 100%) !important;
        margin: 30px 0 !important;
        border-radius: 1px;
        box-shadow: 0 0 10px rgba(64, 224, 255, 0.5);
    }

    /* Centrado de texto */
    .text-center {
        text-align: center !important;
    }

    center {
        text-align: center !important;
        margin: 25px 0;
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
            padding: 8px 16px !important;
            font-size: 13px !important;
        }

        .form-control {
            padding: 10px 12px !important;
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

        h3 {
            font-size: 18px;
            text-align: center;
            margin-top: 15px;
        }

        .col-sm-3 h3 {
            margin-bottom: 20px;
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
            margin: 5px;
        }

        .btn-sm {
            padding: 6px 12px !important;
            font-size: 12px !important;
        }

        .form-control {
            font-size: 12px !important;
            padding: 8px 10px !important;
        }

        input[type="file"] {
            padding: 8px !important;
        }

        input[type="file"]::file-selector-button {
            padding: 6px 12px !important;
            font-size: 12px;
        }

        label {
            font-size: 13px;
        }

        h3 {
            font-size: 16px;
        }

        .col {
            font-size: 14px;
            padding: 10px;
        }
    }

    /* Para pantallas muy pequeñas */
    @media screen and (max-width: 400px) {
        .thead {
            font-size: 14px !important;
            padding: 10px 12px !important;
        }

        .btn {
            font-size: 0.7rem !important;
            padding: 6px 10px !important;
        }

        .form-control {
            font-size: 11px !important;
            padding: 6px 8px !important;
        }

        h3 {
            font-size: 14px;
        }

        .col {
            font-size: 12px;
        }
    }

    /* Asegurar que no haya overflow horizontal */
    .content-wrapper,
    .container-fluid,
    .container {
        overflow-x: hidden;
    }

    /* Iconos dentro de botones */
    .btn i {
        transition: transform 0.3s ease;
    }

    .btn:hover i {
        transform: scale(1.2);
    }
</style>
</head> 
<body>
<div class="container">
	 <div class="row">
    <div class="col">
       Expediente: <strong><?php echo $f[0];?></strong> <br>
       Paciente: <strong><?php echo $f[2];?>
      <?php echo $f[3];?>
      <?php echo $f[4];?></strong><br>
      
Fecha de nacimiento:<strong> <?php  $date = date_create($f[5]);
 echo date_format($date,"d/m/Y");?></strong>
    </div>
  
  </div>

    <form action="insertar_ine.php?Id_exp=<?php echo $_GET['Id_exp']; ?>" method="POST" enctype="multipart/form-data">
    	<div class="row">       
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php
                        $Id_exp= $_GET['Id_exp'];
                        ?>
                        <input type="hidden" name="Id_exp" placeholder="EXPEDIENTE" id="Id_exp" class="form-control" value="<?php echo $Id_exp ?>" 
                               disabled>
                    </div>
                </div>
         </div>
         <div>
                        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                 <tr><strong><center>AGREGAR INE</center></strong>
            </div>
           
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3">
                           <h3>PACIENTE:</h3>     
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="img_inef">INE FRONTAL</label>
                                <input type="file" name="img_inef" id="img_inef" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="img_inet">INE TRASERA</label>
                                <input type="file" name="img_inet" id="img_inet" class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                            <h3>RESPONSABLE: </h3>
                        </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="img_inefr">INE FRONTAL</label>
                                <input type="file" name="img_inefr" id="img_inefr" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="img_inet">INE TRASERA</label>
                                <input type="file" name="img_inetr" id="img_inetr" class="form-control">
                            </div>
                        </div>
                    </div>
               <center>
                <hr>
                  <button type="button" class="btn btn-danger btn-sm" onclick="history.back()">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>

                </center>
   
    </form>      
</div>
</div>
<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>
<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>


<script>document.oncontextmenu = function () {
        return false;
    }</script>

</body> 
</html>  