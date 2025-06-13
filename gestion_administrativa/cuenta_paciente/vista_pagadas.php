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

<body>
 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="excel_cuentas_pagadas.php">
    <div class="container-fluid">
        <div class="row">
        <div class="col-sm-2">
                <a type="submit" class="btn btn-danger btn-sm" onclick="history.back()"><font color="white">Regresar</font></a>
                
            </div>
         <?php
            if ($usuario['id_usua'] == 1) {
            ?>
            <!--
            <div class="col-sm-2">
                
                <input type="date" class="form-control" name="date1" id = "date1" required>
            </div> 
            <div class="col-sm-2">
               
                <input type="date" class="form-control" name="date2" id = "date2" required>
            </div>
           
            <div class="col-sm-4">
                <img src="https://img.icons8.com/color/48/000000/ms-excel.png"/>
              <strong>
              <input type="submit" class="btn btn-warning" name="btnexcel" id = "btnexcel" value="Exportar a excel">
              </strong>    
                
                
            </div>-->
            
         <?php } ?>
        </div>
    </div>
    </form>
    

<br>
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                            <tr><strong><center>HISTÓRICO DE CUENTAS PAGADAS</center></strong>
    </div>
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
                                <th scope="col">Paciente</th>
                                <th scope="col">Médico</th>
                                <th scope="col">Especialidad</th>
                                <th scope="col">Ingreso</th>
                                <th scope="col">Alta</th>
                                <th scope="col">Días <br> Estancia</th>
                                <th scope="col">Cliente</th>
                                <th style="text-align: center" bgcolor="green">Total </th>
                                <th scope="col">Descuento</th>
                                <th scope="col">Real pagado</th>
                              
                               
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        $resultado = $conexion->query("SELECT di.fecha as ingreso, di.*, p.* from dat_ingreso di, paciente p  WHERE p.Id_exp=di.Id_exp and valida='SI' ORDER BY fec_egreso DESC") or die($conexion->error);
                        while ($f = $resultado->fetch_assoc()) {
                            $nombre=$f['papell'].' '.$f['sapell'].' ' .$f['nom_pac'];
                            $id_atencion=$f['id_atencion'];
                            $id_exp = $f['Id_exp'];
                            //$cama = $f['num_cama'];
                            $fecingreso = date_create($f['ingreso']);
                            $fecing = date_format($fecingreso, "d/m/Y h:i A");
                            
                            $datei=date_create($f['ingreso']);
                            $datei = date_format($datei, "d/m/Y h:i A");
                            
                            $datee=date_create($f['fec_egreso']);
                            $datee = date_format($datee, "d/m/Y h:i A");
                            
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
                            
                            $sql_est = "SELECT DATEDIFF( fec_egreso , fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";
                        $result_est = $conexion->query($sql_est);
                        while ($row_est = $result_est->fetch_assoc()) {
                            $estancia = $row_est['estancia'];
                        } 
                            
                            
                        $resultadom = $conexion ->query("SELECT * FROM reg_usuarios WHERE id_usua = $medico ") or die($conexion->error);
                        while($filam = mysqli_fetch_array($resultadom)){ 
                            $nom_medico=$filam["pre"].'. '.$filam["papell"];
                        }
                        $resultadot = $conexion ->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$asegura'")or die($conexion->error);
                        while($filat = mysqli_fetch_array($resultadot)){ 
                            $tr=$filat["tip_precio"];
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
                    $flag != 'PC' ) {
                    $precio = $precioh;
                    $subtottal = $precio * $cant;
                    $iva =  $subtottal * 0.16;
                }elseif ($flag == 'H') {
                       $preciog12h = $precioh;
                       $subtotalg12h = $preciog12h * $cant;
                       $iva = 0.00;
                }elseif ($flag == 'S') {
                    $resultado_serv = $conexion->query("SELECT * FROM cat_servicios where id_serv = $insumo") or die($conexion->error);
                    while ($row_serv = $resultado_serv->fetch_assoc()) {
                        $tip_servi = $row_serv['tip_insumo'];
                    
                        if ($tr==1) $precio = $row_serv['serv_costo'];
                        if ($tr==2) $precio = $row_serv['serv_costo2'];
                        if ($tr==3) $precio = $row_serv['serv_costo3'];
                        if ($tr==4) $precio = $row_serv['serv_costo4'];
                        
                        if ($precio == 0) $precio = $precioh;
                        
                        $subtottal = $precio * $cant;
                        $iva = $subtottal * 0.16;
                        
                        $tip_s = $row_serv['tipo'];
                        
                        if ($insumo == 1 || $insumo == 3 ){
                            $num_gasto = $num_gasto + 1;
                        }
                        
                        if ($tip_servi=="RENTA EQUIPO"){
                            $preciog12 = $precioh;
                            $subtotalg12 = ($preciog12 * $cant) * 1.16;
                        }
                           
                        
                    }
                } else if ($flag == 'P' || $flag == 'PC' ) {
                    $costo = 0;
                    $resultado_i = $conexion_almacen->query("SELECT item_cost FROM item_almacen where item_id = $insumo and activo = 'SI' ") or die($conexion->error);
                    while ($row_i = $resultado_i->fetch_assoc()) {
                        $costo = $row_i['item_cost'];
                    }
                    $precio = $precioh;
                    $subtottal = $precio * $cant;
                    $iva =  $subtottal * 0.16;
                    $subtotal_costo = $costo * $cant;
                }
                
                $totalg12    = $totalg12 + $subtotalg12;
                $totalg12h   = $totalg12h + $subtotalg12h;
                
                $total      = $total + $subtottal;
                $totaliva   = $totaliva + $iva;
                
                if ($tip_s == '1') {$totallab = $totallab + $subtottal + $iva;}
                if ($tip_s == '2') {$totalimg = $totalimg + $subtottal + $iva;}
                $total_costos = $total_costos + $subtotal_costo;
                $total_gasto = $num_gasto * 2493;
            }
            $total = $total +  + $totalg12h; 
            $Stotal = ($total + $totaliva);
         
            $utilidad = (($Stotal - $totalg12h - $totalg12 - ($totallab * .07) - ($totalimg * .08) - $total_costos - $total_gasto) / $Stotal ) * 100 ;
                        
            $resultado_total = $conexion->query("SELECT * FROM dat_financieros where id_atencion = $id_atencion and banco != 'DESCUENTO'") or die($conexion->error);
            $total_dep = 0;
            while ($row_total = $resultado_total->fetch_assoc()) {
                $total_dep = $row_total['deposito'] + $total_dep;
            }
            $resultado_totald = $conexion->query("SELECT * FROM dat_financieros where id_atencion = $id_atencion and banco = 'DESCUENTO'") or die($conexion->error);
            $total_desc = 0;
            while ($row_totald = $resultado_totald->fetch_assoc()) {
                $total_desc = $row_totald['deposito'] + $total_desc;
            }
        ?>
        <tr>
            <td><center><a type="submit" class="btn btn-warning btn-sm" target="blank" href="detalle_cta.php?id_at=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp ?>&id_usua=<?php echo $usuario1; ?>&rol=<?php echo $rol ?>"><span class="fas fa-dollar-sign" style="font-size:22px"></span></a>
           
            </center> </td>
            <td><strong><?php echo $id_exp; ?></strong></td>
            <td bgcolor="green"><strong><font color="white"> <?php echo $id_atencion; ?></font></strong></td>
            <td><strong><?php echo $nombre; ?></strong></td>
            <td><strong><?php echo $nom_medico; ?></strong></td>
            <td><strong><?php echo $especialidad; ?></strong></td>
            <td><strong><font size="3"><?php echo $datei; ?></strong></td>
            <td><strong><font size="3"><?php echo $datee; ?></strong></td>
            <td><strong><font size="3"><?php echo $estancia; ?></strong></td>
            <td><strong><?php echo $asegura; ?></strong></td>
            
            <td style="text-align: center; font-weight:bold;"><font size ="3", color ="green"><strong><?php echo number_format($Stotal, 2); ?></strong></td>
            <td><strong><?php echo number_format($total_desc, 2); ?></strong></td>
            <td><strong><?php echo number_format($total_dep, 2); ?></strong></td>

           
        <?php
        
        include "../../conexionbd.php";
        include '../../conn_almacen/Connection.php';

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