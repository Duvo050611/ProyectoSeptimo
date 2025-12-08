    <?php
    session_start();
    include "../../conexionbd.php";
    $conexion = ConexionBD::getInstancia()->getConexion();
    include "../header_enfermera.php";
   
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


    </head>
      <?php if ($usuario['id_rol'] == 5) { ?>
    
        <div class="container">
            <div class="row">
                  <div class="col col-12">
                    <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
                    <br>
                   <center>
                        
                        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                                <tr><strong><center>PACIENTES REGISTRADOS</center></strong>
                        </div>
                        <hr>
                    </center>
                
                    
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
                            <th scope="col">SELECCIONAR</th>
                            <th scope="col">CAMA ALTA.</th>
                            <th scope="col">EXP.</th>
                            <th scope="col">NOMBRE</th>
                            <th scope="col">EDAD</th>
                            <th scope="col">FECHA DE INGRESO</th>
                            <th scope="col">DIAGNÓSTICO</th>
                            <th scope="col">MEDICO TRATANTE</th>
                            <th scope="col">FECHA DE EGRESO</th>
                        </tr>
                        </thead>
                        <tbody>


<?php 

?>

                        <?php
                         $resultado = $conexion->query("SELECT p.*, p.papell as papell_pac , p.nom_pac, p.sapell as sapell_pac, d.*, d.fecha as fechaing, r.*, r.papell as papell_doc, r.sapell as sapell_doc from paciente p, dat_ingreso d, reg_usuarios r WHERE d.Id_exp=p.Id_exp and d.cama=0 and d.id_usua=r.id_usua order by p.Id_exp DESC") or die($conexion->error);

                        while ($f = mysqli_fetch_array($resultado)) {

//date_default_timezone_set('America/Guatemala');
$fecha_quir = date("d-m-Y H:i a");

                            ?>

                            <tr>
                                <td>
                                    <center>

                                        <a href="../lista_pacientes/select_pac.php?id_atencion=<?php echo $f['id_atencion']; ?>"><button type="button" class="btn btn-primary "><i class="fa fa-hand-o-up" aria-hidden="true"></i></button></a></center></td>
                                         <td><font size="2"><strong><?php echo $f['cama_alta']; ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['Id_exp']; ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['papell_pac'].' '.$f['sapell_pac'].' '.$f['nom_pac']; ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['edad']; ?></strong></font></td>
                                <td><font size="2"><strong><?php $date = date_create($f['fechaing']); echo date_format($date, "d/m/Y"); ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['motivo_atn'] ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['papell_doc']; ?></strong></font></td>
                                <td><font size="2"><strong><?php $date = date_create($f['fec_egreso']); echo date_format($date, "d/m/Y"); ?></strong></font></td>
                            </tr>
                            <?php
                            
                        }

                        ?>
                        </tbody>
                    </table>
                    </div>

                </div>
            </div>
        </div>
       <?php  } else  if ($usuario['id_rol'] == 3) { ?>
       <div class="container">
            <div class="row">
                  <div class="col col-12">
                    <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
                    <br>
                   <center>
                        
                        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                                <tr><strong><center>PACIENTES REGISTRADOS</center></strong>
                        </div>
                        <hr>
                    </center>
                
                    
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
                            <th scope="col">SELECCIONAR</th>
                                 <th scope="col">CAMA ALTA.</th>
                            <th scope="col">EXP.</th>
                            <th scope="col">NOMBRE</th>
                            <th scope="col">EDAD</th>
                            <th scope="col">FECHA DE INGRESO</th>
                            <th scope="col">DIAGNÓSTICO</th>
                            <th scope="col">MEDICO TRATANTE</th>
                            <th scope="col">FECHA DE EGRESO</th>
                        </tr>
                        </thead>
                        <tbody>


<?php 

?>

                        <?php
                         $resultado = $conexion->query("SELECT p.*,p.papell as papell_pac,p.sapell as sapell_pac ,p.nom_pac,  d.*, d.fecha as fechaing, r.*, r.papell as papell_doc, r.sapell as sapell_doc from paciente p, dat_ingreso d, reg_usuarios r WHERE d.Id_exp=p.Id_exp and d.cama=0 and d.id_usua=r.id_usua order by p.Id_exp DESC") or die($conexion->error);

                        while ($f = mysqli_fetch_array($resultado)) {

//date_default_timezone_set('America/Guatemala');
$fecha_quir = date("d-m-Y H:i:s");

$fecha1 = new DateTime($f['fec_egreso']);//fecha inicial
$fecha2 = new DateTime($fecha_quir);//fecha de cierre

$intervalo = $fecha1->diff($fecha2);

  $intervalo->format('%d %m %h');//00 años 0 meses 0 días 08 horas 0 minutos 0 segundos
 

  $hor=$intervalo->format('%h');
   $dd=$intervalo->format('%d');
   $mm=$intervalo->format('%m');
 if($dd==0 and $mm==0 and $hor<=24 and $f['fec_egreso']!=null){
 
                            ?>

                            <tr>
                                <td>
                                    <center>

                                <a href="../lista_pacientes/select_pac.php?id_atencion=<?php echo $f['id_atencion']; ?>"><button type="button" class="btn btn-primary "><i class="fa fa-hand-o-up" aria-hidden="true"></i></button></a></center></td>
                                <td><font size="2"><strong><?php echo $f['cama_alta']; ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['Id_exp']; ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['papell_pac'].' '.$f['sapell_pac'].' '.$f['nom_pac']; ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['edad']; ?></strong></font></td>
                                <td><font size="2"><strong><?php $date = date_create($f['fechaing']); echo date_format($date, "d/m/Y"); ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['motivo_atn'] ?></strong></font></td>
                                <td><font size="2"><strong><?php echo $f['papell_doc'].' '.$f['sapell_doc']; ?></strong></font></td>
                                <td><font size="2"><strong><?php $date = date_create($f['fec_egreso']); echo date_format($date, "d/m/Y"); ?></strong></font></td>
                            </tr>
                            <?php
                        } 
                            
                        }

                        ?>
                        </tbody>
                    </table>
                    </div>

                </div>
            </div>
        </div>
       
       <?php }?>

       
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
    </body>
    </html>
