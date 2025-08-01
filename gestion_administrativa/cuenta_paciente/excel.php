<?php 
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=cuenta_".$id_atencion."_".date("Ymd_His").".xls");
header("Pragma: no-cache");
header("Expires: 0");
include '../../conexionbd.php';
$id_atencion = mysqli_real_escape_string($conexion, @$_GET['id_atencion']);
$id_usua_log = mysqli_real_escape_string($conexion, @$_GET['id_usua']);
?>
<head>
    <meta charset="UTF-8"/>
</head>
<table border="1" cellpadding="5" cellspacing="0">
    <thead style="background-color: #0c675e; color: white;">
        <tr>
            <th>No.</th>
            <th>Fecha registro</th>
            <th>U. de medida</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Subtotal</th>
            <th>Lote</th>
            <th>Caducidad</th>
        </tr>
    </thead>
    <tbody>
    <?php
    include '../../conexionbd.php';

    $date = date("d/m/Y");
    $sql_aseg = "SELECT aseg FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_aseg = $conexion->query($sql_aseg);
    $aseg = '';
    $at = '';
    if ($row_aseg = $result_aseg->fetch_assoc()) {
        $aseg = $row_aseg['aseg'];
        $at = $row_aseg['aseg'];
    }
    $resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg = '$at'") or die($conexion->error);
    $tr = 1; // Default price type
    if ($filat = $resultadot->fetch_assoc()) {
        $tr = $filat["tip_precio"];
    }

    $resultado3 = $conexion->query("SELECT dc.*, p.papell, p.sapell, p.nom_pac 
                                   FROM dat_ctapac dc 
                                   JOIN dat_ingreso di ON dc.id_atencion = di.id_atencion 
                                   JOIN paciente p ON di.Id_exp = p.Id_exp 
                                   WHERE dc.id_atencion = $id_atencion 
                                   ORDER BY dc.cta_fec ASC") or die($conexion->error);
    $total = 0;
    $no = 1;
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
            $resultado_servi = $conexion->query("SELECT serv_desc, serv_umed, codigo_sat FROM cat_servicios WHERE id_serv = $insumo") or die($conexion->error);
            if ($row_servi = $resultado_servi->fetch_assoc()) {
                $descripcion = $row_servi['serv_desc'];
                $umed = $row_servi['serv_umed'];
                $precio = $precioh;
                $iva = 0.00;
            } else {
                $descripcion = "Servicio no encontrado";
                $umed = "N/A";
                $precio = $precioh;
                $iva = 0.00;
            }
        } elseif ($flag == 'S') {
            $resultado_serv = $conexion->query("SELECT serv_desc, serv_umed, serv_costo, serv_costo2, serv_costo3, serv_costo4, tipo, codigo_sat 
                                               FROM cat_servicios WHERE id_serv = $insumo") or die($conexion->error);
            if ($row_serv = $resultado_serv->fetch_assoc()) {
                if ($tr == 1) $precio = $row_serv['serv_costo'];
                elseif ($tr == 2) $precio = $row_serv['serv_costo2'];
                elseif ($tr == 3) $precio = $row_serv['serv_costo3'];
                elseif ($tr == 4) $precio = $row_serv['serv_costo4'];
                else $precio = $precioh;
                $descripcion = $row_serv['serv_desc'];
                $umed = $row_serv['serv_umed'];
                $tip_s = $row_serv['tipo'];
                if ($tip_s == '1') $umed = 'LABORATORIO';
                elseif ($tip_s == '2') $umed = 'IMAGENOLOGIA';
                $iva = $precio * 0.16;
            } else {
                $descripcion = "Servicio no encontrado";
                $umed = "N/A";
                $precio = $precioh;
                $iva = $precio * 0.16;
            }
        } elseif ($flag == 'P' || $flag == 'PC') {
            $resultado_prod = $conexion->query("SELECT i.item_name, it.item_type_desc, i.codigo_sat 
                                               FROM item i 
                                               JOIN item_type it ON i.item_type_id = it.item_type_id 
                                               WHERE i.item_id = $insumo") or die($conexion->error);
            if ($row_prod = $resultado_prod->fetch_assoc()) {
                $descripcion = $row_prod['item_name'];
                $umed = ($flag == 'P') ? 'FARMACIA, ' . $row_prod['item_type_desc'] : 'QUIRÓFANO, ' . $row_prod['item_type_desc'];
                $precio = $precioh;
                $iva = $precio * 0.16;
            } else {
                $descripcion = "Producto no encontrado";
                $umed = "N/A";
                $precio = $precioh;
                $iva = $precio * 0.16;
            }
        }

        $precio = $precio + $iva;
        $subtottal = $precio * $cant;
        $total += $subtottal;
        $fecha_cta = date_create($row3['cta_fec']);
        echo '<tr>'
            . '<td>' . $no . '</td>'
            . '<td>' . date_format($fecha_cta, 'd/m/Y') . '</td>'
            . '<td>' . $umed . '</td>'
            . '<td>' . $descripcion . '</td>'
            . '<td>' . $cant . '</td>'
            . '<td>' . number_format($precio, 2) . '</td>'
            . '<td>' . number_format($subtottal, 2) . '</td>'
            . '<td>' . ($existe_lote ?? '') . '</td>'
            . '<td>' . ($existe_caducidad ? date_format(date_create($existe_caducidad), 'd/m/Y') : '') . '</td>'
            . '</tr>';
        $no++;
    }
    echo '<tr>'
        . '<td colspan="5"></td>'
        . '<td><strong>Total</strong></td>'
        . '<td><strong>' . number_format($total, 2) . '</strong></td>'
        . '<td></td>'
        . '<td></td>'
        . '</tr>';
    ?>
    </tbody>
</table>