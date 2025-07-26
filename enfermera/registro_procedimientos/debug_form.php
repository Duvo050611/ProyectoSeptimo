<?php
// Archivo de debug para ver qué datos llegan del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si la cirugía está terminada (simulación)
    $cirugiaTerminada = false;
    if (isset($_POST['cirugia_terminada']) && $_POST['cirugia_terminada'] === '1') {
        $cirugiaTerminada = true;
    }
    // Si la cirugía está terminada, mostrar mensaje elegante
    if ($cirugiaTerminada) {
        echo "<!DOCTYPE html>\n<html lang='es'>\n<head>\n<meta charset='UTF-8'>\n<title>Cirugía Terminada</title>\n<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>\n<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>\n<style>\nbody { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }\n.card-blocked { background: #ffeaa7; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); padding: 40px 30px; text-align: center; max-width: 500px; }\n.card-blocked h2 { color: #2d3436; margin-bottom: 20px; }\n.card-blocked p { color: #636e72; font-size: 18px; margin-bottom: 10px; }\n.card-blocked .icon { font-size: 60px; color: #e17055; margin-bottom: 20px; }\n</style>\n</head>\n<body>\n<div class='card-blocked'>\n<div class='icon'><i class='fas fa-lock'></i> <i class='fas fa-ban'></i></div>\n<h2>🚫 Cirugía Terminada</h2>\n<p>Los signos vitales han sido <strong>bloqueados</strong> porque la cirugía ha sido marcada como terminada.</p>\n<p>Solo puedes visualizar los registros existentes. No se pueden agregar nuevos signos vitales.</p>\n<a href='javascript:history.back()' class='btn btn-primary mt-3'><i class='fas fa-arrow-left'></i> Volver</a>\n</div>\n</body>\n</html>";
        exit;
    }
    echo "<h2>Datos recibidos via POST:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>Validación individual de campos:</h3>";
    $campos = ['sistg', 'diastg', 'fcardg', 'frespg', 'satg', 'tempg', 'hora_signos'];
    
    foreach ($campos as $campo) {
        $valor = $_POST[$campo] ?? 'NO ENVIADO';
        $vacio = empty($valor) ? 'SÍ' : 'NO';
        echo "Campo '{$campo}': '{$valor}' - ¿Vacío? {$vacio}<br>";
    }
    
    echo "<hr>";
    echo "<a href='javascript:history.back()'>Volver</a>";
} else {
    echo "No se recibieron datos POST";
}
?>
