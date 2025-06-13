<?php
session_start();
include "../../conexionbd.php";
include "../../gestion_administrativa/header_administrador.php";
$resultado = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE  dat_ingreso.activo='SI' AND alta_adm = 'NO'") or die($conexion->error);
?>
<!DOCTYPE html>
<html>
<head>
     <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>

    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>
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
<style>
    hr.new4 {
      border: 1px solid red;
    }
  </style>

    <title>Menu Gestión Administrativa </title>
    <link rel="shortcut icon" href="logp.png">
</head>
 <div class="col-sm-2">
            <a type="submit" class="btn btn-danger btn-sm" onclick="history.back()"><font color="white">Regresar</font></a>
        </div>
        <br>
<div class="container">
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                 <tr><strong><center>PRESUPUESTOS</center></strong>
</div>

<?php

    $nombre='PRUEBA';
?>


<div class="container box">
  

    <div class="row">
        <div class="col-sm-1"></div>
        <form action="" method="POST">
         <div class="row">
            <div class="col-sm-8">
              Servicio:<br><select data-live-search="true" id="mibuscador" name="serv" class="form-control" required>
             <?php
               $sql_serv = "SELECT * FROM cat_servicios where serv_activo = 'SI'";
                   $result_serv = $conexion->query($sql_serv);
                    while ($row_serv = $result_serv->fetch_assoc()) {
                       echo "<option value='" . $row_serv['id_serv'] . "'>" . $row_serv['serv_desc'] . "</option>";
                                    }
              ?>
                </select>  
            </div>
            <div class="col-sm-2">
            Cantidad:<br><input type="number" name="cantidad" class="form-control" value="">
            </div>
            <div class="col-sm-2">
            <br><input type="submit" name="btnserv" class="btn btn-block btn-success" value="Guardar">
            </div> 
          </div>
        </form>
    </div><hr>

    <div class="row">
        <div class="col-sm-1"></div>
        <form action="" method="POST">
         <div class="row">
            <div class="col-sm-8">
              Medicamentos y materiales:<br><select data-live-search="true" id="mibuscador2" name="med" class="form-control" required>
             <?php
               $sql_serv = "SELECT * FROM item ";
                   $result_serv = $conexion->query($sql_serv);
                    while ($row_serv = $result_serv->fetch_assoc()) {
                       echo "<option value='" . $row_serv['item_id'] . "'>" . $row_serv['item_name'] . ', '.$row_serv['item_grams'] . "</option>";
                                    }
              ?>
                </select>  
            </div>
            <div class="col-sm-2">
            Cantidad:<br><input type="number" name="cantidad" class="form-control" value="">
            </div>
            <div class="col-sm-2">
            <br><input type="submit" name="btnmed" class="btn btn-block btn-success" value="Guardar">
            </div> 
          </div>
        </form>
    </div>
   
    <?php 
    if (isset($_POST['btnserv'])) {
        include "../../conexionbd.php";
        $nombre='PRUEBA';
        $serv_id =  mysqli_real_escape_string($conexion, (strip_tags($_POST["serv"], ENT_QUOTES)));
        $cantidad =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cantidad"], ENT_QUOTES)));

$resultado_serv = $conexion->query("SELECT * FROM cat_servicios where id_serv = $serv_id") or die($conexion->error);
                  while ($row_serv = $resultado_serv->fetch_assoc()) {
                    $descripcion = $row_serv['serv_desc'];
                  }
                  
$fecha_actual = date("Y-m-d H:i:s");
        $ingresar2 = mysqli_query($conexion, 'INSERT INTO presupuesto (fecha,id_pac,nombre,id_serv,servicio,cantidad) values ("'.$fecha_actual.'",1,"'.$nombre.'","' . $serv_id . '","' . $descripcion .'",' . $cantidad . ') ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

           echo '<script type="text/javascript">window.location.href = "presupuesto.php?id_pac=1&nombre='.$nombre.'";</script>';
    }

    if (isset($_POST['btnmed'])) {
        include "../../conexionbd.php";
        $nombre=mysqli_real_escape_string($conexion, (strip_tags($_GET["nombre"], ENT_QUOTES)));
        $item_id =  mysqli_real_escape_string($conexion, (strip_tags($_POST["med"], ENT_QUOTES)));
        $cantidad =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cantidad"], ENT_QUOTES)));

$resultado_serv = $conexion->query("SELECT * FROM item where item_id = $item_id") or die($conexion->error);
                  while ($row_serv = $resultado_serv->fetch_assoc()) {
                    $item_code = $row_serv['item_code'];
                    $descripcion = $row_serv['item_name'];
                  }
                  
$fecha_actual = date("Y-m-d H:i:s");
        $ingresar2 = mysqli_query($conexion, 'INSERT INTO presupuesto (fecha,id_pac,nombre,id_serv,servicio,cantidad) values ("'.$fecha_actual.'",1,"'.$nombre.'","'. $item_code .'","' . $descripcion .'",' . $cantidad . ') ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

           echo '<script type="text/javascript">window.location.href = "presupuesto.php?id_pac=1&nombre='.$nombre.'";</script>';
    }
     ?>
    <hr class="new4">
     <?php

?>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
             <table class="table table-bordered table-striped" id="mytable">
              <thead class="thead" style="background-color: #2b2d7f; color: white;">
               <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th></th>
              </tr>
             </thead>
            
            <tbody>
                 <?php
                 $total = 0;
               
                   include "../../conexionbd.php";
                
                $id_pac=1;
                $resultado3 = $conexion->query("SELECT * from presupuesto p, cat_servicios c where $id_pac=1 and c.id_serv=p.id_serv") or die($conexion->error);

                 $no = 1;
                while ($row_lista_serv = $resultado3->fetch_assoc()) {
                    $fecha=date_create($row_lista_serv['fecha']);
                    $precio = $row_lista_serv['serv_costo'] * 1.16;
                    $subtottal=$precio*$row_lista_serv['cantidad'];
                  echo '<tr>'
                    . '<td>' . $no . '</td>'
                    . '<td>' . date_format($fecha,"d-m-Y") . '</td>'
                    . '<td>' . $row_lista_serv['servicio'] . '</td>'
                    . '<td>' . $row_lista_serv['cantidad'] . '</td>'
                   
                    . '<td>$' . number_format($subtottal, 2). '</td>'
                    . '<td> <a type="submit" class="btn btn-danger btn-sm" href="eliminar.php?q=eliminar_serv&id_presupuesto= ' . $row_lista_serv['id_presupuesto'] . '&id_pac='.$row_lista_serv['id_pac'].'&nombre='.$nombre.'"><span class = "fa fa-trash"></span></a></td>';
                  echo '</tr>';
                  $total= $subtottal + $total;
                  $no++;
                } 
          $resultado3 = $conexion->query("SELECT * from presupuesto p, item i where id_pac=$id_pac and i.item_code=p.id_serv") or die($conexion->error);
                while ($row_lista_serv = $resultado3->fetch_assoc()) {
                    $fecha=date_create($row_lista_serv['fecha']);
                    $precio = $row_lista_serv['item_price'] * 1.16;
                    $subtottal=$precio*$row_lista_serv['cantidad'];
                  echo '<tr>'
                    . '<td>' . $no . '</td>'
                    . '<td>' . date_format($fecha,"d-m-Y") . '</td>'
                    . '<td>' . $row_lista_serv['servicio'] . '</td>'
                    . '<td>' . $row_lista_serv['cantidad'] . '</td>'
                   
                    . '<td>$' . number_format($subtottal, 2). '</td>'
                    . '<td> <a type="submit" class="btn btn-danger btn-sm" href="eliminar.php?q=eliminar_serv&id_presupuesto= ' . $row_lista_serv['id_presupuesto'] . '&id_pac='.$row_lista_serv['id_pac'].'&nombre='.$nombre.'"><span class = "fa fa-trash"></span></a></td>';
                  echo '</tr>';
                  $total= $subtottal + $total;
                  $no++;
                } 
                ?>
                <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>TOTAL:</td>
              <td><?php echo "$ " . number_format($total, 2); ?></td>
              <td></td>
            </tbody>
            </table>
           </div>
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

<script>
    document.oncontextmenu = function () {
        return false;
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador2').select2();
    });
</script>
</body>

</html>