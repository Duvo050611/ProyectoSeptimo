<?php
session_start();
include "../../conexionbd.php";

// Verificar que haya un paciente seleccionado
if (!isset($_SESSION['pac']) || empty($_SESSION['pac'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Error - Sistema INEO</title>
        <meta charset="UTF-8">
        <script>
            alert('❌ No hay paciente seleccionado');
            window.history.back();
        </script>
    </head>
    <body></body>
    </html>
    <?php
    exit;
}

// Si es GET, mostrar confirmación simple
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>🚫 Terminar Cirugía - Sistema INEO</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Inter', sans-serif;
            }
            .confirm-card {
                background: white;
                border-radius: 20px;
                padding: 40px;
                text-align: center;
                max-width: 500px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            }
            .btn-confirm {
                background: linear-gradient(135deg, #e74c3c, #c0392b);
                border: none;
                color: white;
                padding: 12px 30px;
                border-radius: 25px;
                font-weight: 600;
                margin: 10px;
                transition: all 0.3s;
            }
            .btn-confirm:hover {
                transform: translateY(-2px);
                color: white;
            }
            .btn-cancel {
                background: #6c757d;
                border: none;
                color: white;
                padding: 12px 30px;
                border-radius: 25px;
                font-weight: 600;
                margin: 10px;
                text-decoration: none;
                display: inline-block;
                transition: all 0.3s;
            }
            .btn-cancel:hover {
                background: #545b62;
                color: white;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="confirm-card">
            <h2 style="color: #2c3e50; margin-bottom: 20px;">
                <i class="fas fa-exclamation-triangle" style="color: #f39c12;"></i>
                Confirmar Término de Cirugía
            </h2>
            <p style="color: #5a6c7d; font-size: 18px; margin-bottom: 25px;">
                ¿Está seguro que desea <strong>terminar la cirugía</strong>?
            </p>
            <div class="alert alert-warning" style="margin: 20px 0;">
                <strong>⚠️ Advertencia:</strong> Esta acción bloqueará permanentemente la captura de signos vitales.
            </div>
            
            <form method="POST" style="display: inline;">
                <input type="hidden" name="confirmar_termino" value="1">
                <button type="submit" class="btn btn-confirm">
                    ✅ Sí, Terminar Cirugía
                </button>
            </form>
            
            <a href="nota_registro_grafico.php" class="btn-cancel">
                ❌ Cancelar
            </a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Si es POST, procesar terminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_termino'])) {
    try {
        $id_atencion = $_SESSION['pac'];
        
        // Verificación rápida
        $check = $conexion->query("SELECT id_trans_graf FROM dat_trans_grafico 
                                  WHERE id_atencion=$id_atencion AND sistg='CIRUGIA' AND diastg='TERMINADA' LIMIT 1");
        
        if ($check->num_rows > 0) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Aviso - Sistema INEO</title>
                <meta charset="UTF-8">
                <script>
                    alert('⚠️ La cirugía ya ha sido marcada como terminada anteriormente.');
                    window.location.href = '../lista_pacientes/vista_pac_hosp.php';
                </script>
            </head>
            <body></body>
            </html>
            <?php
            exit;
        }
        
        // Insertar registro de terminación
        $hora_actual = date('H:i:s');
        $insert = $conexion->query("INSERT INTO dat_trans_grafico 
                                   (id_atencion, id_usua, sistg, diastg, fcardg, frespg, satg, tempg, hora) 
                                   VALUES ($id_atencion, 1, 'CIRUGIA', 'TERMINADA', 'BLOQUEADO', 'BLOQUEADO', 'BLOQUEADO', 'BLOQUEADO', '$hora_actual')");
        
        if ($insert) {
            $_SESSION['cirugia_terminada_' . $id_atencion] = true;
            
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Éxito - Sistema INEO</title>
                <meta charset="UTF-8">
                <script>
                    alert('✅ Cirugía terminada exitosamente\n\nLos signos vitales han sido bloqueados.');
                    window.location.href = '../lista_pacientes/vista_pac_hosp.php';
                </script>
            </head>
            <body></body>
            </html>
            <?php
        } else {
            throw new Exception("Error en la base de datos: " . $conexion->error);
        }
        
    } catch (Exception $e) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Error - Sistema INEO</title>
            <meta charset="UTF-8">
            <script>
                alert('❌ Error: <?php echo addslashes($e->getMessage()); ?>');
                window.history.back();
            </script>
        </head>
        <body></body>
        </html>
        <?php
    }
}

$conexion->close();
?>
