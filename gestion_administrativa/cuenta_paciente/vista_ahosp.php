<?php
session_start();
include "../../conexionbd.php";
include("../../gestion_administrativa/header_administrador.php");


$resultado = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE dat_ingreso.area='HOSPITALIZACION' && dat_ingreso.activo='SI' && dat_ingreso.cama='0'") or die($conexion->error);
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

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>



    <title>Asignar habitacion </title>
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

    .btn-primary {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
        color: #ffffff !important;
        border-color: #40E0FF !important;
    }

    .btn-primary:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 25px rgba(64, 224, 255, 0.4) !important;
        background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
        border-color: #00D9FF !important;
        color: #40E0FF !important;
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

    /* Botón de cama especial */
    .table tbody td .btn-danger {
        background: linear-gradient(135deg, #c2185b 0%, #e91e63 100%) !important;
        border-radius: 10px !important;
        padding: 10px 20px !important;
        transition: all 0.3s ease;
    }

    .table tbody td .btn-danger:hover {
        transform: scale(1.1) rotate(5deg) !important;
        box-shadow: 0 5px 20px rgba(244, 143, 177, 0.5) !important;
    }

    .table tbody td .btn-danger i {
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .table tbody td .btn-danger:hover i {
        transform: scale(1.2);
    }

    /* Iconos dentro de botones */
    .btn i {
        transition: transform 0.3s ease;
        margin-right: 8px;
    }

    .btn:hover i {
        transform: scale(1.2);
    }

    /* Centrado de texto */
    .text-center {
        text-align: center !important;
    }

    /* Estilo para letras */
    #letra {
        font-weight: 600;
        color: #ffffff;
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

    .btn.col-md-5 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 200px;
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

        .btn.col-md-5 {
            min-width: 180px;
            font-size: 0.8rem !important;
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

        .col-6 {
            text-align: center !important;
            margin-bottom: 10px;
        }

        .btn.col-md-5 {
            min-width: 160px;
            font-size: 0.75rem !important;
            padding: 8px 15px !important;
        }

        #letra {
            font-size: 14px;
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

        .table tbody td .btn-danger {
            padding: 6px 12px !important;
            font-size: 11px !important;
        }

        .table tbody td .btn-danger i {
            font-size: 14px;
        }
    }

    /* Estilos para alineación de contenido */
    center {
        text-align: center !important;
    }

    /* Asegurar que no haya overflow horizontal */
    .content-wrapper,
    .container-fluid,
    .container {
        overflow-x: hidden;
    }
</style>
</head>


<body>
    <div class="container">
        <div class="row">


            <div class="col col-12">
               
                    <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
                <div class="text-center">
                </div>
               
                    <a href="../gestion_pacientes/registro_pac.php" class="btn btn-danger btn-sm">Regresar</a><hr>            
                
                <div class="row">
                    <div class="col-6"><a href="dispo_camas_ahosp.php"><button type="button" class="btn btn-primary col-md-5" data-target="#exampleModal">
                                <i class="fa fa-plus"></i>
                                <font id="letra">Ver disponibilidad</font>
                            </button>
                    </div>
                </div>

                <br>
                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                     <tr><strong><center>LISTA DE PACIENTES SIN HABITACIÓN ASIGNADA</center></strong>
                </div>
               
                    <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
              
                

                <table class="table table-responsive table-hover">
                    <thead class="thead">
                        <tr>
                            <th scope="col">HABITACIÓN</th>
                            <th scope="col">EXP.</th>
                            <th scope="col">NOMBRE(S)</th>
                            <th scope="col">FEC. NAC.</th>
                            <th scope="col">EDAD</th>
                            <th scope="col">MOTIVO DE ATENCIÓN</th>
                            <th scope="col">FECHA DE INGRESO</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        while ($f = mysqli_fetch_array($resultado)) {

                        ?>

                            <tr>
                                <td scope="row" id="letra" align="center"><a href="../cuenta_paciente/dispo_camas.php?id_atencion=<?php echo $f['id_atencion']; ?>"><strong> <button type="button" class="btn btn-danger"> <i class="fa fa-bed" aria-hidden="true"></i> </button></td>
                                <td><?php echo $f['Id_exp']; ?></td>
                                <td><?php echo $f['papell'].' '.$f['sapell'].' '.$f['nom_pac']; ?></td>
                                <td><?php $date = date_create($f[5]); echo date_format($date, "d/m/Y"); ?></strong></td>
                                <td> <center><?php echo $f['edad']; ?></center></td>
                                <td><center><?php echo $f['motivo_atn']; ?></center></td>
                                <td> <center><?php $date = date_create($f['fecha']);echo date_format($date, "d/m/Y"); ?></center></td>
                            </tr>
                        <?php
                        }

                        ?>
                       
                    </tbody>
                </table>


                <br>
                <br>

            </div>
        </div>
    </div>




    <script>
        document.querySelector('#id_estado').addEventListener('change', event => {
            fetch('municipios.php?id_estado=' + event.target.value)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Hubo un error en la respuesta');
                    } //en if
                    return res.json();
                })
                .then(datos => {
                    let html = '<option value="">Seleccionar municipio</option>';
                    if (datos.data.length > 0) {
                        for (let i = 0; i < datos.data.length; i++) {
                            html += `<option value="${datos.data[i].id}">${datos.data[i].nombre}</option>`;
                        } //end for
                    } //end if
                    document.querySelector('#municipios').innerHTML = html;
                })
                .catch(error => {
                    console.error('Ocurrió un error ' + error);
                });
        });
    </script>





    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            var idEliminar = -1;
            var idEditar = -1;
            var fila;
            $(".btnEliminar").click(function() {
                Id_expEliminar = $(this).data('Id_exp');
                fila = $(this).parent('td').parent('tr');
            });
            $(".eliminar").click(function() {
                $.ajax({
                    url: 'eliminar_paciente.php',
                    method: 'POST',
                    data: {
                        id: idEliminar
                    }
                }).done(function(res) {
                    $(fila).fadeOut();
                });
            });
            $(".btnEditar").click(function() {
                idEditar = $(this).data('Id_exp');
                var curp = $(this).data('curp');
                var papell = $(this).data('papell');
                var sapell = $(this).data('sapell');
                var nombre = $(this).data('nombre');
                var fecnac = $(this).data('fecnac');
                var edonac = $(this).data('edonac');
                var sexo = $(this).data('sexo');
                var nacorigen = $(this).data('nacorigen');
                var edo = $(this).data('edo');
                var mun = $(this).data('mun');
                var loc = $(this).data('loc');
                var dir = $(this).data('dir');
                var ocup = $(this).data('ocup');
                var tel = $(this).data('tel');
                $("#curpEdit").val(curp);
                $("#papellEdit").val(papell);
                $("#sapellEdit").val(sapell);
                $("#nombreEdit").val(nombre);
                $("#fecnacEdit").val(fecnac);
                $("#edonacEdit").val(edonac);
                $("#sexoEdit").val(sexo);
                $("#nacorigenEdit").val(nacorigen);
                $("#edoEdit").val(edo);
                $("#munEdit").val(mun);
                $("#locEdit").val(loc);
                $("#dirEdit").val(dir);
                $("#ocupEdit").val(ocup);
                $("#telEdit").val(tel);
                $("#Id_expEdit").val(Id_expEditar);

            });
        });
        document.oncontextmenu = function() {
            return false;
        }
    </script>

</body>

</html>