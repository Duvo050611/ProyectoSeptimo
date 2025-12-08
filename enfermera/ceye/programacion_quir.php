<?php
session_start();

include "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];

include "../header_enfermera.php";

?>
<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1″/>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!--js CALENDAR-->
    <script src="js/jquery.min.js"></script>
    <script src="js/moment.min.js"></script>

    <!--full CALENDAR-->
    <link rel="stylesheet" href="css/fullcalendar.min.css">
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/es.js"></script> <!--Idioma español Fullcalendar-->

    <!--relog-->
    <script src="js/bootstrap-clockpicker.js"></script>
    <link rel="stylesheet" href="css/bootstrap-clockpicker.css">

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

    <style>
        /* Estilos Cyberpunk */
        body {
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a2e 100%);
            color: #00ffff;
        }

        /* Encabezado principal */
        .thead {
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a3e 50%, #0f0f23 100%) !important;
            color: #00ffff !important;
            text-shadow: 0 0 10px #00ffff, 0 0 20px #00ffff;
            border: 2px solid #00ffff;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
            padding: 15px;
            border-radius: 10px;
        }

        /* Botones */
        .btn-danger {
            background: linear-gradient(135deg, #8b0000 0%, #ff0055 100%);
            border: 2px solid #ff0055;
            box-shadow: 0 0 15px rgba(255, 0, 85, 0.5);
            color: #fff;
            text-shadow: 0 0 5px #fff;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #ff0055 0%, #ff3377 100%);
            box-shadow: 0 0 25px rgba(255, 0, 85, 0.8);
            transform: scale(1.05);
        }

        .btn-success {
            background: linear-gradient(135deg, #00ff88 0%, #00cc66 100%);
            border: 2px solid #00ff88;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.5);
            color: #000;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #00ffaa 0%, #00ff88 100%);
            box-shadow: 0 0 25px rgba(0, 255, 136, 0.8);
            transform: scale(1.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, #0066ff 0%, #0099ff 100%);
            border: 2px solid #00ffff;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);
            color: #fff;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0099ff 0%, #00ccff 100%);
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.8);
            transform: scale(1.05);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #333 0%, #666 100%);
            border: 2px solid #888;
            box-shadow: 0 0 10px rgba(136, 136, 136, 0.5);
        }

        /* Lista de leyenda */
        .list-group-item {
            background: linear-gradient(90deg, #0d0d1f 0%, #1a1a2e 100%);
            border: 1px solid rgba(0, 255, 255, 0.3);
            color: #00ffff;
            margin-bottom: 5px;
        }

        .list-group-item:hover {
            background: linear-gradient(90deg, #16213e 0%, #1f4068 100%);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.4);
        }

        .fw-bold {
            color: #ff00ff;
            text-shadow: 0 0 5px #ff00ff;
        }

        .badge.bg-light {
            background: linear-gradient(135deg, #00ffff 0%, #00ccff 100%) !important;
            color: #000;
            font-weight: bold;
        }

        /* Calendario */
        #CalendarioWeb {
            background: #0a0a1a;
            border: 2px solid #00ffff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.3);
        }

        .fc-toolbar h2 {
            color: #ff00ff !important;
            text-shadow: 0 0 10px #ff00ff;
        }

        .fc-button {
            background: linear-gradient(135deg, #0066ff 0%, #0099ff 100%) !important;
            border: 2px solid #00ffff !important;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.5) !important;
        }

        .fc-button:hover {
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.8) !important;
        }

        .fc-day-grid {
            background: #0f0f1f;
        }

        .fc-day {
            border-color: rgba(0, 255, 255, 0.2) !important;
        }

        .fc-day-number {
            color: #00ffff !important;
        }

        .fc-event {
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 10px rgba(255, 0, 255, 0.5);
        }

        /* Modal */
        .modal-content {
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a2e 100%);
            border: 2px solid #00ffff;
            box-shadow: 0 0 40px rgba(0, 255, 255, 0.5);
            color: #00ffff;
        }

        .modal-header {
            border-bottom: 2px solid #ff00ff;
            background: linear-gradient(90deg, #0f0f23 0%, #1a1a3e 100%);
        }

        .modal-title {
            color: #ff00ff !important;
            text-shadow: 0 0 10px #ff00ff;
            background: transparent !important;
            border: none !important;
        }

        .modal-footer {
            border-top: 2px solid #ff00ff;
            background: linear-gradient(90deg, #0f0f23 0%, #1a1a3e 100%);
        }

        /* Inputs y selects */
        .form-control {
            background: #0a0a1a;
            border: 2px solid #00ffff;
            color: #00ffff;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: #0f0f1f;
            border-color: #ff00ff;
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.5);
            color: #ff00ff;
        }

        .form-control:disabled {
            background: #1a1a2e;
            border-color: #555;
            color: #888;
        }

        /* Labels */
        label, strong {
            color: #00ffff;
            text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
        }

        /* Select picker */
        .bootstrap-select .dropdown-toggle {
            background: #0a0a1a !important;
            border: 2px solid #00ffff !important;
            color: #00ffff !important;
        }

        .bootstrap-select .dropdown-menu {
            background: #0a0a1a;
            border: 2px solid #00ffff;
        }

        .bootstrap-select .dropdown-menu li a {
            color: #00ffff;
        }

        .bootstrap-select .dropdown-menu li a:hover {
            background: #1a1a3e;
            color: #ff00ff;
        }

        /* Container */
        .container {
            background: transparent;
        }

        hr {
            border-color: rgba(0, 255, 255, 0.5);
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }

        /* Texto requerido */
        font[color="red"] {
            color: #ff0055 !important;
            text-shadow: 0 0 5px #ff0055;
        }
    </style>

</head>

<body>
<?php
if ($usuario['id_rol'] == 4) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <a type="submit" class="btn btn-danger btn-sm" href="../../template/menu_sauxiliares.php">Regresar</a>
            </div>
        </div>
    </div>

    <?php
} else if ($usuario['id_rol'] == 8) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <a type="submit" class="btn btn-danger btn-sm" href="../../template/menu_ceye.php">Regresar</a>
            </div>
        </div>
    </div>

    <?php
} else if ($usuario['id_rol'] == 5) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <a type="submit" class="btn btn-danger btn-sm" href="../../template/menu_enfermera.php">Regresar</a>
            </div>
        </div>
    </div>

    <?php
} else if ($usuario['id_rol'] == 12) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <a type="submit" class="btn btn-danger btn-sm" href="../../template/menu_residente.php">Regresar</a>
            </div>
        </div>
    </div>

    <?php
} else if ($usuario['id_rol'] == 3) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <a type="submit" class="btn btn-danger btn-sm" href="../../template/menu_enfermera.php">Regresar</a>
            </div>
        </div>
    </div>

    <?php
} else if ($usuario['id_rol'] == 2) {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <a type="submit" class="btn btn-danger btn-sm" href="../../template/menu_medico.php">Regresar</a>
            </div>
        </div>
    </div>

    <?php
}
?>

<br>
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
    <tr><strong><center>PROGRAMACIÓN QUIRÚRGICA</center></strong></tr>
</div>
<hr>

<!--calendario-->
<div class="container">
    <div class="row">
        <div class="col-sm">
            <ol class="list-group list-group-numbered">
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <center><span class="badge bg-light rounded-pill">Leyenda de agenda</span></center>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">Pendiente </div>
                    </div>
                    <span class="badge bg-danger rounded-pill" style="font-size:40px;"> </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">Cancelado</div>
                    </div>
                    <span class="badge bg-secondary rounded-pill" style="font-size:40px;"> </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">Quirófano 1</div>
                    </div>
                    <span class="badge bg-primary rounded-pill" style="font-size:40px;"> </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">Quirófano 2</div>
                    </div>
                    <span class="badge bg-success rounded-pill" style="font-size:40px;"> </span>
                </li>
            </ol>
        </div>
        <div class="col-sm-7"><div id="CalendarioWeb"></div></div>
        <div class="col-sm"></div>
    </div>
</div>
<!-- fin calendario-->
<br>

<script>
    $(document).ready(function(){
        $('#CalendarioWeb').fullCalendar({

            header:{
                left:'month,agendaWeek,agendaDay',
                center:'title',
                rigth:'today,prev,next'
            },
            dayClick:function(date,jsEvent,view){
                $('#btnAgregar').prop("hidden",false);
                $('#btnModificar').prop("hidden",true);
                $('#btnEliminar').prop("hidden",true);
                $('#btnCancelar').prop("hidden",true);
                $('#btnReprogramar').prop("hidden",true);
                limpiarFormulario();
                $('#txtFecha').val(date.format());
                $('#txtfechaend').val(date.format());


                $("#ModalEventos").modal();

            },

            events:'eventos.php',

            eventClick:function(calEvent,jsEvent,view){
                $('#btnAgregar').prop("hidden",true);
                $('#btnModificar').prop("hidden",false);
                $('#btnEliminar').prop("hidden",false);
                $('#btnCancelar').prop("hidden",false);
                $('#btnReprogramar').prop("hidden",false);

//h5
                $('#tituloEvento').html(calEvent.title);
//info del evento en inputs
                $('#txtDescripcion').val(calEvent.descripcion);
                $('#txtID').val(calEvent.id);
                $('#txtTitulo').val(calEvent.title);
                $('#txtColor').val(calEvent.color);
                $('#txtTipo').val(calEvent.tipo);
                $('#txtMedico').val(calEvent.medico);
                $('#txtEnf').val(calEvent.enfermera);
                $('#txtquirofano').val(calEvent.quirofano);
                $('#txtdur').val(calEvent.duracion);
                $('#txtMotivo').val(calEvent.motivo);
                $('#txtEstatus').val(calEvent.estatus);



                FechaHora=calEvent.start._i.split(" ");
                $('#txtFecha').val(FechaHora[0]);
                $('#txtHora').val(FechaHora[1]);


                Fechahoraend=calEvent.end._i.split(" ");
                $('#txtfechaend').val(Fechahoraend[0]);
                $('#txthoraend').val(Fechahoraend[1]);



                $("#ModalEventos").modal();
            },

            eventDrop:function(calEvent){
                $('#txtID').val(calEvent.id);
                $('#txtTitulo').val(calEvent.title);
                $('#txtColor').val(calEvent.color);
                $('#txtDescripcion').val(calEvent.descripcion);
                $('#txtTipo').val(calEvent.tipo);
                $('#txtMedico').val(calEvent.medico);
                $('#txtEnf').val(calEvent.enfermera);
                $('#txtquirofano').val(calEvent.quirofano);
                $('#txtdur').val(calEvent.duracion);
                $('#txtMotivo').val(calEvent.motivo);
                $('#txtEstatus').val(calEvent.estatus);

                var fechaHora=calEvent.start.format().split("T");
                $('#txtFecha').val(fechaHora[0]);
                $('#txtHora').val(fechaHora[1]);

                var fechahoraend=calEvent.end.format().split("T");
                $('#txtfechaend').val(fechahoraend[0]);
                $('#txthoraend').val(fechahoraend[1]);



                RecolectarDatosGUI();
                EnviarInformacion('modificar',NuevoEvento,true);

                RecolectarDatosGUI();
                EnviarInformacion('reprogramar',NuevoEvento,true);
            }


        });

    });
</script>


<!-- Modal(AGREGAR, MODIFICAR ELIMINAR) -->
<div class="modal fade bd-example-modal-lg" id="ModalEventos" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title alert alert-primary" id="tituloEvento" role="alert"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <center><input type="hidden" name="txtID" id="txtID" class="form-control"></center>
                <div class="container">
                    <div class="row">

                        <div class="col-sm-4">
                            <strong>Fecha inicio Cx:</strong><input type="date" name="txtFecha" id="txtFecha" class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <strong>Hora Inicio Cx:</strong>
                            <div class="input-group">
                                <input type="time" value="" name="txtHora" id="txtHora" class="form-control">
                            </div>


                        </div>
                        <?php if ($usuario['id_rol'] == 2) { ?>
                            <div class="col-sm-5">
                                <strong>Quirófano <font color="red">*</font></strong><select class="form-control" id="txtquirofano" name="txtquirofano" disabled>
                                    <option value="">Seleccionar Quirófano</option>
                                    <option value="Quirofano 1">Quirófano 1</option>
                                    <option value="Quirofano 2">Quirófano 2</option>

                                </select>
                            </div>
                        <?php }else {?>
                            <div class="col-sm-5">
                                <strong>Quirófano <font color="red">*</font></strong><select class="form-control" id="txtquirofano" name="txtquirofano">
                                    <option value="">Seleccionar Quirófano</option>
                                    <option value="Quirofano 1">Quirófano 1</option>
                                    <option value="Quirofano 2">Quirófano 2</option>

                                </select>
                            </div>
                        <?php }

                        ?>


                    </div>
                </div>
                <p>
                <div class="container">
                    <div class="row">

                        <div class="col-sm-12">
                            <strong>Nombre del paciente:</strong><input type="text" name="txtTitulo" id="txtTitulo" class="form-control">
                        </div>

                    </div>
                </div>
                <p>


                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Cirugía programada:</strong><input type="text" id="txtTipo" name="txtTipo" class="form-control">
                        </div>


                        <?php if($usuario['id_rol']==2){
                            $sqlR = "SELECT * FROM reg_usuarios where id_usua='".$usuario["id_usua"]."' ORDER BY papell ASC";
                            $resultR = $conexion->query($sqlR);
                            while ($row_R = $resultR->fetch_assoc()) {

                                $MED=$row_R['papell'];
                            }
                            ?>
                            <div class="col-sm">
                                <strong>Nombre del médico cirujano:</strong>
                                <select data-live-search="true" class="selectpicker form-control" name="txtMedico" id="txtMedico" style="width : 100%; heigth : 100%" required="">
                                    <?php

                                    echo"<option value=''>Seleccionar médico</option>";


                                    echo "<option value='" . $MED . "'>" . $MED . "</option>";

                                    ?></select>
                            </div>

                        <?php }else{ ?>

                            <div class="col-sm">
                                <strong>Nombre del médico cirujano:</strong>
                                <select data-live-search="true" class="selectpicker form-control" name="txtMedico" id="txtMedico" style="width : 100%; heigth : 100%" required="">
                                    <?php
                                    $sql = "SELECT * FROM reg_usuarios where id_rol='12' and u_activo='SI' or id_rol='2' and u_activo='SI' ORDER BY papell ASC";
                                    $result = $conexion->query($sql);
                                    echo"<option value=''>Seleccionar médico</option>";
                                    while ($row_datos = $result->fetch_assoc()) {

                                        echo "<option value='" . $row_datos['papell'] . "'>" . $row_datos['papell'] . "</option>";
                                    }
                                    ?></select>
                            </div>

                        <?php } ?>




                    </div>
                </div>
                <p>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <strong>Enfermera que programa:</strong><input type="text" id="txtEnf" name="txtEnf" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <strong>Duración aproximada:</strong><input type="text" id="txtdur" name="txtdur" class="form-control">
                        </div>
                    </div>
                </div>
                <p>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="txtfechaend"><strong>Fecha termino Cx:</strong></label>
                                <input type="date" name="txtfechaend" class="form-control" id="txtfechaend">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="txthoraend"><strong>Hora termino Cx: <font color="red">*</font></strong></label>
                                <input type="time" name="txthoraend" class="form-control" id="txthoraend" required>
                            </div>
                            <?php if($usuario['id_rol'] == 2) { ?>

                                <input type="hidden" class="form-control" name="txtEstatus" id="txtEstatus"value="Pendiente">

                            <?php }else{ ?>

                                <input type="hidden" name="txtEstatus" value="Activa" id="txtEstatus" class="form-control">

                            <?php } ?>
                        </div>


                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>Observaciones (Material, equipo de laparoscopia, fluoroscopio, etc.):</strong><textarea id="txtDescripcion" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <p>
                    <?php
                    // Inicializar la variable $estatus1
                    $estatus1 = '';

                    $resultado2 = $conexion->query("SELECT * from agenda") or die($conexion->error);

                    while ($row = $resultado2->fetch_assoc()) {
                        $estatus1 = $row['estatus'];
                    }
                    ?>

                    <?php
                    if ($estatus1 == "Cancelada" || $estatus1 == "Reprogramada"){
                    ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <strong>Motivo de reprogramación:</strong><textarea id="txtMotivo" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <hr>
            </div>
            <div class="modal-footer">

                <button type="button" id="btnAgregar" class="btn btn-success btn-sm">Programar</button>

                <button type="submit" id="btnReprogramar" class="btn btn-primary btn-sm">Reprogramar</button>

                <button type="submit" id="btnModificar" class="btn btn-primary btn-sm">Programar</button>

                <button type="button" id="btnEliminar" class="btn btn-danger btn-sm">Borrar</button>
                <button type="button" id="btnCancelar" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Regresar</button>

            </div>
        </div>
    </div>
</div>

</div>

<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<script>
var NuevoEvento;

$('#btnAgregar').click(function(){
     var txtHora = $('#txtHora').val();
     
            var txthoraend = $('#txthoraend').val();
            if (txthoraend === "" || txthoraend==txtHora) {
               event.preventDefault();
               if(txthoraend==txtHora){
                      var error2 = '<span style="color: red;">¡Por favor ingresa la hora final diferente a la inicial!</span>';
                  $('#txthoraend').after($(error2).fadeOut(2000));
                  $('#txthoraend').css( "border-color","red");
               }else if(txthoraend === ""){
                  var error = '<span style="color: red;">¡Por favor ingresa la hora final!</span>';
                  $('#txthoraend').after($(error).fadeOut(2000));
                  $('#txthoraend').css( "border-color","red");
               }
            }else{
     $('#txthoraend').css( "border-color","gray");
RecolectarDatosGUI();
EnviarInformacion('agregar',NuevoEvento);
}
});

$('#btnEliminar').click(function(){
RecolectarDatosGUI();
EnviarInformacion('eliminar',NuevoEvento);
});

$('#btnModificar').click(function(){
RecolectarDatosGUI();
EnviarInformacion('modificar',NuevoEvento);
});

$('#btnCancelar').click(function(){
RecolectarDatosGUI();
EnviarInformacion('cancelar',NuevoEvento);
});

$('#btnReprogramar').click(function(){
RecolectarDatosGUI();
EnviarInformacion('reprogramar',NuevoEvento);
});



function RecolectarDatosGUI(){


if ($('#txtquirofano').val()=="Quirofano 1") {
NuevoEvento= {
id:$('#txtID').val(),
title:$('#txtTitulo').val(),
start:$('#txtFecha').val()+" "+$('#txtHora').val(),
color:"#056AB0",
descripcion:$('#txtDescripcion').val(),
textColor:"#FFFFFF",
end: $('#txtfechaend').val()+" "+$('#txthoraend').val(),
tipo:$('#txtTipo').val(),
medico:$('#txtMedico').val(),
enfermera:$('#txtEnf').val(),
quirofano:$('#txtquirofano').val(),
duracion:$('#txtdur').val(),
motivo:$('#txtMotivo').val(),
estatus:"Activa"

         }
    }



if ($('#txtquirofano').val()=="Quirofano 2") {
NuevoEvento= {
id:$('#txtID').val(),
title:$('#txtTitulo').val(),
start:$('#txtFecha').val()+" "+$('#txtHora').val(),
color:"#29B005",
descripcion:$('#txtDescripcion').val(),
textColor:"#FFFFFF",
end: $('#txtfechaend').val()+" "+$('#txthoraend').val(),
tipo:$('#txtTipo').val(),
medico:$('#txtMedico').val(),
enfermera:$('#txtEnf').val(),
quirofano:$('#txtquirofano').val(),
duracion:$('#txtdur').val(),
motivo:$('#txtMotivo').val(),
estatus:"Activa"
         }
    }

if ($('#txtquirofano').val()=="Quirofano 3") {
NuevoEvento= {
id:$('#txtID').val(),
title:$('#txtTitulo').val(),
start:$('#txtFecha').val()+" "+$('#txtHora').val(),
color:"#A305B0",
descripcion:$('#txtDescripcion').val(),
textColor:"#FFFFFF",
end:$('#txtfechaend').val()+" "+$('#txthoraend').val(),
tipo:$('#txtTipo').val(),
medico:$('#txtMedico').val(),
enfermera:$('#txtEnf').val(),
quirofano:$('#txtquirofano').val(),
duracion:$('#txtdur').val(),
motivo:$('#txtMotivo').val(),
estatus:"Activa"
         }
    }

    if ($('#txtquirofano').val()=="Quirofano 4") {
NuevoEvento= {
id:$('#txtID').val(),
title:$('#txtTitulo').val(),
start:$('#txtFecha').val()+" "+$('#txtHora').val(),
color:"#E09734",
descripcion:$('#txtDescripcion').val(),
textColor:"#FFFFFF",
end:$('#txtfechaend').val()+" "+$('#txthoraend').val(),
tipo:$('#txtTipo').val(),
medico:$('#txtMedico').val(),
enfermera:$('#txtEnf').val(),
quirofano:$('#txtquirofano').val(),
duracion:$('#txtdur').val(),
motivo:$('#txtMotivo').val(),
estatus:"Activa"
         }
    }
    
    if ($('#txtquirofano').val()=="") {
NuevoEvento= {
id:$('#txtID').val(),
title:$('#txtTitulo').val(),
start:$('#txtFecha').val()+" "+$('#txtHora').val(),
color:"#FF0000",
descripcion:$('#txtDescripcion').val(),
textColor:"#FFFFFF",
end:$('#txtfechaend').val()+" "+$('#txthoraend').val(),
tipo:$('#txtTipo').val(),
medico:$('#txtMedico').val(),
enfermera:$('#txtEnf').val(),
quirofano:$('#txtquirofano').val(),
duracion:$('#txtdur').val(),
motivo:$('#txtMotivo').val(),
estatus:"Pendiente"
         }
    }

}

function EnviarInformacion(accion,ObjEvento,modal){
    $.ajax({
type:'POST',
url:'eventos.php?accion='+accion,
data:ObjEvento,
success:function(msg){
    if(msg){
        $('#CalendarioWeb').fullCalendar('refetchEvents');
        if(!modal){
        $("#ModalEventos").modal('toggle');
        }
        
 }
},
error:function(){
    alert("Hay un error..");
}

    });
}

$('.clockpicker').clockpicker();

function limpiarFormulario(){

    $('#txtID').val('');
    $('#txtTitulo').val('');
    $('#txtColor').val('');
    $('#txtDescripcion').val('');
    $('#txtHora').val('');
    $('#txtFecha').val('');
    $('#txtfechaend').val('');
    $('#txthoraend').val('');
    $('#txtTipo').val('');
    $('#txtMedico').val('');
    $('#txtEnf').val('');
    $('#txtquirofano').val('');
    $('#txtdur').val('');
    $('#txtMotivo').val('');
    $('#txtEstatus').val('');
}

</script>

</body>
</html>