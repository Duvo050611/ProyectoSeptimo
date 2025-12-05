<?php
// Start session and ensure no output before this
ob_start();
session_start();
require_once '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();

if (!isset($_SESSION['login']) || !in_array($_SESSION['login']['id_rol'], [4, 5, 10])) {
    ob_end_clean();
    header("Location: ../../index.php");
    exit();
}

$not_id = isset($_GET['not_id']) && is_numeric($_GET['not_id']) ? (int)$_GET['not_id'] : 0;
$usuario = $_SESSION['login'];
$upload_error = '';
$upload_success = '';

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Define upload directory and settings
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/gestion_medica/notas_medicas/resultados/';
$allowed_extensions = ['pdf', 'png', 'jpg', 'jpeg'];
$allowed_mime_types = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
$max_file_size = 25000000; // 25MB

// Ensure upload directory exists and is writable
if (!file_exists($upload_dir) && !mkdir($upload_dir, 0775, true)) {
    $upload_error = "Error al crear directorio de resultados.";
    error_log("Failed to create directory: $upload_dir");
}
if (!is_writable($upload_dir)) {
    if (!chmod($upload_dir, 0775)) {
        $upload_error = "Error al establecer permisos de directorio.";
        error_log("Failed to set permissions for directory: $upload_dir");
    }
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resultado'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $upload_error = "Error de seguridad: token CSRF inválido.";
    } else {
        $files = $_FILES['resultado'];
        $file_names = [];
        $all_uploaded = true;

        // Handle multiple files
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = $files['name'][$i];
                $file_tmp = $files['tmp_name'][$i];
                $file_size = $files['size'][$i];
                $file_mime = mime_content_type($file_tmp);
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                // Validate file
                if (!in_array($file_ext, $allowed_extensions) || !in_array($file_mime, $allowed_mime_types)) {
                    $all_uploaded = false;
                    $upload_error = "Formato no permitido para: " . htmlspecialchars($file_name) . ". Solo PDF, PNG, JPG, JPEG.";
                    break;
                }
                if ($file_size > $max_file_size) {
                    $all_uploaded = false;
                    $upload_error = "Archivo demasiado grande: " . htmlspecialchars($file_name) . ". Máximo 25MB.";
                    break;
                }

                // Generate unique filename
                $new_filename = "resultado_{$not_id}_" . date('Ymd_His') . "_$i." . $file_ext;
                $destination = $upload_dir . $new_filename;

                // Move file
                if (move_uploaded_file($file_tmp, $destination)) {
                    if (file_exists($destination)) {
                        $file_names[] = $new_filename;
                    } else {
                        $all_uploaded = false;
                        $upload_error = "Archivo no encontrado después de subir: " . htmlspecialchars($file_name);
                        error_log("File not found after upload: $destination");
                        break;
                    }
                } else {
                    $all_uploaded = false;
                    $upload_error = "Error al mover el archivo: " . htmlspecialchars($file_name);
                    error_log("Failed to move uploaded file: $destination");
                    break;
                }
            } else {
                $all_uploaded = false;
                $upload_error = "Error al subir el archivo: " . $files['error'][$i];
                break;
            }
        }

        if ($all_uploaded && !empty($file_names)) {
            // Store as comma-separated string
            $file_names_string = implode(',', $file_names);

            $sql = "UPDATE notificaciones_labo SET realizado = 'SI', resultado = ?, fecha_resultado = NOW(), id_usua_resul = ? WHERE not_id = ?";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                $upload_error = "Error al preparar la consulta: " . $conexion->error;
                error_log("SQL Prepare Error: " . $conexion->error);
            } else {
                $stmt->bind_param("sii", $file_names_string, $usuario['id_usua'], $not_id);
                if ($stmt->execute()) {
                    $upload_success = "Archivos subidos correctamente.";
                    // Regenerate CSRF token
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    ob_end_clean();
                    header("Location: sol_laboratorio.php?success=" . urlencode($upload_success));
                    exit();
                } else {
                    $upload_error = "Error al actualizar la base de datos: " . $stmt->error;
                    error_log("SQL Execute Error: " . $stmt->error);
                }
                $stmt->close();
            }
        }
    }
}

// Fetch notification details
$sql = "SELECT n.sol_estudios, n.det_labo, n.habitacion, p.papell, p.sapell, p.nom_pac 
        FROM notificaciones_labo n 
        JOIN dat_ingreso d ON n.id_atencion = d.id_atencion 
        JOIN paciente p ON d.Id_exp = p.Id_exp 
        WHERE n.not_id = ?";
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $not_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notificacion = $result->fetch_assoc();
    $stmt->close();
} else {
    $upload_error = "Error al preparar la consulta de notificación: " . $conexion->error;
    error_log("SQL Prepare Error: " . $conexion->error);
}

include "../header_labo.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <title>Subir Resultado de Estudio</title>
</head>
<body>
<div class="container-fluid">
    <a href="sol_laboratorio.php" class="btn btn-danger mb-3"><i class="fas fa-arrow-left"></i> Regresar</a>
    <h2><i class="fas fa-upload"></i> Subir Resultado de Estudio</h2>
    <?php if ($notificacion): ?>
        <div class="card mb-3">
            <div class="card-body">
                <p><strong>Paciente:</strong> <?php echo htmlspecialchars($notificacion['papell'] . ' ' . $notificacion['sapell'] . ' ' . $notificacion['nom_pac']); ?></p>
                <p><strong>Habitación:</strong> <?php echo htmlspecialchars($notificacion['habitacion']); ?></p>
                <p><strong>Estudio(s):</strong> <?php echo htmlspecialchars($notificacion['sol_estudios'] . ' ' . ($notificacion['det_labo'] ?? '')); ?></p>
            </div>
        </div>
        <?php if ($upload_error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($upload_error); ?></div>
        <?php endif; ?>
        <?php if ($upload_success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($upload_success); ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="form-group">
                <label for="resultado">Seleccionar archivo(s) (PDF, PNG, JPG, JPEG):</label>
                <input type="file" class="form-control-file" id="resultado" name="resultado[]" accept=".pdf,.png,.jpg,.jpeg" multiple required>
                <small class="form-text text-muted">Máximo 25MB por archivo. Puede seleccionar múltiples archivos.</small>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Subir Resultados</button>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Notificación no encontrada.</div>
    <?php endif; ?>
</div>
<footer class="main-footer mt-4">
    <?php include "../../template/footer.php"; ?>
</footer>
</body>
</html>
<?php
$conexion->close();
ob_end_flush();
?>