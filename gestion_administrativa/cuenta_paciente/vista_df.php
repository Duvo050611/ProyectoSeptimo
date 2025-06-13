<?php
session_start();
include "../../conexionbd.php";
include '../../conn_almacen/Connection.php';
include "../../gestion_administrativa/header_administrador.php";
$usuario = $_SESSION['login'];
$usuario1 = $usuario['id_usua'];
$rol = $usuario['id_rol'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link rel="icon" href="../../imagenes/SIF.PNG">
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="../../js/jquery-ui.js"></script>
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

    <title>Menu Gestión administrativa </title>
    <link rel="shortcut icon" href="logp.png">
</head>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-2">
            <a type="submit" class="btn btn-danger btn-sm" onclick="history.back()">
                <font color="white">Regresar</font>
            </a>
        </div>
        <?php
            if ($usuario['id_usua'] == 1 ) {
            ?>
        <div class="col-sm-4"><a href="vista_pagadas.php">
                <button type="button" class="btn btn-primary col-md-8" data-target="#exampleModal">
                    <i class="fa fa-plus"></i>
                    <font id="letra">Cuentas pagadas</font>
                </button>
        </div>
        <div class="col-sm">
            <a href="excel_cuentas_activas.php">
                <!--<button type="button" class="btn btn-warning"><img src="https://img.icons8.com/color/48/000000/ms-excel.png"/><strong>Exporta a excel</strong></a>-->
        </div>
        <?php } ?>
    </div>
</div>
<br>

<div class="container-fluid">
    <div class="row">
        <div class="col col-12">
            <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars"
                    id="side"></i></a>
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                <tr><strong>
                        <center>CUENTA DE PACIENTES ACTIVOS</center>
                    </strong>
            </div>
            <br>
        </div>
    </div>

</div>
<?php
        
?>
<h2>
    <a href="" data-target="#sidebar" data-toggle="collapse" class="d-md-none"><i class="fa fa-bars" id="side"></i></a>
</h2>

<div class="form-group">
    <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped" id="mytable">
        <thead class="thead">
            <tr>
                <th scope="col">Cuenta</th>
                <th scope="col">Exp</th>
                <th scope="col">Id <br> Atencion</th>
                <th scope="col">Hab</th>
                <th scope="col">Paciente</th>
                <th scope="col">Médico</th>
                <th scope="col">Especialidad</th>
                <th scope="col">Cliente</th>
                <th scope="col">Subtotal</th>
                <th scope="col">IVA</th>
                <th style="text-align: center" bgcolor="green">Total </th>
                <th scope="col">Anticipos</th>


                <?php
                           
                            ?>
                <th scope="col">Fecha de Ingreso</th>
            </tr>
        </thead>
        <tbody>

            <?php
                        $resultado = $conexion->query("SELECT * from cat_camas c, dat_ingreso di, paciente p  WHERE c.id_atencion=di.id_atencion and p.Id_exp=di.Id_exp and di.activo='SI'  ORDER BY di.fecha DESC") or die($conexion->error);
                        while ($f = $resultado->fetch_assoc()) {
                            $nombre=$f['papell'].' '.$f['sapell'].' ' .$f['nom_pac'];
                            $id_atencion=$f['id_atencion'];
                            $id_exp = $f['Id_exp'];
                            $cama = $f['num_cama'];
                            $date = date_create($f['fecha']);
                            $fecing = date_format($date, "d/m/Y h:i A");
                            $asegura = $f['aseg'];
                            $medico = $f['id_usua'];
                            $especialidad = $f['tipo_a'];
                            $total = 0;
                            $iva = 0;
                            $totalg12 = 0;
                            $totalg12h = 0;
                            $totaliva = 0;
                            $Stotal = 0;
                            $costo = 0;
                            $subtotal_costo = 0;
                            $total_costos = 0;
                            $totallab = 0;
                            $totalimg = 0;
                            $total_gasto = 0;
                            $num_gasto=0;
                            
                            
                            $resultadom = $conexion ->query("SELECT * FROM reg_usuarios WHERE id_usua = $medico ") or die($conexion->error);
                            while($filam = mysqli_fetch_array($resultadom)){ 
                                $nom_medico=$filam["pre"].'. '.$filam["papell"];
                             }
                                                        // Initialize $tr with a default value
                            $tr = 0; // Default to 0 or another fallback value that makes sense in your context
                            $resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$asegura'") or die($conexion->error);
                            if ($resultadot && mysqli_num_rows($resultadot) > 0) {
                                while ($filat = mysqli_fetch_array($resultadot)) { 
                                    $tr = $filat["tip_precio"];
                                }
                            }
       
                            $resultado3 = $conexion->query("SELECT * FROM dat_ctapac where id_atencion = $id_atencion ORDER BY id_atencion") or die($conexion->error);
                            while ($row3 = $resultado3->fetch_assoc()) {
                                $flag = $row3['prod_serv'];
                                $insumo = $row3['insumo'];
                                $cant = $row3['cta_cant'];
                                $precioh = $row3['cta_tot'];
                                $precio = 0;
                                $iva = 0;
                                $subtottal = 0;
                                $preciog12 = 0;
                                $subtotalg12 = 0;
                                $preciolab = 0;
                                $subtotallab = 0;
                                $precioimg = 0;
                                $subtotalimg = 0;
                                $preciog12h = 0;
                                $subtotalg12h = 0;
                                $costo = 0;
                                $subtotal_costo = 0;
                                $tip_s = ' ';
                                $tip_servi=' ';
                                $Stotal=0;
                               
                             
                if ($insumo == 0 && 
                    $flag != 'S' && 
                    $flag != 'H' && 
                    $flag != 'P' && 
                    $flag != 'PC') {
                    $precio = $precioh;
                    $subtottal = $precio * $cant;
                    $iva = $subtottal * 0.16;
                } elseif ($flag == 'H') {
                    $preciog12h = $precioh;
                    $subtotalg12h = $preciog12h * $cant;
                    $iva = 0.00;
                } elseif ($flag == 'S') {
                    $resultado_serv = $conexion->query("SELECT * FROM cat_servicios WHERE id_serv = $insumo") or die($conexion->error);
                    while ($row_serv = $resultado_serv->fetch_assoc()) {
                        $tip_servi = $row_serv['tip_insumo'];
                        
                        // Use $tr with a fallback to $precioh if $tr is invalid or undefined
                        if ($tr == 1) {
                            $precio = $row_serv['serv_costo'];
                        } elseif ($tr == 2) {
                            $precio = $row_serv['serv_costo2'];
                        } elseif ($tr == 3) {
                            $precio = $row_serv['serv_costo3'];
                        } elseif ($tr == 4) {
                            $precio = $row_serv['serv_costo4'];
                        } else {
                            $precio = $precioh; // Fallback price
                        }
                        
                        if ($precio == 0) {
                            $precio = $precioh;
                        }
                        
                        $subtottal = $precio * $cant;
                        $iva = $subtottal * 0.16;
                        
                        $tip_s = $row_serv['tipo'];
                        
                        if ($insumo == 1 || $insumo == 3) {
                            $num_gasto = $num_gasto + 1;
                        }
                        
                        if ($tip_servi == "RENTA EQUIPO") {
                            $preciog12 = $precioh;
                            $subtotalg12 = ($preciog12 * $cant) * 1.16;
                        }
                    }
                } elseif ($flag == 'P' || $flag == 'PC') {
                    $costo = 0;
                    $precio = $precioh;
                    $subtottal = $precio * $cant;
                    $iva = $subtottal * 0.16;
                }
                
                $totalg12    = $totalg12 + $subtotalg12;
                $totalg12h   = $totalg12h + $subtotalg12h;
                
                $total      = $total + $subtottal;
                $totaliva   = $totaliva + $iva;
                
                if ($tip_s == '1') {$totallab = $totallab + $subtottal + $iva;}
                if ($tip_s == '2') {$totalimg = $totalimg + $subtottal + $iva;}
               
            }
            $total = $total +  + $totalg12h; 
            $Stotal = ($total + $totaliva);
         
           
                        
        $sql_tabla = "SELECT deposito FROM dat_financieros WHERE id_atencion=$id_atencion ORDER BY id_atencion";
        $result_tabla = $conexion->query($sql_tabla);
        $subtottaldep=0;
        $totaldep=0;
        while ($row_tabla = $result_tabla->fetch_assoc()) {
            $subtotaldep=$row_tabla['deposito'];
            $totaldep=$totaldep+$subtotaldep;
        }
        ?>
            <tr>
                <td>
                    <center><a type="submit" class="btn btn-warning btn-sm"
                            href="detalle_cuenta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua=<?php echo $usuario1; ?>&rol=<?php echo $rol ?>"><span
                                class="fas fa-dollar-sign" style="font-size:22px"></span></a>
                        <a type="submit" class="btn btn-danger btn-sm"
                            href="cuenta.php?id_atencion=<?php echo $id_atencion ?>&id_usua=<?php echo $usuario1 ?>"
                            target="blank">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                    </center>
                </td>
                <td><strong><?php echo $id_exp; ?></strong></td>
                <td style="background:grey;"><strong>
                        <font color="white"> <?php echo $id_atencion; ?></font>
                    </strong></td>
                <td bgcolor="green"><strong>
                        <font color="white"> <?php echo $cama; ?></font>
                    </strong></td>
                <td><strong><?php echo $nombre; ?></strong></td>
                <td><strong><?php echo $nom_medico; ?></strong></td>
                <td><strong><?php echo $especialidad; ?></strong></td>
                <td><strong><?php echo $asegura; ?></strong></td>
                <td><strong><?php echo number_format($total, 2); ?></strong></td>
                <td><strong><?php echo number_format($totaliva, 2); ?></strong></td>
                <td style="text-align: center; font-weight:bold; background:white;">
                    <font size="3" , color="red"><strong><?php echo number_format($Stotal, 2); ?></strong>
                </td>
                <td><strong><?php echo number_format($totaldep, 2); ?></strong></td>

                <?php
           
            ?>
                <td><strong>
                        <font size="3"><?php echo $fecing; ?>
                    </strong></td>
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


<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function() {
        $("#alerts").hide(500);
    }, 500);
});
</script>
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