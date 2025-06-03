<?php
// Start session and ensure no output before this
ob_start();
session_start();
include "../../conexionbd.php";
include "../header_medico.php";

// Validate session and role
if (!isset($_SESSION['login']) || !in_array($_SESSION['login']['id_rol'], [4, 5, 10])) {
    ob_end_clean();
    header("Location: ../../index.php");
    exit();
}

$not_id = isset($_GET['not_id']) && is_numeric($_GET['not_id']) ? (int)$_GET['not_id'] : 0;
$usuario = $_SESSION['login'];
$error_message = '';
$success_message = '';

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Define upload directory for file validation
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/gestion_medica/notas_medicas/resultados_gabinete/';
$base_url = '/gestion_medica/notas_medicas/resultados_gabinete/';
$allowed_extensions = ['pdf', 'png', 'jpg', 'jpeg'];

// Handle annotation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['anotacion'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = "Error de seguridad: token CSRF inválido.";
    } else {
        $anotacion = trim($_POST['anotacion']);
        if (!empty($anotacion)) {
            // Fetch current det_gab
            $sql = "SELECT det_gab FROM notificaciones_gabinete WHERE id_not_gabinete = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $not_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $current_det_gab = $row['det_gab'] ?? '';
            $stmt->close();

            // Append new annotation with timestamp and user
            $new_anotacion = $current_det_gab
                ? $current_det_gab . "\n[" . date('Y-m-d H:i') . " - {$usuario['papell']} {$usuario['sapell']}]: " . $anotacion
                : "[" . date('Y-m-d H:i') . " - {$usuario['papell']} {$usuario['sapell']}]: " . $anotacion;

            // Update det_gab
            $sql = "UPDATE notificaciones_gabinete SET det_gab = ? WHERE id_not_gabinete = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("si", $new_anotacion, $not_id);
            if ($stmt->execute()) {
                $success_message = "Anotación guardada correctamente.";
                // Regenerate CSRF token after successful submission
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } else {
                $error_message = "Error al guardar la anotación: " . $conexion->error;
            }
            $stmt->close();
        } else {
            $error_message = "La anotación no puede estar vacía.";
        }
    }
}

// Validate not_id and fetch data
if ($not_id === 0) {
    $error_message = "ID de notificación inválido.";
} else {
    // Fetch Gabinete result and det_gab
    $sql = "SELECT id_not_gabinete, resultado, det_gab FROM notificaciones_gabinete WHERE id_not_gabinete = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $not_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <!-- Bootstrap 4.5.2 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Font Awesome 5.15.4 -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <!-- jQuery 3.5.1 -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- Popper.js 1.16.0 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <!-- Bootstrap 4.5.2 JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <title>Detalles de Resultados de Gabinete</title>
    <style>
        .preview-container { max-width: 100%; height: 600px; overflow: auto; margin-bottom: 20px; }
        .preview-container img { max-width: 100%; height: auto; }
        .preview-container iframe { width: 100%; height: 600px; border: none; }
        .annotation-area { border: 1px solid #ccc; padding: 15px; border-radius: 5px; background-color: #f8f9fa; }
        #det_gab_display { white-space: pre-wrap; margin-bottom: 15px; }
        .missing-file { color: red; font-style: italic; }
    </style>
    <script>
        $(document).ready(function() {
            $('.result-file').click(function(e) {
                e.preventDefault();
                var fileUrl = $(this).attr('href');
                var fileExt = fileUrl.split('.').pop().toLowerCase();
                var $previewArea = $('#previewArea');
                
                $previewArea.empty();
                if (fileExt === 'pdf') {
                    $previewArea.html('<iframe src="' + fileUrl + '" class="preview-container"></iframe>');
                } else if (['png', 'jpg', 'jpeg'].includes(fileExt)) {
                    $previewArea.html('<img src="' + fileUrl + '" alt="Resultado" class="preview-container">');
                } else {
                    $previewArea.html('<p>Formato de archivo no compatible para vista previa. <a href="' + fileUrl + '" download>Descargar archivo</a></p>');
                }
            });
        });
    </script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center">
                <i class="fas fa-plus-square"></i> Resultados de Gabinete
            </h2>
            <hr>
        </div>
    </div>
</div>

<section class="content container-fluid">
    <div class="container">
        <div class="content">
            <div class="row">
                <div class="col-md-8">
                    <a href="estudios.php" class="btn btn-danger mb-3">Regresar</a>
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php elseif ($success_message): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                    <?php elseif ($row): ?>
                        <?php
                        // Parse resultado as comma-separated string (previously JSON)
                        $file_names = $row['resultado'] ? array_map('trim', explode(',', $row['resultado'])) : [];
                        $first_file_path = '';
                        if (!empty($file_names)) {
                            $first_file = $file_names[0];
                            $first_file_path = $base_url . $first_file;
                        }
                        ?>
                        <h3>Resultados Disponibles:</h3>
                        <?php if (!empty($file_names)): ?>
                            <ul class="list-group mb-3">
                                <?php foreach ($file_names as $index => $file_name): ?>
                                    <?php
                                    // Sanitize filename to prevent directory traversal
                                    $file_name = basename($file_name);
                                    $file_path = $base_url . $file_name;
                                    $file_exists = file_exists($upload_dir . $file_name);
                                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                                    ?>
                                    <li class="list-group-item">
                                        <a href="<?php echo htmlspecialchars($file_path); ?>" class="result-file">
                                            Resultado <?php echo ($index + 1); ?>
                                            <?php if (!$file_exists): ?>
                                                <span class="missing-file">(No encontrado)</span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <h3>Vista Previa</h3>
                            <div id="previewArea" class="preview-container">
                                <?php if ($first_file_path && file_exists($upload_dir . $first_file)): ?>
                                    <?php if (in_array(strtolower(pathinfo($first_file_path, PATHINFO_EXTENSION)), ['pdf'])): ?>
                                        <iframe src="<?php echo htmlspecialchars($first_file_path); ?>" class="preview-container"></iframe>
                                    <?php elseif (in_array(strtolower(pathinfo($first_file_path, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg'])): ?>
                                        <img src="<?php echo htmlspecialchars($first_file_path); ?>" alt="Resultado" class="preview-container">
                                    <?php else: ?>
                                        <p>Formato de archivo no compatible para vista previa. <a href="<?php echo htmlspecialchars($first_file_path); ?>" download>Descargar archivo</a></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p>No hay archivos disponibles para vista previa.</p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">No se encontraron archivos de resultados.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-danger">No se encontraron resultados para la notificación ID <?php echo $not_id; ?>.</div>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <div class="annotation-area">
                        <h4>Anotaciones</h4>
                        <?php if ($row && !empty($row['det_gab'])): ?>
                            <div id="det_gab_display"><?php echo htmlspecialchars($row['det_gab']); ?></div>
                        <?php else: ?>
                            <p>No hay anotaciones disponibles.</p>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <div class="form-group">
                                <label for="anotacion">Nueva Anotación:</label>
                                <textarea class="form-control" id="anotacion" name="anotacion" rows="4" placeholder="Escribe tu anotación aquí..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Anotación</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="main-footer">
    <?php include "../../template/footer.php"; ?>
</footer>

<!-- Avoid duplicate jQuery and potential conflicts -->
<!-- <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script> -->
<!-- <script src="../../template/plugins/fastclick/fastclick.min.js"></script> -->
<script src="../../template/dist/js/app.min.js"></script>
</body>
</html>
<?php
$conexion->close();
ob_end_flush();
?>