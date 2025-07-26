<?php
// Función para verificar si la cirugía ha terminado
function cirugiaTerminada($conexion, $id_atencion) {
    try {
        // Verificar en la base de datos si existe un registro que indique que la cirugía terminó
        $sql = "SELECT COUNT(*) as terminada FROM dat_trans_grafico 
                WHERE id_atencion = ? AND sistg = 'CIRUGIA' AND diastg = 'TERMINADA'";
        
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("i", $id_atencion);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['terminada'] > 0;
        
    } catch (Exception $e) {
        error_log("Error verificando estado de cirugía: " . $e->getMessage());
        return false;
    }
}

// Función para mostrar mensaje de bloqueo cuando la cirugía ha terminado
function mostrarMensajeBloqueo() {
    return "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>🚫 Cirugía Terminada - Sistema INEO</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap' rel='stylesheet'>
        <style>
            body { 
                font-family: 'Inter', sans-serif; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .lock-container {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                border-radius: 25px;
                box-shadow: 0 25px 50px rgba(0,0,0,0.2);
                padding: 50px 40px;
                text-align: center;
                max-width: 500px;
                width: 100%;
                border: 2px solid rgba(102, 126, 234, 0.3);
                position: relative;
                overflow: hidden;
            }
            .lock-container::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: conic-gradient(from 0deg, transparent, rgba(102, 126, 234, 0.03), transparent);
                animation: rotate 6s linear infinite;
            }
            @keyframes rotate {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .btn-grafica {
                background: linear-gradient(135deg, #f39c12, #e67e22);
                border: none;
                border-radius: 25px;
                padding: 15px 35px;
                color: white;
                font-weight: 700;
                font-size: 16px;
                transition: all 0.3s;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                position: relative;
                z-index: 2;
                margin-bottom: 30px;
            }
            .btn-grafica:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 35px rgba(243, 156, 18, 0.4);
                color: white;
                text-decoration: none;
            }
            .lock-icon {
                font-size: 80px;
                background: linear-gradient(135deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 25px;
                animation: lockPulse 2s ease-in-out infinite;
                position: relative;
                z-index: 2;
            }
            @keyframes lockPulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }
            .lock-title {
                background: linear-gradient(135deg, #2c3e50, #34495e);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-size: 32px;
                font-weight: 700;
                margin-bottom: 20px;
                position: relative;
                z-index: 2;
            }
            .lock-message {
                color: #5a6c7d;
                font-size: 18px;
                margin-bottom: 30px;
                line-height: 1.6;
                position: relative;
                z-index: 2;
            }
            .lock-details {
                background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                border-radius: 15px;
                padding: 20px;
                margin: 25px 0;
                border-left: 5px solid #667eea;
                position: relative;
                z-index: 2;
            }
            .lock-btn {
                background: linear-gradient(135deg, #667eea, #764ba2);
                border: none;
                border-radius: 25px;
                padding: 15px 35px;
                color: white;
                font-weight: 700;
                font-size: 16px;
                transition: all 0.3s;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                position: relative;
                z-index: 2;
            }
            .lock-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
                color: white;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class='lock-container'>
            <!-- Botón Ver Gráfica arriba del mensaje -->
            <a href='ver_grafica.php' class='btn-grafica'>
                <i class='fas fa-chart-line'></i> Ver Gráfica
            </a>
            
            <div class='lock-icon'>
                <i class='fas fa-lock'></i>
            </div>
            <div class='lock-title'>🚫 Cirugía Terminada</div>
            <div class='lock-message'>
                Los signos vitales han sido <strong>bloqueados</strong> porque la cirugía ha sido marcada como terminada.
            </div>
            <div class='lock-details'>
                <h5 style='color: #667eea; margin-bottom: 15px;'>
                    <i class='fas fa-info-circle'></i> Información del Sistema
                </h5>
                <p style='margin: 8px 0; color: #5a6c7d;'>
                    <i class='fas fa-clock'></i> <strong>Hora de bloqueo:</strong> " . date('d/m/Y H:i:s') . "
                </p>
                <p style='margin: 8px 0; color: #5a6c7d;'>
                    <i class='fas fa-user-md'></i> <strong>Estado:</strong> Procedimiento completado
                </p>
                <p style='margin: 8px 0; color: #5a6c7d;'>
                    <i class='fas fa-shield-alt'></i> <strong>Seguridad:</strong> Registro protegido
                </p>
            </div>
            <a href='../lista_pacientes/vista_pac_hosp.php' class='lock-btn'>
                <i class='fas fa-arrow-left'></i> Regresar al Paciente
            </a>
        </div>
    </body>
    </html>";
}
?>
