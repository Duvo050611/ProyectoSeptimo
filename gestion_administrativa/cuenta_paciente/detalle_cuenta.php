<?php
session_start();
include "../header_administrador.php";

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
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
        $(document).ready(function() {
            $("#search").keyup(function() {
                _this = this;
                $.each($("#mytable tbody tr"), function() {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>

    <title>Detalle de la cuenta del paciente</title>
    <style>
        hr.new4 {
            border: 1px solid red;
        }
    </style>
</head>

<body>
    <section class="content container-fluid">
        <div class="container box">
            <div class="content">
                <?php
                include "../../conexionbd.php";
                $usuario1 = $_GET['id_usua'];
                $rol = $_GET['rol'];
                $id_exp = $_GET['id_exp'];
                $id_atencion = $_GET['id_at'];

                // Calculate $total_dep
                $resultado_total = $conexion->query("SELECT * FROM dat_financieros where id_atencion = $id_atencion") or die($conexion->error);
                $total_dep = 0;
                while ($row_total = $resultado_total->fetch_assoc()) {
                    $total_dep = $row_total['deposito'] + $total_dep;
                }

                // Calculate $total_desc
                $total_desc = 0;
                $sql_desc = "SELECT SUM(deposito) as total_desc FROM dat_financieros WHERE id_atencion = $id_atencion AND banco = 'DESCUENTO'";
                $result_desc = $conexion->query($sql_desc);
                if ($row_desc = $result_desc->fetch_assoc()) {
                    $total_desc = $row_desc['total_desc'] ?? 0;
                }

                // Calculate $total
                $total = 0;
                $Stotiva = 0;
                $tr = 1; // Default price type
                $resultado3 = $conexion->query("SELECT * FROM dat_ctapac WHERE id_atencion = $id_atencion ORDER BY cta_fec ASC") or die($conexion->error);
                while ($row3 = $resultado3->fetch_assoc()) {
                    $flag = $row3['prod_serv'];
                    $insumo = $row3['insumo'];
                    $precioh = $row3['cta_tot'];
                    $cant = $row3['cta_cant'];
                    $precio = 0;
                    $iva = 0;

                    if ($insumo == 0 && $flag != 'S' && $flag != 'H' && $flag != 'P' && $flag != 'PC') {
                        $precio = $precioh;
                        $iva = $precio * 0.16;
                    } elseif ($flag == 'H') {
                        $precio = $precioh;
                        $iva = 0.00;
                    } elseif ($flag == 'S') {
                        $resultado_serv = $conexion->query("SELECT serv_costo, serv_costo2, serv_costo3, serv_costo4 FROM cat_servicios WHERE id_serv = $insumo") or die($conexion->error);
                        if ($row_serv = $resultado_serv->fetch_assoc()) {
                            if ($tr == 1) $precio = $row_serv['serv_costo'];
                            elseif ($tr == 2) $precio = $row_serv['serv_costo2'];
                            elseif ($tr == 3) $precio = $row_serv['serv_costo3'];
                            elseif ($tr == 4) $precio = $row_serv['serv_costo4'];
                            else $precio = $precioh;
                            $iva = $precio * 0.16;
                        } else {
                            $precio = $precioh;
                            $iva = $precio * 0.16;
                        }
                    } elseif ($flag == 'P' || $flag == 'PC') {
                        $precio = $precioh;
                        $iva = $precio * 0.16;
                    }

                    $precio2 = $precio + $iva;
                    $subtottal = $precio2 * $cant;
                    $totiva = $iva * $cant;
                    $Stotiva += $totiva;
                    $total += $subtottal;
                }

                // Calculate $diferencia
                $diferencia = $total - $total_dep - $total_desc;
                $diferencia = round($diferencia, 2, PHP_ROUND_HALF_DOWN);

                $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac, di.fecha, di.area, di.alta_med, di.alta_adm, di.valida, di.correo FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";
                $result_pac = $conexion->query($sql_pac);

                while ($row_pac = $result_pac->fetch_assoc()) {
                    $pac_papell = $row_pac['papell'];
                    $pac_sapell = $row_pac['sapell'];
                    $pac_nom_pac = $row_pac['nom_pac'];
                    $pac_dir = $row_pac['dir'];
                    $pac_id_edo = $row_pac['id_edo'];
                    $pac_id_mun = $row_pac['id_mun'];
                    $pac_tel = $row_pac['tel'];
                    $pac_fecnac = $row_pac['fecnac'];
                    $pac_fecing = $row_pac['fecha'];
                    $area = $row_pac['area'];
                    $alta_med = $row_pac['alta_med'];
                    $alta_adm = $row_pac['alta_adm'];
                    $valida = $row_pac['valida'];
                    $id_exp = $row_pac['Id_exp'];
                    $correo = $row_pac['correo'];
                }

                $fecha_actual = date("Y-m-d H:i:s");
                $sql_now = "SELECT DATE_ADD('$fecha_actual', INTERVAL 11 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";
                $result_now = $conexion->query($sql_now);
                while ($row_now = $result_now->fetch_assoc()) {
                    $dat_now = $row_now['dat_now'];
                }

                $sql_est = "SELECT DATEDIFF('$dat_now', fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";
                $result_est = $conexion->query($sql_est);
                while ($row_est = $result_est->fetch_assoc()) {
                    $estancia = $row_est['estancia'];
                }

                $sql_est = "SELECT DATEDIFF('$dat_now', fecha_cama) as dia_hab FROM dat_ingreso WHERE id_atencion = $id_atencion";
                $result_est = $conexion->query($sql_est);
                while ($row_est = $result_est->fetch_assoc()) {
                    $dia_hab = $row_est['dia_hab'];
                }

                $sql_dia_hab = "SELECT * FROM dat_ctapac WHERE id_atencion = $id_atencion and prod_serv='S' and insumo IN (1,2,3,8,4) Order by id_ctapac DESC LIMIT 1";
                $result_dia_hab = $conexion->query($sql_dia_hab);
                while ($row_dia_hab = $result_dia_hab->fetch_assoc()) {
                    $id_cta = $row_dia_hab['id_ctapac'];
                    $cta_cant = $row_dia_hab['cta_cant'];
                    $insumo = $row_dia_hab['insumo'];
                }

                if (isset($id_cta)) {
                    // Logic for updating dat_ctapac can remain here if needed
                } else {
                ?>
                    <div class="col-md-12">
                        <!-- <div class="alert alert-danger alert-dismissible fade show"><center><strong>PACIENTE SIN HABITACIÓN</strong></center></div> -->
                    </div>
                <?php } ?>

                <div class="row">
                    <button type="button" class="btn btn-danger btn-sm" onclick="history.back()">Regresar</button>
                </div>

                <br>
                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                    <tr><strong>
                            <center>CUENTA DEL PACIENTE</center>
                        </strong>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label">Paciente: </label><strong> &nbsp;
                                <?php echo $id_exp . ' - ' . $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
                        </div>
                        <?php
                        $pac_fecing = date_create($pac_fecing);
                        ?>
                        <div class="col-md-6">
                            <label class="control-label">Fecha de ingreso: </label><strong> &nbsp;
                                <?php echo date_format($pac_fecing, "d/m/Y H:i a") ?></strong>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Aseguradora: </label><strong> &nbsp;
                                <?php
                                $sql_aseg = "SELECT aseg from dat_ingreso where id_atencion =$id_atencion";
                                $result_aseg = $conexion->query($sql_aseg);
                                while ($row_aseg = $result_aseg->fetch_assoc()) {
                                    echo $row_aseg['aseg'];
                                    $at = $row_aseg['aseg'];
                                }
                                $resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$at'") or die($conexion->error);
                                while ($filat = mysqli_fetch_array($resultadot)) {
                                    $tr = $filat["tip_precio"];
                                }
                                ?></strong>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">Habitación: </label><strong> &nbsp;
                                <?php
                                $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
                                $result_hab = $conexion->query($sql_hab);
                                while ($row_hab = $result_hab->fetch_assoc()) {
                                    echo $row_hab['num_cama'];
                                }
                                ?></strong>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label">Área: </label><strong> &nbsp; <?php echo $area ?></strong>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Días de estancia: </label><strong> &nbsp;
                                <?php echo $estancia ?> días</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Totals and Difference -->
        <div class="container">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <td style="text-align: center">Total de la cuenta:</td>
                        <td style="text-align: center; font-weight:bold;">
                            <font size="3" color="black"><?php echo number_format($total, 2); ?></font>
                        </td>
                        <td style="text-align: center">Total de pagos:</td>
                        <td style="text-align: center; font-weight:bold;">
                            <font size="3" color="black"><?php echo number_format($total_dep, 2); ?></font>
                        </td>
                        <td style="text-align: center">Total descuentos:</td>
                        <td style="text-align: center; font-weight:bold;">
                            <font size="3" color="black"><?php echo number_format($total_desc, 2); ?></font>
                        </td>
                        <td style="text-align: center">Saldo:</td>
                        <td style="text-align: center; font-weight:bold;">
                            <font size="3" color="red"><?php echo number_format($diferencia, 2); ?></font>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add Service to Account -->
        <div class="container">
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                <tr><strong>
                        <center>AGREGAR SERVICIO A LA CUENTA</center>
                    </strong>
            </div>
            <form class="form-horizontal" action="" method="post">
                <div class="row">
                    <div class="col-md-5">
                        <label for="serv">Servicio:</label>
                        <select name="serv" id="serv" class="form-control" required>
                            <option value="">Seleccionar servicio</option>
                            <?php
                            $result_servicios = $conexion->query("SELECT id_serv, serv_desc FROM cat_servicios ORDER BY serv_desc ASC") or die($conexion->error);
                            while ($row_serv = $result_servicios->fetch_assoc()) {
                                echo '<option value="' . $row_serv['id_serv'] . '">' . htmlspecialchars($row_serv['serv_desc']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="cant">Cantidad:</label>
                        <input type="number" name="cant" id="cant" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label><br>
                        <input type="submit" name="btnserv" class="btn btn-primary btn-block" value="Agregar Servicio">
                    </div>
                </div>
            </form>
        </div>
        <hr>
        <!-- New Buttons -->
        <div class="row mt-4 justify-content-center">
            <div class="col-md-4 mb-3">
                <a href="cuenta.php?id_atencion=<?php echo $id_atencion ?>&id_usua=<?php echo $id_usua ?>"
                    target="_blank"
                    class="btn btn-primary btn-lg btn-block shadow rounded">
                    <i class="fas fa-file-alt mr-2"></i> Estado de cuenta
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="excel.php?id_atencion=<?php echo $id_atencion ?>&id_usua=<?php echo $usuario1 ?>"
                    class="btn btn-warning btn-lg btn-block shadow rounded">
                    <i class="fas fa-file-excel mr-2"></i> Descargar Excel
                </a>
            </div>
        </div>


        <hr>

        <!-- Charges Table -->
        <div class="container">
            <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">
                    <thead class="thead" style="background-color: #2b2d7f; color: white;">
                        <tr>
                            <th>#</th>
                            <th>Fecha de registro</th>
                            <th>Área/Tipo</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>P. U.</th>
                            <th>IVA</th>
                            <th>Subtotal</th>
                            <th>Lote</th>
                            <th>Caducidad</th>
                            <?php if ($rol == 5 || $rol == 1) { ?>
                                <th></th>
                                <th></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $Stotiva = 0;
                        $no = 1;
                        $resultado3 = $conexion->query("SELECT * FROM dat_ctapac WHERE id_atencion = $id_atencion ORDER BY cta_fec ASC") or die($conexion->error);
                        while ($row3 = $resultado3->fetch_assoc()) {
                            $flag = $row3['prod_serv'];
                            $insumo = $row3['insumo'];
                            $id_ctapac = $row3['id_ctapac'];
                            $precioh = $row3['cta_tot'];
                            $cant = $row3['cta_cant'];
                            $existe_lote = $row3['existe_lote'] ?? '';
                            $existe_caducidad = $row3['existe_caducidad'] ?? '';
                            $precio = 0;
                            $iva = 0;
                            $descripcion = '';
                            $umed = '';

                            if ($insumo == 0 && $flag != 'S' && $flag != 'H' && $flag != 'P' && $flag != 'PC') {
                                $descripcion = $row3['prod_serv'];
                                $umed = "OTROS";
                                $precio = $precioh;
                                $iva = $precio * 0.16;
                            } elseif ($flag == 'H') {
                                $resultado_servi = $conexion->query("SELECT serv_desc, serv_umed FROM cat_servicios WHERE id_serv = $insumo") or die($conexion->error);
                                if ($row_servi = $resultado_servi->fetch_assoc()) {
                                    $descripcion = $row_servi['serv_desc'];
                                    $umed = $row_servi['serv_umed'];
                                } else {
                                    $descripcion = "Servicio no encontrado";
                                    $umed = "N/A";
                                }
                                $precio = $precioh;
                                $iva = 0.00;
                            } elseif ($flag == 'S') {
                                $resultado_serv = $conexion->query("SELECT serv_desc, serv_umed, serv_costo, serv_costo2, serv_costo3, serv_costo4, tipo FROM cat_servicios WHERE id_serv = $insumo") or die($conexion->error);
                                if ($row_serv = $resultado_serv->fetch_assoc()) {
                                    $descripcion = $row_serv['serv_desc'];
                                    $umed = $row_serv['serv_umed'];
                                    if ($tr == 1) $precio = $row_serv['serv_costo'];
                                    elseif ($tr == 2) $precio = $row_serv['serv_costo2'];
                                    elseif ($tr == 3) $precio = $row_serv['serv_costo3'];
                                    elseif ($tr == 4) $precio = $row_serv['serv_costo4'];
                                    else $precio = $precioh;
                                    $iva = $precio * 0.16;
                                    $tip_serv = $row_serv['tipo'];
                                    if ($tip_serv == "1") $umed = 'LABORATORIO';
                                    elseif ($tip_serv == "2") $umed = 'IMAGENOLOGIA';
                                } else {
                                    $descripcion = "Servicio no encontrado";
                                    $umed = "N/A";
                                    $precio = $precioh;
                                    $iva = $precio * 0.16;
                                }
                            } elseif ($flag == 'P' || $flag == 'PC') {
                                $resultado_prod = $conexion->query("SELECT i.item_name, it.item_type_desc FROM item i, item_type it WHERE i.item_id = $insumo AND it.item_type_id = i.item_type_id") or die($conexion->error);
                                if ($row_prod = $resultado_prod->fetch_assoc()) {
                                    $descripcion = $row_prod['item_name'];
                                    $umed = ($flag == 'P') ? 'FARMACIA, ' . $row_prod['item_type_desc'] : 'QUIRÓFANO, ' . $row_prod['item_type_desc'];
                                } else {
                                    $descripcion = "Producto no encontrado";
                                    $umed = "N/A";
                                }
                                $precio = $precioh;
                                $iva = $precio * 0.16;
                            }

                            $precio2 = $precio + $iva;
                            $subtottal = $precio2 * $cant;
                            $totiva = $iva * $cant;
                            $Stotiva += $totiva;

                            $cta_fec = date_create($row3['cta_fec']);
                            echo '<tr>';
                            echo '<td>' . $no . '</td>';
                            echo '<td>' . date_format($cta_fec, "d-m-Y H:i:s") . '</td>';
                            echo '<td>' . htmlspecialchars($umed) . '</td>';
                            echo '<td>' . htmlspecialchars($descripcion) . '</td>';
                            echo '<td>' . $cant . '</td>';
                            echo '<td>' . number_format($precio, 2) . '</td>';
                            echo '<td>' . number_format($totiva, 2) . '</td>';
                            echo '<td>' . number_format($subtottal, 2) . '</td>';
                            echo '<td>' . htmlspecialchars($existe_lote) . '</td>';
                            echo '<td>' . htmlspecialchars($existe_caducidad) . '</td>';

                            if ($rol == 5 || $rol == 1) {
                                echo '<td><a type="submit" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal' . $id_ctapac . '"><font color="white"><span class="fa fa-trash"></span></font></a></td>';
                                echo '<td>';
                                if ($precio == 0) {
                                    echo '<a href="editarcuenta.php?id=' . $id_ctapac . '&des=' . urlencode($descripcion) . '&id_at=' . $id_atencion . '&id_exp=' . $id_exp . '&id_usua=' . $usuario1 . '&rol=' . $rol . '" type="button" class="btn btn-info btn-sm"><font color="white"><span class="fa fa-edit"></span></font></a>';
                                }
                                echo '</td>';
                                echo '<div class="modal fade" id="exampleModal' . $id_ctapac . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel' . $id_ctapac . '" aria-hidden="true">';
                                echo '<form action="" method="GET">';
                                echo '<div class="modal-dialog" role="document">';
                                echo '<div class="modal-content">';
                                echo '<div class="modal-header">';
                                echo '<h5 class="modal-title" id="exampleModalLabel' . $id_ctapac . '">¿Seguro que desea eliminar <strong><br>' . htmlspecialchars($descripcion) . '?</strong></h5>';
                                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                                echo '</div>';
                                echo '<input type="hidden" class="form-control" value="' . $id_ctapac . '" name="id_ctapac">';
                                echo '<input type="hidden" class="form-control" value="' . htmlspecialchars($umed) . '" name="umed">';
                                echo '<input type="hidden" class="form-control" value="' . $cant . '" name="cant">';
                                echo '<input type="hidden" class="form-control" value="' . $id_atencion . '" name="id_at">';
                                echo '<input type="hidden" class="form-control" value="' . $id_exp . '" name="id_exp">';
                                echo '<input type="hidden" class="form-control" value="' . $usuario1 . '" name="id_usua">';
                                echo '<input type="hidden" class="form-control" value="' . $rol . '" name="rol">';
                                echo '<input type="hidden" class="form-control" value="' . htmlspecialchars($descripcion) . '" name="descrip">';
                                echo '<div class="container">Motivo<input type="text" class="form-control" name="motivo" required></div>';
                                echo '<div class="modal-footer">';
                                echo '<button type="button" class="btn btn-primary" data-dismiss="modal">Regresar</button>';
                                echo '<button type="submit" class="btn btn-danger" name="del_cta_dev"><font color="white">Eliminar</font></button>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</form>';
                                echo '</div>';
                            }
                            echo '</tr>';
                            $no++;
                        }
                        echo '<tr>';
                        echo '<td colspan="4"></td>';
                        echo '<td>Total</td>';
                        echo '<td></td>';
                        echo '<td>' . number_format($Stotiva, 2) . '</td>';
                        echo '<td>' . number_format($total, 2) . '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        if ($rol == 5 || $rol == 1) {
                            echo '<td></td><td></td>';
                        }
                        echo '</tr>';
                        ?>
                    </tbody>
                </table>
            </div>
            <center>
                <strong>
                    <h3 class="col-sm-3 control-label">Diferencia:</h3>
                </strong>
                <div class="col-md-6">
                    <strong><input type="text" class="form-control pull-right"
                            value="$ <?php echo number_format($diferencia, 2); ?>" disabled></strong>
                </div>
            </center>
        </div>

        <!-- Delete Charge -->
        <?php
        if (isset($_GET['del_cta_dev'])) {
            $id_ctapac = $_GET['id_ctapac'];
            $fecha = date("Y-m-d H:i:s");
            $sql_cta = "SELECT * FROM dat_ctapac WHERE id_ctapac=$id_ctapac";
            $result_cta = $conexion->query($sql_cta);
            while ($row_cta = $result_cta->fetch_assoc()) {
                $prod_serv = $row_cta['prod_serv'];
                $insumo = $row_cta['insumo'];
                $cta_cant = $row_cta['cta_cant'];
            }

            $paci = $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac;
            $fecha_actual = date("Y-m-d H:i:s");
            if ($prod_serv == 'P') {
                $sql_dev = "INSERT INTO devolucion(dev_item,dev_qty,dev_estatus,fecha,id_usua,paciente)VALUES($insumo,$cta_cant,'SI','$fecha_actual',$id_usua,'$paci')";
                $result_dev = $conexion->query($sql_dev);
                $sql2 = "DELETE FROM dat_ctapac WHERE id_ctapac = $id_ctapac";
                $result_dev1 = $conexion->query($sql2);
            } elseif ($prod_serv == 'PC') {
                $sql_dev_ceye = "INSERT INTO devolucion_ceye(dev_producto,dev_cantidad,dev_estatus,fecha,id_usua,paciente)VALUES($insumo,$cta_cant,'SI','$fecha_actual',$id_usua,'$paci')";
                $result_dev_ceye = $conexion->query($sql_dev_ceye);
                $sql2 = "DELETE FROM dat_ctapac WHERE id_ctapac = $id_ctapac";
                $result = $conexion->query($sql2);
            } else {
                $sql3 = "DELETE FROM dat_ctapac WHERE id_ctapac = $id_ctapac";
                $result_serv = $conexion->query($sql3);
            }

            $motivo = $_GET['motivo'];
            $cant = $_GET['cant'];
            $umed = $_GET['umed'];
            $id_at = $_GET['id_at'];
            $id_usua = $_GET['id_usua'];
            $descripcion = $_GET['descrip'];
            $sql_reporte = "INSERT INTO cuentas_reporte(id_atencion,id_usua,id_ctapac,descripcion,servicio,cantidad,motivo,fecha)VALUES($id_at,$id_usua,$id_ctapac,'$descripcion',$umed,$cant,'$motivo','$fecha_actual')";
            $result_dev_ceyere = $conexion->query($sql_reporte);
            echo '<script type="text/javascript"> window.location.href="detalle_cuenta.php?id_at=' . $id_atencion . '&id_exp=' . $id_exp . '&id_usua=' . $usuario1 . '&rol=' . $rol . '";</script>';
        }
        ?>

        <br /><br /><br />

        <?php
        $sql_cart = "SELECT * FROM cart where paciente = $id_atencion ORDER BY paciente";
        $result_cart = $conexion->query($sql_cart);
        while ($row_cart = $result_cart->fetch_assoc()) {
            $cart_id = $row_cart['cart_id'];
        }
        $cart_id = isset($cart_id) ? $cart_id : 'nada';
        ?>

        <?php
        if ($alta_med == "SI" && $alta_adm == "NO" && $diferencia == 0) {
        ?>
            <center>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Cerrar Cuenta
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Favor de verificar que el paciente egresa</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                ¿Está seguro de cerrar la cuenta?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">No, regresar</button>
                                <a type="submit" class="btn btn-primary btn-md"
                                    href="valida_cuenta.php?q=cerrar&id_atencion=<?php echo $id_atencion ?>&id_usua=<?php echo $usuario1 ?>&dif=<?php echo $diferencia ?>&total=<?php echo $total ?>">Sí, cerrar cuenta.</a>
                            </div>
                        </div>
                    </div>
                </div>
            </center><br>
        <?php
        } elseif ($alta_med == "SI" && $alta_adm == "SI") {
        ?>
            <div class="container">
                <div class="row">
                    <?php
                    $usuario = $_SESSION['login'];
                    $id_usua = $usuario['id_usua'];
                    ?>
                    <div class="col-sm">
                        <center>
                            <a type="submit" class="btn btn-primary btn-md"
                                href="cuenta.php?id_atencion=<?php echo $id_atencion ?>&id_usua=<?php echo $id_usua ?>"
                                target="_blank">Estado de cuenta</a>
                        </center>
                    </div>
                    <div class="col-sm">
                        <a href="excel.php?id_atencion=<?php echo $id_atencion ?>&id_usua=<?php echo $usuario1 ?>">
                            <button type="button" class="btn btn-warning"><img
                                    src="https://img.icons8.com/color/48/000000/ms-excel.png" /><strong>Exportar a Excel</strong></a>
                    </div>
                </div>
            </div><br>
        <?php
        } elseif ($alta_med == "NO" && $diferencia == 0) {
        ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show">
                            <center><strong>Falta Alta Médica </strong></center>
                        </div>
                    </div>
                </div>
            </div><br>
        <?php
        }
        ?>

        <?php
        if ($diferencia == 0) {
        ?>
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show">
                    <center><strong>Cuenta sin diferencias </strong></center>
                </div>
            </div>
        <?php
        } elseif ($diferencia != 0) {
        ?>
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show">
                    <center><strong>Hay una diferencia en la cuenta por $ <?php echo $diferencia; ?> </strong></center>
                </div>
            </div>
        <?php
        }
        ?>

        <?php
        if (isset($_POST['btnserv'])) {
            $serv = mysqli_real_escape_string($conexion, (strip_tags($_POST["serv"], ENT_QUOTES)));
            $cant = mysqli_real_escape_string($conexion, (strip_tags($_POST["cant"], ENT_QUOTES)));
            $fecha_actual = date("Y-m-d H:i:s");

            // Fetch serv_costo based on the selected service ID
            $resultado_serv = $conexion->query("SELECT serv_costo FROM cat_servicios WHERE id_serv = $serv") or die($conexion->error);
            $precio = 0;
            if ($row_serv = $resultado_serv->fetch_assoc()) {
                $precio = $row_serv['serv_costo'];
            }

            if ($serv == 1 || $serv == 2 || $serv == 3 || $serv == 4 || $serv == 8 || $serv == 11) {
                $sql_dia_hab = "UPDATE dat_ingreso SET fecha_cama='$fecha_actual' WHERE id_atencion = $id_atencion";
                $result_dia_hab = $conexion->query($sql_dia_hab);
                $sql2 = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant, cta_tot, id_usua, cta_activo,centro_cto)VALUES($id_atencion,'S', $serv,'$fecha_actual',$cant,$precio,$usuario1,'SI','$area')";
                $result = $conexion->query($sql2);
            } else {
                $sql2 = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant, cta_tot, id_usua, cta_activo,centro_cto)VALUES($id_atencion,'S', $serv,'$fecha_actual',$cant,$precio,$usuario1,'SI','$area')";
                $result = $conexion->query($sql2);
            }
            echo '<script type="text/javascript"> window.location.href="detalle_cuenta.php?id_at=' . $id_atencion . '&id_exp=' . $id_exp . '&id_usua=' . $usuario1 . '&rol=' . $rol . '";</script>';
        }
        ?>

        <div class="container box">
            <div class="content">
                <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                    <tr><strong>
                            <center>AGREGAR PAGOS A LA CUENTA
                        </strong>
                </div>
                <form class="form-horizontal" id="fff" name="depocta" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Forma de pago: </label>
                            <select name="banco" class="form-control">
                                <option value="">Seleccionar</option>
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                <option value="DEPOSITO">DEPOSITO</option>
                                <option value="TARJETA">TARJETA</option>
                                <option value="ASEGURADORA">ASEGURADORA</option>
                                <option value="CUENTAS POR COBRAR">CUENTAS POR COBRAR</option>
                                <option value="COASEGURO">COASEGURO</option>
                                <option value="DESCUENTO">DESCUENTO</option>
                                <option value="DEDUCIBLE">DEDUCIBLE</option>
                                <option value="COASEGURO H.">COASEGURO H.</option>
                                <option value="COPAGO">COPAGO</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="resp">Detalle: </label><br>
                            <input type="text" name="resp" placeholder="Banco, tipo de tarjeta, No. de tarjeta, etc." id="resp" maxlength="60" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Cantidad $:</label>
                            <input type="text" name="deposito" class="form-control" onkeypress="return SoloNumeros(event);" class="form-control">
                        </div>
                    </div>
                    <br>
                    <center>
                        <input type="submit" name="btndatfin" id="depocta" class="btn btn-block btn-success col-sm-3" value="GUARDAR DEPÓSITO">
                    </center>
                </form>

                <hr class="new4">
                <div class="form-group">
                    <input type="text" class="form-control pull-right" style="width:20%" id="search_dep" placeholder="Buscar...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mytable">
                        <thead class="thead" style="background-color: #2b2d7f; color: white;">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Forma de pago</th>
                                <th>Detalle</th>
                                <th>Cantidad</th>
                                <th>Personal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $usuario = $_SESSION['login'];
                            $id_usua = $usuario['id_usua'];
                            $resultado4 = $conexion->query("SELECT * FROM dat_financieros df,reg_usuarios reg where df.id_atencion = $id_atencion and df.id_usua=reg.id_usua") or die($conexion->error);
                            $total_dep = 0;
                            $no = 1;
                            while ($row4 = $resultado4->fetch_assoc()) {
                                $fecha = date_create($row4['fecha']);
                                $id_datfin = $row4['id_datfin'];
                                echo '<tr>'
                                    . '<td>' . $no . '</td>'
                                    . '<td>' . date_format($fecha, "d-m-Y H:i:s") . '</td>'
                                    . '<td>' . $row4['banco'] . '</td>'
                                    . '<td>' . $row4['resp'] . '</td>'
                                    . '<td>$ ' . number_format($row4['deposito'], 2) . '</td>'
                                    . '<td>' . $row4['papell'] . ' ' . $row4['sapell'] . '</td>';
                                if ($rol == 5 || $rol == 1) {
                                    echo '<td><a type="submit" class="btn btn-danger btn-sm" href="pdf_deposito.php?id_exp=' . $id_exp . '&id_atencion=' . $id_atencion . '&id_datfin=' . $row4['id_datfin'] . '&id_usua=' . $id_usua . '" target="_blank"><span class="fa fa-file-pdf-o"></span></a>
                                    <a type="submit" class="btn btn-danger btn-sm" href="elimina_detallecta.php?q=del_dep&id_datfin=' . $row4['id_datfin'] . '&id_at=' . $id_atencion . '&id_exp=' . $id_exp . '&id_usua=' . $usuario1 . '&rol=' . $rol . '"><span class="fa fa-trash"></span></a></td>';
                                } else {
                                    echo '<td><strong><a type="submit" class="btn btn-danger btn-sm" href="pdf_deposito.php?id_exp=' . $id_exp . '&id_atencion=' . $id_atencion . '&id_datfin=' . $row4['id_datfin'] . '&id_usua=' . $id_usua . '" target="_blank"><span class="fa fa-file-pdf-o"></span></a></strong></td>';
                                }
                                echo '</tr>';
                                $total_dep = $row4['deposito'] + $total_dep;
                                $no++;
                            }
                            ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total</td>
                            <td><?php echo "$ " . number_format($total_dep, 2); ?></td>
                            <td></td>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            if (isset($_POST['btndatfin'])) {
                $usuario = $_SESSION['login'];
                $id_usua = $usuario['id_usua'];
                $resp = mysqli_real_escape_string($conexion, (strip_tags($_POST["resp"], ENT_QUOTES)));
                $dir_resp = mysqli_real_escape_string($conexion, (strip_tags($_POST["dir_resp"], ENT_QUOTES)));
                $tel = mysqli_real_escape_string($conexion, (strip_tags($_POST["tel"], ENT_QUOTES)));
                $banco = mysqli_real_escape_string($conexion, (strip_tags($_POST["banco"], ENT_QUOTES)));
                $deposito = mysqli_real_escape_string($conexion, (strip_tags($_POST["deposito"], ENT_QUOTES)));
                require_once "CifrasEnLetras.php";
                $v = new CifrasEnLetras();
                $letra = ($v->convertirEurosEnLetras($deposito));
                $dep_l = strtoupper($letra);
                $fecha_actual = date("Y-m-d H:i:s");
                if ($banco == "DEVOLUCION" && $deposito < 0) {
                    $sql_df = "INSERT INTO dat_financieros(id_atencion,aseg,resp,dir_resp,tel,aval,banco,deposito,dep_l,fec_deposito,total_cta,saldo,fecha,id_usua)values($id_atencion,'NINGUNA','$resp','$dir_resp','$tel','$resp','$banco',$deposito,'$dep_l','$fecha_actual',0.00,0.00,'$fecha_actual',$id_usua)";
                    $result_df = $conexion->query($sql_df);
                } elseif ($banco == "DEVOLUCION" && $deposito > 0) {
                    $deposito = $deposito * -1;
                    $sql_df = "INSERT INTO dat_financieros(id_atencion,aseg,resp,dir_resp,tel,aval,banco,deposito,dep_l,fec_deposito,total_cta,saldo,fecha,id_usua)values($id_atencion,'NINGUNA','$resp','$dir_resp','$tel','$resp','$banco',$deposito,'$dep_l','$fecha_actual',0.00,0.00,'$fecha_actual',$id_usua)";
                    $result_df = $conexion->query($sql_df);
                } else {
                    $sql_df = "INSERT INTO dat_financieros(id_atencion,aseg,resp,dir_resp,tel,aval,banco,deposito,dep_l,fec_deposito,total_cta,saldo,fecha,id_usua)values($id_atencion,'NINGUNA','$resp','$dir_resp','$tel','$resp','$banco',$deposito,'$dep_l','$fecha_actual',0.00,0.00,'$fecha_actual',$id_usua)";
                    $result_df = $conexion->query($sql_df);
                }
                if ($result_df) {
                    echo '<script type="text/javascript"> window.location.href="detalle_cuenta.php?id_at=' . $id_atencion . '&id_exp=' . $id_exp . '&id_usua=' . $usuario1 . '&rol=' . $rol . '";</script>';
                } else {
                    echo $sql_df;
                    echo '<h1>ERROR AL INSERTAR';
                }
            }
            ?>
    </section>
    <footer class="main-footer">
        <?php
        include("../../template/footer.php");
        ?>
    </footer>
    <?php
    if (isset($_POST['btnserv_otros'])) {
        $desc_otros = mysqli_real_escape_string($conexion, (strip_tags($_POST["desc_otros"], ENT_QUOTES)));
        $cant_otros = mysqli_real_escape_string($conexion, (strip_tags($_POST["cant_otros"], ENT_QUOTES)));
        $precio_otros = mysqli_real_escape_string($conexion, (strip_tags($_POST["precio_otros"], ENT_QUOTES)));
        $fecha_actual = date("Y-m-d H:i:s");
        $sql5 = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant, cta_tot, id_usua, cta_activo,centro_cto)VALUES($id_atencion,'$desc_otros', 0,'$fecha_actual',$cant_otros,$precio_otros,$usuario1,'SI','$area')";
        $result = $conexion->query($sql5);
        echo '<script type="text/javascript"> window.location.href="detalle_cuenta.php?id_at=' . $id_atencion . '&id_exp=' . $id_exp . '&id_usua=' . $usuario1 . '&rol=' . $rol . '";</script>';
    }
    ?>
    <script type="text/javascript">
        function mostrar(value) {
            if (value == "135" || value == true) {
                document.getElementById('div1').style.display = 'block';
            } else if (value != "135" || value == false) {
                document.getElementById('div1').style.display = 'none';
            }
        }
    </script>
    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>

</html>