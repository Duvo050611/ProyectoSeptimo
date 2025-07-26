<?php
session_start();
include "../../conexionbd.php";
include "verificar_cirugia.php"; // Incluir funciones de verificación

if (!isset($_SESSION['pac'])) {
    http_response_code(400);
    echo "Error: No hay paciente seleccionado";
    exit;
}

// VERIFICAR SI LA CIRUGÍA HA TERMINADO
if (cirugiaTerminada($conexion, $_SESSION['pac'])) {
    echo mostrarMensajeBloqueo();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener los datos del formulario
        $id_atencion = $_SESSION['pac'];
        $id_usua = $_SESSION['id_usua'] ?? 1; // Usuario actual
        $id_tratamiento = $_POST['id_tratamiento'] ?? null;
        $sistg = $_POST['sistg'] ?? '';
        $diastg = $_POST['diastg'] ?? '';
        $fcardg = $_POST['fcardg'] ?? '';
        $frespg = $_POST['frespg'] ?? '';
        $satg = $_POST['satg'] ?? '';
        $tempg = $_POST['tempg'] ?? '';
        $hora = $_POST['hora_signos'] ?? '';
        
        // Validar campos requeridos
        if (empty($sistg) || empty($diastg) || empty($fcardg) || empty($frespg) || empty($satg) || empty($tempg) || empty($hora)) {
            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>🩺 Validación de Campos - Sistema Médico INEO</title>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>
                <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap' rel='stylesheet'>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { 
                        font-family: 'Inter', sans-serif; 
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                        min-height: 100vh; 
                        display: flex; 
                        align-items: center; 
                        justify-content: center;
                        overflow: hidden;
                        position: relative;
                    }
                    
                    /* Partículas animadas de fondo */
                    .particles { position: absolute; width: 100%; height: 100%; overflow: hidden; }
                    .particle { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 6s ease-in-out infinite; }
                    .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
                    .particle:nth-child(2) { width: 60px; height: 60px; left: 20%; animation-delay: 2s; }
                    .particle:nth-child(3) { width: 100px; height: 100px; left: 70%; animation-delay: 4s; }
                    .particle:nth-child(4) { width: 40px; height: 40px; left: 80%; animation-delay: 1s; }
                    
                    @keyframes float {
                        0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
                        50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
                    }
                    
                    .notification-container {
                        background: rgba(255, 255, 255, 0.95);
                        backdrop-filter: blur(20px);
                        border-radius: 25px;
                        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
                        padding: 50px 40px;
                        text-align: center;
                        max-width: 500px;
                        width: 90%;
                        animation: slideInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
                        border: 1px solid rgba(255,255,255,0.2);
                        position: relative;
                        overflow: hidden;
                    }
                    
                    .notification-container::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: -100%;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                        animation: shimmer 2s infinite;
                    }
                    
                    @keyframes shimmer {
                        0% { left: -100%; }
                        100% { left: 100%; }
                    }
                    
                    @keyframes slideInUp {
                        0% { transform: translateY(50px); opacity: 0; }
                        100% { transform: translateY(0); opacity: 1; }
                    }
                    
                    .icon-warning {
                        background: linear-gradient(135deg, #ff6b6b, #ffa726);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                        font-size: 80px;
                        margin-bottom: 25px;
                        animation: pulse 2s infinite, bounce 1s ease-out;
                        display: inline-block;
                    }
                    
                    @keyframes pulse {
                        0%, 100% { transform: scale(1); }
                        50% { transform: scale(1.1); }
                    }
                    
                    @keyframes bounce {
                        0% { transform: scale(0) rotate(-180deg); opacity: 0; }
                        50% { transform: scale(1.2) rotate(-90deg); opacity: 0.8; }
                        100% { transform: scale(1) rotate(0deg); opacity: 1; }
                    }
                    
                    .title {
                        background: linear-gradient(135deg, #2c3e50, #34495e);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                        font-size: 32px;
                        font-weight: 700;
                        margin-bottom: 20px;
                        animation: fadeInDown 0.8s ease-out 0.2s both;
                    }
                    
                    @keyframes fadeInDown {
                        0% { transform: translateY(-20px); opacity: 0; }
                        100% { transform: translateY(0); opacity: 1; }
                    }
                    
                    .message {
                        color: #5a6c7d;
                        font-size: 18px;
                        margin-bottom: 35px;
                        line-height: 1.7;
                        font-weight: 400;
                        animation: fadeIn 0.8s ease-out 0.4s both;
                    }
                    
                    @keyframes fadeIn {
                        0% { opacity: 0; }
                        100% { opacity: 1; }
                    }
                    
                    .missing-fields {
                        background: linear-gradient(135deg, #fff5f5, #fee);
                        border: 2px solid #ffcccb;
                        border-radius: 15px;
                        padding: 20px;
                        margin: 25px 0;
                        animation: slideInLeft 0.8s ease-out 0.6s both;
                    }
                    
                    @keyframes slideInLeft {
                        0% { transform: translateX(-30px); opacity: 0; }
                        100% { transform: translateX(0); opacity: 1; }
                    }
                    
                    .field-list {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                        gap: 10px;
                        margin-top: 15px;
                    }
                    
                    .field-item {
                        background: white;
                        padding: 8px 12px;
                        border-radius: 8px;
                        font-size: 14px;
                        color: #e74c3c;
                        font-weight: 500;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                    }
                    
                    .btn-container {
                        display: flex;
                        gap: 15px;
                        justify-content: center;
                        flex-wrap: wrap;
                        animation: slideInUp 0.8s ease-out 0.8s both;
                    }
                    
                    .btn-action {
                        background: linear-gradient(135deg, #667eea, #764ba2);
                        border: none;
                        border-radius: 50px;
                        padding: 15px 30px;
                        color: white;
                        font-weight: 600;
                        font-size: 16px;
                        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
                        cursor: pointer;
                        position: relative;
                        overflow: hidden;
                        min-width: 160px;
                    }
                    
                    .btn-action::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: -100%;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                        transition: left 0.5s;
                    }
                    
                    .btn-action:hover::before { left: 100%; }
                    .btn-action:hover {
                        transform: translateY(-3px) scale(1.05);
                        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
                    }
                    
                    .btn-action:active { transform: translateY(-1px) scale(1.02); }
                    
                    .progress-bar {
                        position: fixed;
                        top: 0;
                        left: 0;
                        height: 4px;
                        background: linear-gradient(90deg, #667eea, #764ba2);
                        animation: progressLoad 3s ease-out;
                        z-index: 1000;
                    }
                    
                    @keyframes progressLoad {
                        0% { width: 0%; }
                        100% { width: 100%; }
                    }
                    
                    @media (max-width: 600px) {
                        .notification-container { padding: 30px 20px; margin: 20px; }
                        .title { font-size: 24px; }
                        .message { font-size: 16px; }
                        .icon-warning { font-size: 60px; }
                        .btn-container { flex-direction: column; }
                        .btn-action { min-width: 100%; }
                    }
                </style>
            </head>
            <body>
                <div class='progress-bar'></div>
                <div class='particles'>
                    <div class='particle'></div>
                    <div class='particle'></div>
                    <div class='particle'></div>
                    <div class='particle'></div>
                </div>
                
                <div class='notification-container'>
                    <i class='fas fa-clipboard-check icon-warning'></i>
                    <div class='title'>📋 Campos Requeridos</div>
                    <div class='message'>Para garantizar un registro médico completo y preciso, todos los campos de signos vitales son obligatorios.</div>
                    
                    <div class='missing-fields'>
                        <h6 style='color: #e74c3c; margin-bottom: 10px;'><i class='fas fa-exclamation-circle'></i> Campos obligatorios:</h6>
                        <div class='field-list'>
                            <div class='field-item'><i class='fas fa-heartbeat'></i> Presión Sistólica</div>
                            <div class='field-item'><i class='fas fa-heartbeat'></i> Presión Diastólica</div>
                            <div class='field-item'><i class='fas fa-heart'></i> Frecuencia Cardíaca</div>
                            <div class='field-item'><i class='fas fa-lungs'></i> Frecuencia Respiratoria</div>
                            <div class='field-item'><i class='fas fa-percentage'></i> Saturación O2</div>
                            <div class='field-item'><i class='fas fa-thermometer-half'></i> Temperatura</div>
                            <div class='field-item'><i class='fas fa-clock'></i> Hora</div>
                        </div>
                    </div>
                    
                    <div class='btn-container'>
                        <button class='btn-action' onclick='window.history.back()'>
                            <i class='fas fa-arrow-left'></i> Completar Campos
                        </button>
                    </div>
                </div>
                
                <script>
                    // Efectos de sonido y vibración (si está disponible)
                    if ('vibrate' in navigator) {
                        navigator.vibrate([200, 100, 200]);
                    }
                    
                    // Auto-focus en el primer campo cuando regrese
                    setTimeout(() => {
                        if (document.referrer) {
                            sessionStorage.setItem('focusFirstField', 'true');
                        }
                    }, 500);
                </script>
            </body>
            </html>";
            exit;
        }
        
        // Limpiar datos de entrada (remover caracteres no numéricos excepto punto decimal)
        $sistg = preg_replace('/[^0-9.]/', '', $sistg);
        $diastg = preg_replace('/[^0-9.]/', '', $diastg);
        $fcardg = preg_replace('/[^0-9.]/', '', $fcardg);
        $frespg = preg_replace('/[^0-9.]/', '', $frespg);
        $satg = str_replace('%', '', preg_replace('/[^0-9.]/', '', $satg));
        $tempg = preg_replace('/[^0-9.]/', '', $tempg);
        
        // Obtener la fecha y hora actual completa
        $fecha_g = date('Y-m-d H:i:s');
        
        // Obtener el siguiente número de cuenta para este paciente y tratamiento
        $sql_cuenta = "SELECT COALESCE(MAX(cuenta), 0) + 1 as siguiente_cuenta 
                       FROM dat_trans_grafico 
                       WHERE id_atencion = ? AND id_tratamiento = ?";
        
        $stmt_cuenta = $conexion->prepare($sql_cuenta);
        $stmt_cuenta->bind_param("ii", $id_atencion, $id_tratamiento);
        $stmt_cuenta->execute();
        $result_cuenta = $stmt_cuenta->get_result();
        $cuenta = $result_cuenta->fetch_assoc()['siguiente_cuenta'];
        $stmt_cuenta->close();
        
        // Insertar los signos vitales en la base de datos
        $sql_insert = "INSERT INTO dat_trans_grafico 
                       (id_atencion, id_usua, id_tratamiento, hora, sistg, diastg, fcardg, frespg, satg, tempg, fecha_g, cuenta) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql_insert);
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }
        
        $stmt->bind_param("iiissssssssi", 
            $id_atencion, 
            $id_usua, 
            $id_tratamiento, 
            $hora, 
            $sistg, 
            $diastg, 
            $fcardg, 
            $frespg, 
            $satg, 
            $tempg, 
            $fecha_g, 
            $cuenta
        );
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // Respuesta exitosa con notificación elegante
            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>✅ Registro Exitoso - Sistema Médico INEO</title>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>
                <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap' rel='stylesheet'>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { 
                        font-family: 'Inter', sans-serif; 
                        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); 
                        min-height: 100vh; 
                        display: flex; 
                        align-items: center; 
                        justify-content: center;
                        overflow: hidden;
                        position: relative;
                    }
                    
                    /* Efecto de confeti */
                    .confetti { position: absolute; width: 10px; height: 10px; background: #f39c12; animation: confetti-fall 3s linear infinite; }
                    .confetti:nth-child(1) { left: 10%; animation-delay: 0s; background: #e74c3c; }
                    .confetti:nth-child(2) { left: 20%; animation-delay: 0.5s; background: #3498db; }
                    .confetti:nth-child(3) { left: 30%; animation-delay: 1s; background: #2ecc71; }
                    .confetti:nth-child(4) { left: 40%; animation-delay: 1.5s; background: #9b59b6; }
                    .confetti:nth-child(5) { left: 50%; animation-delay: 2s; background: #f39c12; }
                    .confetti:nth-child(6) { left: 60%; animation-delay: 0.3s; background: #e67e22; }
                    .confetti:nth-child(7) { left: 70%; animation-delay: 0.8s; background: #1abc9c; }
                    .confetti:nth-child(8) { left: 80%; animation-delay: 1.3s; background: #34495e; }
                    .confetti:nth-child(9) { left: 90%; animation-delay: 1.8s; background: #e91e63; }
                    
                    @keyframes confetti-fall {
                        0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
                        100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
                    }
                    
                    /* Ondas de fondo */
                    .wave {
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        width: 100%;
                        height: 100px;
                        background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 1200 120\" preserveAspectRatio=\"none\"><path d=\"M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z\" fill=\"%23ffffff\" fill-opacity=\"0.1\"/></svg>') repeat-x;
                        animation: wave 10s ease-in-out infinite;
                    }
                    
                    @keyframes wave {
                        0%, 100% { transform: translateX(0px); }
                        50% { transform: translateX(-50px); }
                    }
                    
                    .notification-container {
                        background: rgba(255, 255, 255, 0.98);
                        backdrop-filter: blur(25px);
                        border-radius: 30px;
                        box-shadow: 0 30px 60px rgba(0,0,0,0.2);
                        padding: 60px 50px;
                        text-align: center;
                        max-width: 600px;
                        width: 90%;
                        animation: successEntry 1.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                        border: 3px solid rgba(255,255,255,0.3);
                        position: relative;
                        overflow: hidden;
                    }
                    
                    .notification-container::before {
                        content: '';
                        position: absolute;
                        top: -50%;
                        left: -50%;
                        width: 200%;
                        height: 200%;
                        background: conic-gradient(from 0deg, transparent, rgba(46, 204, 113, 0.1), transparent);
                        animation: rotate 4s linear infinite;
                        z-index: -1;
                    }
                    
                    @keyframes rotate {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                    
                    @keyframes successEntry {
                        0% { transform: scale(0.3) rotate(-10deg); opacity: 0; }
                        50% { transform: scale(1.1) rotate(5deg); opacity: 0.8; }
                        100% { transform: scale(1) rotate(0deg); opacity: 1; }
                    }
                    
                    .icon-success {
                        background: linear-gradient(135deg, #27ae60, #2ecc71);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                        font-size: 100px;
                        margin-bottom: 30px;
                        animation: successPulse 2s infinite, checkmarkDraw 1s ease-out;
                        display: inline-block;
                        position: relative;
                    }
                    
                    .icon-success::after {
                        content: '';
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        width: 120px;
                        height: 120px;
                        border: 3px solid #27ae60;
                        border-radius: 50%;
                        transform: translate(-50%, -50%);
                        animation: circleExpand 1s ease-out;
                        opacity: 0.3;
                    }
                    
                    @keyframes successPulse {
                        0%, 100% { transform: scale(1); }
                        50% { transform: scale(1.05); }
                    }
                    
                    @keyframes checkmarkDraw {
                        0% { transform: scale(0) rotate(-45deg); }
                        50% { transform: scale(1.2) rotate(-22.5deg); }
                        100% { transform: scale(1) rotate(0deg); }
                    }
                    
                    @keyframes circleExpand {
                        0% { transform: translate(-50%, -50%) scale(0); opacity: 0.8; }
                        100% { transform: translate(-50%, -50%) scale(1); opacity: 0.3; }
                    }
                    
                    .title {
                        background: linear-gradient(135deg, #2c3e50, #27ae60);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                        font-size: 36px;
                        font-weight: 800;
                        margin-bottom: 25px;
                        animation: titleSlide 1s ease-out 0.3s both;
                    }
                    
                    @keyframes titleSlide {
                        0% { transform: translateY(-30px); opacity: 0; }
                        100% { transform: translateY(0); opacity: 1; }
                    }
                    
                    .message {
                        color: #5a6c7d;
                        font-size: 20px;
                        margin-bottom: 40px;
                        line-height: 1.8;
                        font-weight: 400;
                        animation: messageSlide 1s ease-out 0.5s both;
                    }
                    
                    @keyframes messageSlide {
                        0% { transform: translateY(20px); opacity: 0; }
                        100% { transform: translateY(0); opacity: 1; }
                    }
                    
                    .details-card {
                        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                        border-radius: 20px;
                        padding: 30px;
                        margin: 30px 0;
                        box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);
                        animation: detailsExpand 1s ease-out 0.7s both;
                    }
                    
                    @keyframes detailsExpand {
                        0% { transform: scaleY(0); opacity: 0; }
                        100% { transform: scaleY(1); opacity: 1; }
                    }
                    
                    .detail-row {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 12px 0;
                        border-bottom: 1px solid rgba(0,0,0,0.1);
                        animation: detailFade 0.8s ease-out calc(0.8s + var(--delay)) both;
                    }
                    
                    .detail-row:last-child { border-bottom: none; }
                    
                    @keyframes detailFade {
                        0% { transform: translateX(-20px); opacity: 0; }
                        100% { transform: translateX(0); opacity: 1; }
                    }
                    
                    .detail-label {
                        font-weight: 600;
                        color: #2c3e50;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    }
                    
                    .detail-value {
                        background: linear-gradient(135deg, #27ae60, #2ecc71);
                        color: white;
                        padding: 8px 15px;
                        border-radius: 25px;
                        font-weight: 700;
                        font-size: 14px;
                        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
                    }
                    
                    .btn-container {
                        display: flex;
                        gap: 20px;
                        justify-content: center;
                        flex-wrap: wrap;
                        animation: buttonSlide 1s ease-out 0.9s both;
                    }
                    
                    @keyframes buttonSlide {
                        0% { transform: translateY(30px); opacity: 0; }
                        100% { transform: translateY(0); opacity: 1; }
                    }
                    
                    .btn-continue {
                        background: linear-gradient(135deg, #27ae60, #2ecc71);
                        border: none;
                        border-radius: 50px;
                        padding: 18px 40px;
                        color: white;
                        font-weight: 700;
                        font-size: 18px;
                        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
                        cursor: pointer;
                        position: relative;
                        overflow: hidden;
                        min-width: 200px;
                        box-shadow: 0 10px 30px rgba(39, 174, 96, 0.3);
                    }
                    
                    .btn-continue::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: -100%;
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                        transition: left 0.6s;
                    }
                    
                    .btn-continue:hover::before { left: 100%; }
                    .btn-continue:hover {
                        transform: translateY(-5px) scale(1.05);
                        box-shadow: 0 20px 40px rgba(39, 174, 96, 0.5);
                    }
                    
                    .btn-continue:active { transform: translateY(-2px) scale(1.02); }
                    
                    .progress-circle {
                        position: fixed;
                        top: 30px;
                        right: 30px;
                        width: 60px;
                        height: 60px;
                        border: 4px solid rgba(255,255,255,0.3);
                        border-top: 4px solid #27ae60;
                        border-radius: 50%;
                        animation: spin 1s linear infinite;
                    }
                    
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                    
                    .countdown {
                        position: fixed;
                        bottom: 30px;
                        right: 30px;
                        background: rgba(0,0,0,0.8);
                        color: white;
                        padding: 10px 20px;
                        border-radius: 25px;
                        font-weight: 600;
                        animation: countdownPulse 1s infinite;
                    }
                    
                    @keyframes countdownPulse {
                        0%, 100% { transform: scale(1); }
                        50% { transform: scale(1.05); }
                    }
                    
                    @media (max-width: 600px) {
                        .notification-container { padding: 40px 25px; margin: 20px; }
                        .title { font-size: 28px; }
                        .message { font-size: 16px; }
                        .icon-success { font-size: 70px; }
                        .btn-container { flex-direction: column; }
                        .btn-continue { min-width: 100%; }
                        .details-card { padding: 20px; }
                        .detail-row { flex-direction: column; gap: 10px; text-align: center; }
                    }
                </style>
            </head>
            <body>
                <!-- Confeti animado -->
                <div class='confetti'></div>
                <div class='confetti'></div>
                <div class='confetti'></div>
                <div class='confetti'></div>
                <div class='confetti'></div>
                <div class='confetti'></div>
                <div class='confetti'></div>
                <div class='confetti'></div>
                <div class='confetti'></div>
                
                <!-- Onda de fondo -->
                <div class='wave'></div>
                
                <!-- Indicador de progreso -->
                <div class='progress-circle'></div>
                
                <!-- Contador regresivo -->
                <div class='countdown'>
                    <i class='fas fa-clock'></i> Auto-redirección en <span id='countdownNum'>5</span>s
                </div>
                
                <div class='notification-container'>
                    <i class='fas fa-check-circle icon-success'></i>
                    <div class='title'>🎉 ¡Registro Completado!</div>
                    <div class='message'>Los signos vitales han sido registrados exitosamente en el sistema médico. El registro se encuentra disponible para consulta inmediata.</div>
                    
                    <div class='details-card'>
                        <div class='detail-row' style='--delay: 0s;'>
                            <span class='detail-label'>
                                <i class='fas fa-clock'></i> Hora de Registro
                            </span>
                            <span class='detail-value'>" . $hora . "</span>
                        </div>
                        <div class='detail-row' style='--delay: 0.1s;'>
                            <span class='detail-label'>
                                <i class='fas fa-calendar-alt'></i> Fecha Completa
                            </span>
                            <span class='detail-value'>" . date('d/m/Y H:i:s') . "</span>
                        </div>
                        <div class='detail-row' style='--delay: 0.2s;'>
                            <span class='detail-label'>
                                <i class='fas fa-user-md'></i> ID de Atención
                            </span>
                            <span class='detail-value'>#" . $id_atencion . "</span>
                        </div>
                        <div class='detail-row' style='--delay: 0.3s;'>
                            <span class='detail-label'>
                                <i class='fas fa-heartbeat'></i> Estado del Sistema
                            </span>
                            <span class='detail-value'>✅ Activo</span>
                        </div>
                    </div>
                    
                    <div class='btn-container'>
                        <button class='btn-continue' onclick='redirectNow()'>
                            <i class='fas fa-chart-line'></i> Ver Registros
                        </button>
                    </div>
                </div>
                
                <script>
                    let countdown = 5;
                    const countdownElement = document.getElementById('countdownNum');
                    
                    function updateCountdown() {
                        countdownElement.textContent = countdown;
                        countdown--;
                        
                        if (countdown < 0) {
                            redirectNow();
                        }
                    }
                    
                    function redirectNow() {
                        document.querySelector('.progress-circle').style.borderTopColor = '#2ecc71';
                        window.location.href = 'nota_registro_grafico.php';
                    }
                    
                    // Iniciar contador
                    const countdownInterval = setInterval(updateCountdown, 1000);
                    
                    // Efectos de sonido y vibración
                    if ('vibrate' in navigator) {
                        navigator.vibrate([100, 50, 100, 50, 200]);
                    }
                    
                    // Notificación del navegador
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('INEO - Registro Exitoso', {
                            body: 'Los signos vitales han sido registrados correctamente',
                            icon: '/favicon.ico'
                        });
                    }
                    
                    // Limpiar interval al salir
                    window.addEventListener('beforeunload', () => {
                        clearInterval(countdownInterval);
                    });
                </script>
            </body>
            </html>";
            exit;
        } else {
            throw new Exception("Error al insertar los datos: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log("Error en insertar_trans_grafico.php: " . $e->getMessage());
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Error del Sistema</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' rel='stylesheet'>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { 
                    font-family: 'Inter', sans-serif; 
                    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); 
                    min-height: 100vh; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center;
                    position: relative;
                    overflow: hidden;
                }
                
                .glitch-bg {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(45deg, rgba(255,0,0,0.1) 25%, transparent 25%),
                                linear-gradient(-45deg, rgba(255,0,0,0.1) 25%, transparent 25%);
                    background-size: 30px 30px;
                    animation: glitchMove 4s linear infinite;
                    opacity: 0.3;
                }
                
                @keyframes glitchMove {
                    0% { transform: translateX(0); }
                    25% { transform: translateX(-5px); }
                    50% { transform: translateX(5px); }
                    75% { transform: translateX(-3px); }
                    100% { transform: translateX(0); }
                }
                
                .notification-container {
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(20px);
                    border-radius: 25px;
                    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
                    padding: 50px 40px;
                    text-align: center;
                    max-width: 550px;
                    width: 90%;
                    animation: errorShake 1.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
                    border: 2px solid rgba(231, 76, 60, 0.3);
                    position: relative;
                    z-index: 10;
                }
                
                @keyframes errorShake {
                    0% { transform: scale(0.8) rotate(-1deg); opacity: 0; }
                    10% { transform: scale(0.9) rotate(1deg); opacity: 0.5; }
                    20% { transform: scale(1.05) rotate(-1deg); opacity: 0.8; }
                    30% { transform: scale(0.98) rotate(1deg); opacity: 0.9; }
                    40% { transform: scale(1.02) rotate(-0.5deg); opacity: 1; }
                    50% { transform: scale(0.99) rotate(0.5deg); }
                    60% { transform: scale(1.01) rotate(-0.3deg); }
                    70% { transform: scale(0.995) rotate(0.3deg); }
                    80% { transform: scale(1.005) rotate(-0.1deg); }
                    90% { transform: scale(0.998) rotate(0.1deg); }
                    100% { transform: scale(1) rotate(0deg); opacity: 1; }
                }
                
                .icon-error {
                    color: #e74c3c;
                    font-size: 90px;
                    margin-bottom: 25px;
                    animation: errorPulse 2s infinite;
                    display: inline-block;
                }
                
                @keyframes errorPulse {
                    0%, 100% { transform: scale(1); filter: drop-shadow(0 0 10px rgba(231, 76, 60, 0.3)); }
                    50% { transform: scale(1.1); filter: drop-shadow(0 0 20px rgba(231, 76, 60, 0.6)); }
                }
                
                .title {
                    background: linear-gradient(135deg, #c0392b, #e74c3c);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    font-size: 32px;
                    font-weight: 800;
                    margin-bottom: 20px;
                }
                
                .message {
                    color: #5a6c7d;
                    font-size: 18px;
                    margin-bottom: 35px;
                    line-height: 1.7;
                    font-weight: 400;
                }
                
                .error-details {
                    background: linear-gradient(135deg, #fff5f5, #fee);
                    border: 2px solid #ffcccb;
                    border-radius: 15px;
                    padding: 25px;
                    margin: 25px 0;
                    position: relative;
                }
                
                .btn-container {
                    display: flex;
                    gap: 15px;
                    justify-content: center;
                    flex-wrap: wrap;
                }
                
                .btn-action {
                    border: none;
                    border-radius: 50px;
                    padding: 15px 30px;
                    color: white;
                    font-weight: 600;
                    font-size: 16px;
                    transition: all 0.3s;
                    cursor: pointer;
                    min-width: 150px;
                }
                
                .btn-retry {
                    background: linear-gradient(135deg, #e74c3c, #c0392b);
                    box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
                }
                
                .btn-home {
                    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
                    box-shadow: 0 8px 25px rgba(149, 165, 166, 0.3);
                }
                
                .btn-action:hover {
                    transform: translateY(-3px) scale(1.05);
                }
                
                @media (max-width: 600px) {
                    .notification-container { padding: 30px 20px; margin: 20px; }
                    .title { font-size: 24px; }
                    .message { font-size: 16px; }
                    .icon-error { font-size: 60px; }
                    .btn-container { flex-direction: column; }
                    .btn-action { min-width: 100%; }
                }
            </style>
        </head>
        <body>
            <div class='notification-card'>
                <i class='fas fa-exclamation-circle icon-error'></i>
                <div class='title'>Error del Sistema</div>
                <div class='message'>Ha ocurrido un error interno al procesar los signos vitales. Por favor intente nuevamente.</div>
                <div class='error-details'>
                    <i class='fas fa-info-circle'></i> Si el problema persiste, contacte al administrador del sistema.
                </div>
                <button class='btn btn-retry' onclick='window.history.back()'>
                    <i class='fas fa-redo'></i> Reintentar
                </button>
                <button class='btn btn-retry' onclick='window.location.href=\"nota_registro_grafico.php\"' style='background: linear-gradient(45deg, #95a5a6, #7f8c8d);'>
                    <i class='fas fa-home'></i> Ir al Inicio
                </button>
            </div>
        </body>
        </html>";
    }
} else {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Acceso No Autorizado</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' rel='stylesheet'>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Inter', sans-serif; 
                background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); 
                min-height: 100vh; 
                display: flex; 
                align-items: center; 
                justify-content: center;
                position: relative;
                overflow: hidden;
            }
            
            .hex-pattern {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: 
                    radial-gradient(circle at 25px 25px, rgba(255,255,255,0.1) 2px, transparent 2px),
                    radial-gradient(circle at 75px 75px, rgba(255,255,255,0.1) 2px, transparent 2px);
                background-size: 100px 100px;
                animation: hexFloat 20s linear infinite;
                opacity: 0.3;
            }
            
            @keyframes hexFloat {
                0% { transform: translateX(0px) translateY(0px); }
                50% { transform: translateX(-50px) translateY(-25px); }
                100% { transform: translateX(0px) translateY(0px); }
            }
            
            .notification-container {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(25px);
                border-radius: 30px;
                box-shadow: 0 30px 60px rgba(0,0,0,0.2);
                padding: 60px 50px;
                text-align: center;
                max-width: 500px;
                width: 90%;
                animation: securityAlert 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                border: 3px solid rgba(243, 156, 18, 0.3);
                position: relative;
                z-index: 10;
            }
            
            @keyframes securityAlert {
                0% { transform: scale(0.5) rotateY(-90deg); opacity: 0; }
                50% { transform: scale(1.1) rotateY(-45deg); opacity: 0.7; }
                100% { transform: scale(1) rotateY(0deg); opacity: 1; }
            }
            
            .icon-warning {
                background: linear-gradient(135deg, #f39c12, #e67e22);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-size: 100px;
                margin-bottom: 30px;
                animation: warningPulse 2s infinite;
                display: inline-block;
            }
            
            @keyframes warningPulse {
                0%, 100% { transform: scale(1); filter: drop-shadow(0 0 15px rgba(243, 156, 18, 0.3)); }
                50% { transform: scale(1.05); filter: drop-shadow(0 0 25px rgba(243, 156, 18, 0.6)); }
            }
            
            .title {
                background: linear-gradient(135deg, #d68910, #f39c12);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-size: 36px;
                font-weight: 800;
                margin-bottom: 25px;
            }
            
            .message {
                color: #5a6c7d;
                font-size: 20px;
                margin-bottom: 40px;
                line-height: 1.8;
                font-weight: 400;
            }
            
            .btn-back {
                background: linear-gradient(135deg, #f39c12, #d68910);
                border: none;
                border-radius: 50px;
                padding: 18px 40px;
                color: white;
                font-weight: 700;
                font-size: 18px;
                transition: all 0.4s;
                cursor: pointer;
                min-width: 200px;
                box-shadow: 0 10px 30px rgba(243, 156, 18, 0.3);
            }
            
            .btn-back:hover {
                transform: translateY(-5px) scale(1.05);
                box-shadow: 0 20px 40px rgba(243, 156, 18, 0.5);
            }
            
            @media (max-width: 600px) {
                .notification-container { padding: 40px 25px; margin: 20px; }
                .title { font-size: 28px; }
                .message { font-size: 16px; }
                .icon-warning { font-size: 70px; }
                .btn-back { min-width: 100%; padding: 15px 30px; }
            }
        </style>
    </head>
    <body>
        <div class='hex-pattern'></div>
        
        <div class='notification-container'>
            <i class='fas fa-shield-alt icon-warning'></i>
            <div class='title'>🔒 Acceso Restringido</div>
            <div class='message'>El método de acceso utilizado no está autorizado para esta operación sensible del sistema médico.</div>
            <button class='btn-back' onclick='window.history.back()'>
                <i class='fas fa-arrow-left'></i> Regresar de Forma Segura
            </button>
        </div>
        
        <script>
            if ('vibrate' in navigator) {
                navigator.vibrate([100, 50, 100]);
            }
            
            setTimeout(() => {
                document.querySelector('.btn-back').focus();
            }, 1000);
        </script>
    </body>
    </html>";
}
?>
