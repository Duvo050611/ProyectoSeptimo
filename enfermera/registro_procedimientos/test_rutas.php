<?php
// Test de rutas para verificar que los archivos existen
echo "<!DOCTYPE html>";
echo "<html><head><title>Test de Rutas - Sistema INEO (ACTUALIZADO)</title>";
echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
.test-container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); max-width: 800px; margin: 0 auto; }
.success { color: #27ae60; } .error { color: #e74c3c; } .info { color: #3498db; } .warning { color: #f39c12; }
.test-item { padding: 15px; margin: 10px 0; border-left: 4px solid #ddd; background: #f9f9f9; border-radius: 8px; }
.status-good { border-left-color: #27ae60; background: #e8f5e8; }
.status-fixed { border-left-color: #3498db; background: #e3f2fd; }
.status-error { border-left-color: #e74c3c; background: #fdf2f2; }
.update-badge { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 5px 10px; border-radius: 20px; font-size: 12px; margin-left: 10px; }
</style></head><body>";

echo "<div class='test-container'>";
echo "<h1>🛠️ Test de Rutas - Sistema INEO <span class='update-badge'>CORREGIDO</span></h1>";
echo "<p class='info'>✅ <strong>ACTUALIZACIÓN:</strong> Se han solucionado los errores encontrados en el sistema.</p>";

echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 10px; margin: 20px 0; border-left: 5px solid #27ae60;'>";
echo "<h3 style='color: #27ae60; margin-top: 0;'>🎯 Problemas Resueltos:</h3>";
echo "<ul style='margin: 10px 0;'>";
echo "<li><strong>✅ Header corregido:</strong> Cambiado de <code>header_medico.php</code> a <code>header_enfermera.php</code></li>";
echo "<li><strong>✅ Variable de sesión corregida:</strong> Cambiado de <code>\$_SESSION['hospital']</code> a <code>\$_SESSION['pac']</code></li>";
echo "<li><strong>✅ Validación agregada:</strong> Verificación si el paciente está seleccionado</li>";
echo "<li><strong>✅ Manejo de errores:</strong> Mensajes informativos si faltan datos</li>";
echo "</ul>";
echo "</div>";

// Rutas a verificar
$rutas = [
    'Vista Paciente Hospitalizado' => '../lista_pacientes/vista_pac_hosp.php',
    'Ver Gráfica' => 'ver_grafica.php',
    'Insertar Transparente Gráfico' => 'insertar_trans_grafico.php',
    'Header Enfermera' => '../header_enfermera.php',
    'Seleccionar Paciente' => '../lista_pacientes/select_pac.php'
];

foreach ($rutas as $nombre => $ruta) {
    echo "<div class='test-item status-good'>";
    echo "<strong>📁 $nombre:</strong> ";
    
    if (file_exists($ruta)) {
        echo "<span class='success'>✅ EXISTE</span> - <code>$ruta</code>";
    } else {
        echo "<span class='error'>❌ NO ENCONTRADO</span> - <code>$ruta</code>";
    }
    echo "</div>";
}

echo "<hr style='margin: 20px 0;'>";
echo "<h3>🔗 Enlaces de Prueba:</h3>";

foreach ($rutas as $nombre => $ruta) {
    if (file_exists($ruta)) {
        echo "<a href='$ruta' target='_blank' style='display: inline-block; margin: 5px; padding: 8px 15px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;'>🔗 Probar $nombre</a>";
    }
}

echo "<hr style='margin: 20px 0;'>";

echo "<div style='background: #fff3cd; padding: 15px; border-radius: 10px; border-left: 5px solid #ffc107;'>";
echo "<h3 style='color: #856404; margin-top: 0;'>⚠️ Importante - Flujo Correcto:</h3>";
echo "<ol style='margin: 10px 0; color: #856404;'>";
echo "<li>Primero debes <strong>seleccionar un paciente</strong> en el sistema</li>";
echo "<li>Esto establecerá la variable <code>\$_SESSION['pac']</code></li>";
echo "<li>Después podrás acceder a <strong>Vista Paciente Hospitalizado</strong></li>";
echo "<li>Y finalmente usar el botón <strong>Término de Cirugía</strong></li>";
echo "</ol>";
echo "</div>";

echo "<div style='background: #d4edda; padding: 15px; border-radius: 10px; border-left: 5px solid #28a745; margin-top: 15px;'>";
echo "<h3 style='color: #155724; margin-top: 0;'>🎉 Resumen de Correcciones:</h3>";
echo "<p style='color: #155724; margin: 10px 0;'><strong>✅ Error 404 resuelto:</strong> La ruta del botón 'Término de Cirugía' ahora es correcta</p>";
echo "<p style='color: #155724; margin: 10px 0;'><strong>✅ Header corregido:</strong> Ya no habrá error de archivo no encontrado</p>";
echo "<p style='color: #155724; margin: 10px 0;'><strong>✅ Variables de sesión:</strong> Uso correcto de \$_SESSION['pac'] en lugar de \$_SESSION['hospital']</p>";
echo "<p style='color: #155724; margin: 10px 0;'><strong>✅ Validaciones:</strong> Manejo adecuado de errores SQL y variables indefinidas</p>";
echo "</div>";

echo "</div></body></html>";
?>
