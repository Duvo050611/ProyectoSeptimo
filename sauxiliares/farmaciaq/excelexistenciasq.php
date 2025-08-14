<?php
// Iniciar el buffer de salida para evitar problemas con headers
ob_start();

// Limpiar cualquier salida previa
if (ob_get_contents()) {
    ob_end_clean();
}

include('../../conexionbd.php');

// Verificar conexión a la base de datos
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$filename = 'existencias_quirofano_' . date('Ymd_His') . '.xls';

// Headers para forzar descarga del archivo Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

echo "\xEF\xBB\xBF"; // BOM para UTF-8

echo "<table>";
echo "<tr>";
echo "<td colspan='9' style='text-align: center; font-size: 20px; font-weight: bold;'>EXISTENCIAS DE QUIROFANO</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='9' style='text-align: center; font-size: 14px;'>Fecha de exportación: " . date('d/m/Y H:i:s') . "</td>";
echo "</tr>";
echo "</table>";

echo "<br>";

echo "<table border='1'>";
echo "<tr style='background-color: #2b2d7f; color: white; text-align: center; font-weight: bold;'>";
echo "<th>Código</th>";
echo "<th>Medicamento / Insumo</th>";
echo "<th>Lote</th>";
echo "<th>Caducidad</th>";
echo "<th>Máximo</th>";
echo "<th>P.Reorden</th>";
echo "<th>Mínimo</th>";
echo "<th>Existencias</th>";
echo "<th>Ubicación</th>";
echo "</tr>";

// Consulta corregida usando la misma lógica que existenciasq.php
$query = "SELECT ia.*, ea.*, it.item_type_desc 
          FROM item_almacen ia 
          LEFT JOIN existencias_almacenq ea ON ia.item_id = ea.item_id 
          LEFT JOIN item_type it ON it.item_type_id = ia.item_type_id 
          WHERE ea.existe_qty IS NOT NULL AND ea.existe_qty > 0
          ORDER BY ia.item_id";

$result = $conexion->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Procesar datos de forma segura
        $existencias = intval($row['existe_qty'] ?? 0);
        $maximo = intval($row['item_max'] ?? 0);
        $minimo = intval($row['item_min'] ?? 0);
        $reordena = intval($row['reorden'] ?? 0);
        
        // Manejar fecha de caducidad
        $caduca_str = "N/A";
        if (!empty($row['existe_caducidad'])) {
            $caduca = date_create($row['existe_caducidad']);
            if ($caduca) {
                $caduca_str = date_format($caduca, "d/m/Y");
            }
        }
        
        // Obtener ubicación
        $ubicacion = "Sin ubicación";
        if (!empty($row['ubicacion_id'])) {
            $result3 = $conexion->query("SELECT nombre_ubicacion FROM ubicaciones_almacen WHERE ubicacion_id = " . intval($row['ubicacion_id']));
            if ($result3 && $result3->num_rows > 0) {
                $row3 = $result3->fetch_assoc();
                $ubicacion = $row3['nombre_ubicacion'] ?? 'Sin ubicación';
            }
        }
        
        // Nombre completo del medicamento
        $nombre_completo = ($row['item_name'] ?? '') . 
                         (!empty($row['item_grams']) ? ', ' . $row['item_grams'] : '') . 
                         (!empty($row['item_type_desc']) ? ', ' . $row['item_type_desc'] : '');
        
        // Determinar color de fondo según el nivel de existencias
        $bg_color = '#ffffff';
        if ($existencias >= $maximo) {
            $bg_color = '#d4edda'; // Verde claro
        } else if ($existencias <= $minimo) {
            $bg_color = '#f8d7da'; // Rojo claro
        } else if ($existencias <= $reordena) {
            $bg_color = '#fff3cd'; // Amarillo claro
        }
        
        echo "<tr>";
        echo "<td style='text-align: center; background-color: #f2f2f2;'>" . htmlspecialchars($row['item_code'] ?? '') . "</td>";
        echo "<td style='text-align: left; background-color: #ffffff;'>" . htmlspecialchars($nombre_completo) . "</td>";
        echo "<td style='text-align: center; background-color: #f2f2f2;'>" . htmlspecialchars($row['existe_lote'] ?? '') . "</td>";
        echo "<td style='text-align: center; background-color: #ffffff;'>" . $caduca_str . "</td>";
        echo "<td style='text-align: center; background-color: #f2f2f2;'>" . $maximo . "</td>";
        echo "<td style='text-align: center; background-color: #ffffff;'>" . $reordena . "</td>";
        echo "<td style='text-align: center; background-color: #f2f2f2;'>" . $minimo . "</td>";
        echo "<td style='text-align: center; background-color: $bg_color; font-weight: bold;'>" . $existencias . "</td>";
        echo "<td style='text-align: center; background-color: #ffffff;'>" . htmlspecialchars($ubicacion) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9' style='text-align: center; font-weight: bold; background-color: #f8d7da;'>No hay registros de existencias disponibles</td></tr>";
}

echo "</table>";

// Agregar información de totales al final
echo "<br>";
echo "<table>";
echo "<tr>";
echo "<td style='font-size: 12px; color: #666;'>Total de medicamentos: " . ($result ? $result->num_rows : 0) . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td style='font-size: 12px; color: #666;'>Sistema: INEO - Quirofano</td>";
echo "</tr>";
echo "</table>";

$conexion->close();

// Limpiar el buffer y enviar la salida
ob_end_flush();
?>
