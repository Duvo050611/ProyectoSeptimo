<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=servicios_".date("Ymd_His").".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<head>
    <meta charset="UTF-8" />
</head>
<table border="1" cellpadding="5" cellspacing="0">

    <thead style="background-color: #0c675e; color: white;">
        <tr>
            <th>Id</th>
            <th>Clave</th>
            <th>Descripción</th>
            <th>Precio 1</th>
            <th>Precio 2 <br> AXA</th>
            <th>Precio 3 <br> GNP</th>
            <th>Precio 4 <br> Rentas</th>
            <th>Precio 5</th>
            <th>Precio 6</th>
            <th>Precio 7</th>
            <th>Precio 8</th>
            <th>U.M.</th>
            <th>Tipo</th>
            <th>Tipo Insumo</th>
            <th>Proveedor</th>
            <th>Grupo</th>
            <th>Código SAT</th>
            <th>Clave Unidad</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include '../../conexionbd.php';
        $resultado2 = $conexion->query("SELECT s.*, t.ser_type_desc AS tipo, COALESCE(p.nom_prov, s.proveedor) AS nom_prov 
        FROM cat_servicios s 
        JOIN service_type t ON s.tipo = t.ser_type_id 
        LEFT JOIN proveedores p ON s.proveedor = p.id_prov 
        ORDER BY s.id_serv") or die($conexion->error);


        $no = 1;
        while ($row = $resultado2->fetch_assoc()) {
            $eid = $row['id_serv'];

            echo '<tr>'
                . '<td>' . $row['id_serv'] . '</td>'
                . '<td>' . $row['serv_cve'] . '</td>'
                . '<td>' . $row['serv_desc'] . '</td>'
                . '<td>$ ' . number_format($row['serv_costo'], 2) . '</td>'
                . '<td>$ ' . number_format($row['serv_costo2'], 2) . '</td>'
                . '<td>$ ' . number_format($row['serv_costo3'], 2) . '</td>'
                . '<td>$ ' . number_format($row['serv_costo4'], 2) . '</td>'
                . '<td>$ ' . number_format($row['serv_costo5'], 2) . '</td>'
                . '<td>$ ' . number_format($row['serv_costo6'], 2) . '</td>'
                . '<td>$ ' . number_format($row['serv_costo7'], 2) . '</td>'
                . '<td>$ ' . number_format($row['serv_costo8'], 2) . '</td>'
                . '<td>' . $row['serv_umed'] . '</td>'
                . '<td>' . $row['tipo'] . '</td>'
                . '<td>' . $row['tip_insumo'] . '</td>'
                . '<td>' . $row['nom_prov'] . '</td>'
                . '<td>' . $row['grupo'] . '</td>'
                . '<td>' . $row['codigo_sat'] . '</td>'
                . '<td>' . $row['c_cveuni'] . '</td>'
                . '</tr>';
            $no++;
        }
        ?>
    </tbody>
</table>