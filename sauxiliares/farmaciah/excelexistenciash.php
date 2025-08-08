<?php
include('../../conexionbd.php');

$filename = 'Existencias_Farmacia_' . date('d-m-Y_H-i-s') . '.xls';

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table style='width: 100%; margin-bottom: 20px;'>";
echo "<tr>";
echo "<td colspan='10' style='text-align: center; font-size: 24px; font-weight: bold; color: #2b2d7f; 
    background-color: #f8f9ff; padding: 20px; border: 3px solid #2b2d7f;'>
    REPORTE DE EXISTENCIAS - FARMACIA HOSPITALARIA
</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='10' style='text-align: center; font-size: 14px; color: #666; 
    padding: 10px; background-color: #f5f5f5; border: 1px solid #ddd;'>
    Generado el: " . date('d/m/Y H:i:s') . " | Sistema Hospitalario INEO
</td>";
echo "</tr>";
echo "</table>";

echo "<br>";

echo "<table border='1' style='width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;'>";

echo "<tr style='background-color: #2b2d7f; color: white; height: 45px;'>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 80px;'>ID</th>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 300px;'>MEDICAMENTO / INSUMO</th>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 120px;'>LOTE</th>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 120px;'>FECHA CADUCIDAD</th>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 100px;'>CANTIDAD</th>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 120px;'>FECHA REGISTRO</th>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 150px;'>ESTADO STOCK</th>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 150px;'>UBICACION</th>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 150px;'>ALERTA CADUCIDAD</th>";
echo "<th style='text-align: center; font-weight: bold; font-size: 12px; padding: 8px; width: 120px;'>VALOR ESTIMADO</th>";
echo "</tr>";

$query = "
    SELECT 
        ea.existe_id, 
        ia.item_name,
        ia.item_grams,
        ia.item_min,
        ia.item_max,
        ea.existe_lote, 
        ea.existe_caducidad, 
        ea.existe_qty, 
        ea.existe_fecha,
        ea.ubicacion_id,
        ua.nombre_ubicacion
    FROM 
        existencias_almacenh ea 
    INNER JOIN 
        item_almacen ia ON ea.item_id = ia.item_id 
    LEFT JOIN
        ubicaciones_almacen ua ON ea.ubicacion_id = ua.ubicacion_id
    WHERE 
        ea.existe_qty > 0
    ORDER BY 
        ea.existe_caducidad ASC, ia.item_name ASC
";

$result = $conexion->query($query);
$contador = 0;
$total_items = 0;
$valor_unitario = 15.50;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contador++;
        $total_items += $row['existe_qty'];
        
        $bg_color = ($contador % 2 == 0) ? '#f8f9ff' : '#ffffff';
        
        $fecha_caducidad = new DateTime($row['existe_caducidad']);
        $fecha_actual = new DateTime();
        $diferencia = $fecha_actual->diff($fecha_caducidad);
        $meses_restantes = $diferencia->m + ($diferencia->y * 12);
        
        if ($fecha_caducidad < $fecha_actual) {
            $color_caducidad = '#dc3545'; 
            $alerta_caducidad = 'VENCIDO';
            $text_color_cad = 'white';
        } elseif ($meses_restantes <= 3) {
            $color_caducidad = '#ffc107';
            $alerta_caducidad = 'PROXIMO A VENCER';
            $text_color_cad = 'black';
        } elseif ($meses_restantes <= 6) {
            $color_caducidad = '#fd7e14'; 
            $alerta_caducidad = 'VIGILAR';
            $text_color_cad = 'white';
        } else {
            $color_caducidad = '#28a745';
            $alerta_caducidad = 'VIGENTE';
            $text_color_cad = 'white';
        }
        
        $cantidad = $row['existe_qty'];
        $minimo = $row['item_min'] ? $row['item_min'] : 0;
        $maximo = $row['item_max'] ? $row['item_max'] : 999;
        
        if ($cantidad <= $minimo && $minimo > 0) {
            $estado_stock = 'STOCK BAJO';
            $color_stock = '#dc3545';
            $text_color_stock = 'white';
        } elseif ($cantidad >= $maximo) {
            $estado_stock = 'SOBRECARGADO';
            $color_stock = '#6f42c1';
            $text_color_stock = 'white';
        } else {
            $estado_stock = 'NORMAL';
            $color_stock = '#28a745';
            $text_color_stock = 'white';
        }
        
        $valor_total_item = $cantidad * $valor_unitario;
        
        echo "<tr style='background-color: {$bg_color}; height: 35px;'>";
        echo "<td style='text-align: center; padding: 8px; font-weight: bold; color: #2b2d7f; border: 1px solid #ddd;'>{$row['existe_id']}</td>";
        echo "<td style='text-align: left; padding: 8px; font-weight: 600; border: 1px solid #ddd;'>" . htmlspecialchars($row['item_name'] . ' - ' . $row['item_grams']) . "</td>";
        echo "<td style='text-align: center; padding: 8px; font-family: monospace; border: 1px solid #ddd;'>" . htmlspecialchars($row['existe_lote']) . "</td>";
        echo "<td style='text-align: center; padding: 8px; font-weight: bold; border: 1px solid #ddd;'>" . date('d/m/Y', strtotime($row['existe_caducidad'])) . "</td>";
        echo "<td style='text-align: center; padding: 8px; font-weight: bold; font-size: 14px; color: #2b2d7f; border: 1px solid #ddd;'>{$cantidad}</td>";
        echo "<td style='text-align: center; padding: 8px; border: 1px solid #ddd;'>" . date('d/m/Y', strtotime($row['existe_fecha'])) . "</td>";
        echo "<td style='text-align: center; padding: 8px; font-weight: bold; background-color: {$color_stock}; color: {$text_color_stock}; border: 1px solid #ddd;'>{$estado_stock}</td>";
        echo "<td style='text-align: center; padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($row['nombre_ubicacion'] ? $row['nombre_ubicacion'] : 'Sin ubicacion') . "</td>";
        echo "<td style='text-align: center; padding: 8px; font-weight: bold; background-color: {$color_caducidad}; color: {$text_color_cad}; border: 1px solid #ddd;'>{$alerta_caducidad}</td>";
        echo "<td style='text-align: right; padding: 8px; font-weight: bold; color: #28a745; border: 1px solid #ddd;'>$ " . number_format($valor_total_item, 2) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10' style='text-align: center; font-weight: bold; padding: 20px; color: #dc3545; background-color: #f8d7da; border: 1px solid #f5c6cb;'>
        No se encontraron registros con cantidad mayor a 0
    </td></tr>";
}

$valor_total_inventario = $total_items * $valor_unitario;
echo "<tr style='background-color: #2b2d7f; color: white; height: 40px;'>";
echo "<td colspan='4' style='text-align: center; font-weight: bold; font-size: 14px; padding: 10px; border: 2px solid #fff;'>
    RESUMEN TOTAL
</td>";
echo "<td style='text-align: center; font-weight: bold; font-size: 16px; padding: 10px; border: 2px solid #fff;'>
    {$total_items}
</td>";
echo "<td colspan='3' style='text-align: center; font-weight: bold; font-size: 12px; padding: 10px; border: 2px solid #fff;'>
    Total de registros: {$contador}
</td>";
echo "<td style='text-align: center; font-weight: bold; font-size: 12px; padding: 10px; border: 2px solid #fff;'>
    {$contador} items
</td>";
echo "<td style='text-align: right; font-weight: bold; font-size: 14px; padding: 10px; border: 2px solid #fff;'>
    $ " . number_format($valor_total_inventario, 2) . "
</td>";
echo "</tr>";

echo "</table>";

echo "<br><br>";
echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
echo "<tr style='background-color: #f8f9ff; height: 35px;'>";
echo "<td style='text-align: center; font-weight: bold; font-size: 14px; padding: 10px; color: #2b2d7f;'>ESTADISTICAS DEL INVENTARIO</td>";
echo "</tr>";
echo "<tr>";
echo "<td style='padding: 15px; font-size: 12px;'>";
echo "• Total de items diferentes: {$contador}<br>";
echo "• Cantidad total de unidades: {$total_items}<br>";
echo "• Valor estimado del inventario: $ " . number_format($valor_total_inventario, 2) . "<br>";
echo "• Promedio de unidades por item: " . ($contador > 0 ? number_format($total_items / $contador, 2) : '0') . "<br>";
echo "• Fecha de generacion: " . date('d/m/Y H:i:s') . "<br>";
echo "• Sistema: INEO - Gestion Hospitalaria";
echo "</td>";
echo "</tr>";
echo "</table>";

$conexion->close();
