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
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    .main-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        padding: 0;
        overflow: hidden;
    }
    
    .header-section {
        background: linear-gradient(135deg, #2b2d7f 0%, #3949ab 100%);
        color: white;
        padding: 25px;
        text-align: center;
        margin-bottom: 0;
    }
    
    .header-section h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .header-section i {
        font-size: 32px;
        margin-right: 15px;
        opacity: 0.9;
    }
    
    .content-section {
        padding: 30px;
    }
    
    .form-card {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    
    .form-card:hover {
        border-color: #2b2d7f;
        box-shadow: 0 5px 15px rgba(43, 45, 127, 0.1);
    }
    
    .form-card h5 {
        color: #2b2d7f;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #2b2d7f;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #2b2d7f 0%, #3949ab 100%);
        border: none;
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(43, 45, 127, 0.3);
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(43, 45, 127, 0.4);
    }
    
    .btn-back {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .btn-back:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    }
    
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-top: 25px;
    }
    
    .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #2b2d7f;
        box-shadow: 0 0 0 0.2rem rgba(43, 45, 127, 0.25);
    }
    
    .select2-container--default .select2-selection--single {
        border: 2px solid #e9ecef !important;
        border-radius: 8px !important;
        height: 46px !important;
        padding: 8px 15px !important;
    }
    
    .select2-container--default .select2-selection--single:focus {
        border-color: #2b2d7f !important;
    }
    
    hr.divider {
        border: 0;
        height: 2px;
        background: linear-gradient(135deg, #2b2d7f 0%, #3949ab 100%);
        margin: 30px 0;
        border-radius: 1px;
    }
    
    .total-section {
        background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
        border: 2px solid #28a745;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        text-align: center;
    }
    
    .total-amount {
        font-size: 24px;
        font-weight: bold;
        color: #155724;
    }
  </style>

    <title>Presupuestos - Gestión Administrativa</title>
    <link rel="shortcut icon" href="logp.png">
</head>

<div class="container-fluid">
    <a class="btn-back" onclick="history.back()">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>
    
    <div class="main-container">
        <div class="header-section">
            <h2>
                <i class="fas fa-calculator"></i>
                GESTIÓN DE PRESUPUESTOS
            </h2>
        </div>
        <div class="content-section">
            <?php
                $nombre='PRUEBA';
            ?>

            <!-- Formulario de Servicios -->
            <div class="form-card">
                <h5><i class="fas fa-concierge-bell"></i> Agregar Servicios</h5>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="serv" class="form-label"><strong>Servicio:</strong></label>
                            <select data-live-search="true" id="mibuscador" name="serv" class="form-control" required>
                                <?php
                                    $sql_serv = "SELECT * FROM cat_servicios where serv_activo = 'SI'";
                                    $result_serv = $conexion->query($sql_serv);
                                    while ($row_serv = $result_serv->fetch_assoc()) {
                                        echo "<option value='" . $row_serv['id_serv'] . "'>" . $row_serv['serv_desc'] . "</option>";
                                    }
                                ?>
                            </select>  
                        </div>
                        <div class="col-md-2">
                            <label for="cantidad" class="form-label"><strong>Cantidad:</strong></label>
                            <input type="number" name="cantidad" class="form-control" value="" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <input type="submit" name="btnserv" class="btn btn-primary-custom btn-block" value="Agregar">
                        </div> 
                    </div>
                </form>
            </div>

            <!-- Formulario de Medicamentos -->
            <div class="form-card">
                <h5><i class="fas fa-pills"></i> Agregar Medicamentos y Materiales</h5>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="med" class="form-label"><strong>Medicamentos y materiales:</strong></label>
                            <select data-live-search="true" id="mibuscador2" name="med" class="form-control" required>
                                <?php
                                    $sql_serv = "SELECT * FROM item ";
                                    $result_serv = $conexion->query($sql_serv);
                                    while ($row_serv = $result_serv->fetch_assoc()) {
                                        echo "<option value='" . $row_serv['item_id'] . "'>" . $row_serv['item_name'] . ', '.$row_serv['item_grams'] . "</option>";
                                    }
                                ?>
                                        </select>
                        </div>
                        <div class="col-md-2">
                            <label for="cantidad2" class="form-label"><strong>Cantidad:</strong></label>
                            <input type="number" name="cantidad" class="form-control" value="" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <input type="submit" name="btnmed" class="btn btn-primary-custom btn-block" value="Agregar">
                        </div> 
                    </div>
                </form>
            </div>
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
            
            <hr class="divider">
            
            <!-- Tabla de Presupuesto -->
            <div class="table-container">
                <table class="table table-striped table-hover mb-0" id="mytable">
                    <thead style="background-color: #2b2d7f; color: white;">
                        <tr>
                            <th style="padding: 15px;">#</th>
                            <th style="padding: 15px;">Fecha</th>
                            <th style="padding: 15px;">Descripción</th>
                            <th style="padding: 15px;">Cantidad</th>
                            <th style="padding: 15px;">Precio</th>
                            <th style="padding: 15px; text-align: center;">Acciones</th>
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
                                . '<td style="padding: 12px;">' . $no . '</td>'
                                . '<td style="padding: 12px;">' . date_format($fecha,"d-m-Y") . '</td>'
                                . '<td style="padding: 12px;">' . $row_lista_serv['servicio'] . '</td>'
                                . '<td style="padding: 12px; text-align: center;">' . $row_lista_serv['cantidad'] . '</td>'
                                . '<td style="padding: 12px; text-align: right;">$' . number_format($subtottal, 2). '</td>'
                                . '<td style="padding: 12px; text-align: center;"> <a class="btn btn-danger btn-sm" href="eliminar.php?q=eliminar_serv&id_presupuesto= ' . $row_lista_serv['id_presupuesto'] . '&id_pac='.$row_lista_serv['id_pac'].'&nombre='.$nombre.'" onclick="return confirm(\'¿Está seguro de eliminar este elemento?\')"><i class="fa fa-trash"></i></a></td>';
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
                                . '<td style="padding: 12px;">' . $no . '</td>'
                                . '<td style="padding: 12px;">' . date_format($fecha,"d-m-Y") . '</td>'
                                . '<td style="padding: 12px;">' . $row_lista_serv['servicio'] . '</td>'
                                . '<td style="padding: 12px; text-align: center;">' . $row_lista_serv['cantidad'] . '</td>'
                                . '<td style="padding: 12px; text-align: right;">$' . number_format($subtottal, 2). '</td>'
                                . '<td style="padding: 12px; text-align: center;"> <a class="btn btn-danger btn-sm" href="eliminar.php?q=eliminar_serv&id_presupuesto= ' . $row_lista_serv['id_presupuesto'] . '&id_pac='.$row_lista_serv['id_pac'].'&nombre='.$nombre.'" onclick="return confirm(\'¿Está seguro de eliminar este elemento?\')"><i class="fa fa-trash"></i></a></td>';
                            echo '</tr>';
                            $total= $subtottal + $total;
                            $no++;
                        } 
                        ?>
                        <tr style="background: #f8f9fa; border-top: 3px solid #2b2d7f;">
                            <td colspan="4" style="padding: 15px; text-align: right; font-weight: bold; font-size: 16px; color: #2b2d7f;">TOTAL:</td>
                            <td style="padding: 15px; text-align: right; font-weight: bold; font-size: 18px; color: #28a745;"><?php echo "$ " . number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
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