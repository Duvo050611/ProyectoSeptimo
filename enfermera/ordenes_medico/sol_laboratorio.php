<?php
session_start();
include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
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

  <!---
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
-->
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
  <title>indicaciones del médico</title>
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

</style>
 <style>
    td.fondo {
      background-color: red !important;
    }
  </style>
  <style>
    td.fondo2 {
      background-color: green !important;
    }
  </style>
  
</head>

<body>
    
    
  <section class="content container-fluid">
     
     <a type="submit" class="btn btn-primary" href="vista_ordenes.php">Regresar</a>
  

    <?php
    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];
}

    ?>   
        <section class="content">
            <br>
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>SOLICITUDES DE LABORATORIO PENDIENTES DE REALIZAR</center></strong>
        </div><br>
        <section class="content container-fluid">
                <div class="content box">
                    <!-- CONTENIDOO -->

                    <div class="form-group">
                        <input type="text" class="form-control pull-right" style="width:20%" id="search"
                               placeholder="Buscar...">
                    </div>
                    <br>

                    <div class="table-responsive">
                        <!--<table id="myTable" class="table table-striped table-hover">-->

                        <table class="table table-bordered table-striped" id="mytable">

                            <thead class="thead" style="background-color: #2b2d7f; color:white;">
                            <tr>
                                <th><font color="white">Habitación</th>
                                <th><font color="white">Paciente</th>
                                <th><font color="white">Fecha de solicitud</th>
                                <th><font color="white">Solicitante</th>
                                <th><font color="white">Estudio(s)</th>

                                <th><font color="white">Eliminar</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                           $query = " SELECT * FROM notificaciones_labo n, reg_usuarios u where 
                                n.realizado = 'NO' and n.id_usua = u.id_usua AND
                                id_atencion = $id_atencion and activo = 'SI' order by fecha_ord DESC ";
                            $result = $conexion->query($query);
                            $no = 1;

                            while ($row = $result->fetch_assoc()) {

                                $habi = $row['habitacion'];
                                $id_atencion = $row['id_atencion'];
                                $es=$row['sol_estudios'].'/ '.$row['det_labo'] ;
                                $fechasol = date_create($row['fecha_ord']);
                    $sql_dat_ingi = "SELECT * from cat_servicios where serv_desc='$es'";
                    $result_dat_inga = $conexion->query($sql_dat_ingi);

                     while ($row_dat_ingu = $result_dat_inga->fetch_assoc()) {
                    //$desc = $row_dat_ingu['serv_desc'];
                    $tipins = $row_dat_ingu['tip_insumo'];
  
                    }


                            if ($habi <> 0)  {
                                $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                                $result_pac = $conexion->query($query_pac);

                                while ($row_pac = $result_pac->fetch_assoc()) {
                                    $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                }
                                $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                                $result_pac = $conexion->query($query_pac);

                                echo '<tr>'
                                    . '<td>' . $row['habitacion'] . '</td>'
                                    . '<td > ' . $pac . '</td>'
                                    . '<td >' . date_format($fechasol,'d/m/Y H:i') . '</td>'
                                    . '<td >' . $row['papell'] . ' ' . $row['sapell'] . '</td>'
                                    . '<td >' . $es. '</a></td>'
  
                                 . '<td class="" style="color:white;"><center>'
                                    . ' <a href="desactivar_labo.php?not_id=' . $row['not_id'] . '&id_usua=' . $row['id_usua'] . '" title="Subir Qr" class="btn btn-danger">X</i></a>'      
                                 ;

                                echo '</center></td></tr>';
                                $no++;
                            
                             } else  {
                                $query_rec = "SELECT * FROM receta_ambulatoria where id_rec_amb = $id_atencion";
                                $result_rec = $conexion->query($query_rec);

                                while ($row_rec = $result_rec->fetch_assoc()) {
                                    $pac = $row_rec['papell_rec'] . ' ' . $row_rec['sapell_rec'] . ' ' . $row_rec['nombre_rec'];
                                    $habitacion = "C.EXT";
                                }
                                echo '<tr>'

                                    . '<td >' . $row['id_atencion'] . ' ' . $pac . '</td>'
                                    . '<td >' . $habitacion . '</td>'
                                    . '<td >' . $row['fecha_ord'] . '</td>'
                                    . '<td >' . $row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nombre'] . '</td>'
                                    . '<td >' . $row['sol_estudios'] . '</a></td>'
                                    . '<td >' . $row['realizado'] . '</td>';

                                echo '<td ><center>'
                                    . ' <a href="../sauxiliares/Imagenologia/subir_resultado.php?not_id=' . $row['not_id'] . '" title="Editar datos" class="btn btn-success "><span class="fa fa-cloud-upload" aria-hidden="true"></span></a>';
                                echo '</center></td></tr>';
                                $no++;
                             } }
                            ?>
                            </tbody>
                        </table>
                    </div>


                </div>

            </section><!-- /.content -->

   

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