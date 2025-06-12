<?php
// Start session and ensure no output before this
ob_start();
session_start();

// Validate session
if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['login'])) {
    ob_end_clean();
    header("Location: ../../index.php");
    exit();
}

include "../../conexionbd.php";

$usuario = $_SESSION['login'];
$id_rol = $usuario['id_rol'];

if (in_array($id_rol, [4, 5, 10, 12])) {
    include "../header_labo.php";
} else {
    ob_end_clean();
    echo "<script>window.location='../../index.php';</script>";
    exit;
}

// Validate id_not_gabinete
$id_not_gabinete = isset($_GET['id_not_gabinete']) && is_numeric($_GET['id_not_gabinete']) ? (int)$_GET['id_not_gabinete'] : 0;
if ($id_not_gabinete <= 0) {
    ob_end_clean();
    header("Location: resultados_gab.php");
    exit();
}

// Define upload directory and base URL
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/gestion_medica/notas_medicas/resultados_gabinete/';
$base_url = '/gestion_medica/notas_medicas/resultados_gabinete/';
$allowed_extensions = ['pdf', 'png', 'jpg', 'jpeg'];
$allowed_mime_types = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
$max_file_size = 25000000; // 25MB

// Ensure upload directory exists and is writable
if (!file_exists($upload_dir) && !mkdir($upload_dir, 0775, true)) {
    error_log("Failed to create directory: $upload_dir");
    ob_end_clean();
    $_SESSION['message'] = 'Error al crear directorio de resultados.';
    $_SESSION['message_type'] = 'danger';
    header("Location: editar_gab.php?id_not_gabinete=$id_not_gabinete");
    exit();
}
if (!is_writable($upload_dir)) {
    if (!chmod($upload_dir, 0775)) {
        error_log("Failed to set permissions for directory: $upload_dir");
        ob_end_clean();
        $_SESSION['message'] = 'Error al establecer permisos de directorio.';
        $_SESSION['message_type'] = 'danger';
        header("Location: editar_gab.php?id_not_gabinete=$id_not_gabinete");
        exit();
    }
}

// Fetch current file(s)
$query = "SELECT id_not_gabinete, resultado FROM notificaciones_gabinete WHERE id_not_gabinete = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_not_gabinete);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    ob_end_clean();
    header("Location: resultados_gab.php");
    exit();
}

$current_files = $row['resultado'] ? array_map('trim', explode(',', htmlspecialchars($row['resultado']))) : [];
$file_paths = [];
$file_status = [];
foreach ($current_files as $file) {
    if ($file) {
        $file_path = $base_url . $file;
        $file_paths[] = $file_path;
        $file_status[$file] = file_exists($upload_dir . $file) ? 'Exists' : 'Missing';
    }
}

// Debugging output for file status
$debug_info = [
    'id_not_gabinete' => $id_not_gabinete,
    'filenames_in_db' => $row['resultado'] ?: 'None',
    'upload_dir' => $upload_dir,
    'directory_exists' => file_exists($upload_dir) ? 'Yes' : 'No',
    'directory_writable' => is_writable($upload_dir) ? 'Yes' : 'No',
    'file_status' => count($file_status) > 0 ? json_encode($file_status) : 'None'
];

// Handle file deletion
if (isset($_POST['delete']) && isset($_POST['filename']) && in_array($_POST['filename'], $current_files)) {
    $file_to_delete = trim($_POST['filename']);
    if ($file_to_delete && file_exists($upload_dir . $file_to_delete)) {
        if (unlink($upload_dir . $file_to_delete)) {
            $current_files = array_diff($current_files, [$file_to_delete]);
            $new_resultado = implode(',', array_filter($current_files));

            $update_query = "UPDATE notificaciones_gabinete SET resultado = ? WHERE id_not_gabinete = ?";
            $update_stmt = $conexion->prepare($update_query);
            $update_stmt->bind_param("si", $new_resultado, $id_not_gabinete);
            if ($update_stmt->execute()) {
                $_SESSION['message'] = 'Archivo eliminado correctamente.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error al actualizar la base de datos.';
                $_SESSION['message_type'] = 'danger';
                error_log("Failed to update database for id_not_gabinete $id_not_gabinete: " . $conexion->error);
            }
            $update_stmt->close();
        } else {
            $_SESSION['message'] = 'Error al eliminar el archivo del servidor.';
            $_SESSION['message_type'] = 'danger';
            error_log("Failed to delete file: $upload_dir$file_to_delete");
        }
    } else {
        $current_files = array_diff($current_files, [$file_to_delete]);
        $new_resultado = implode(',', array_filter($current_files));
        $update_query = "UPDATE notificaciones_gabinete SET resultado = ? WHERE id_not_gabinete = ?";
        $update_stmt = $conexion->prepare($update_query);
        $update_stmt->bind_param("si", $new_resultado, $id_not_gabinete);
        if ($update_stmt->execute()) {
            $_SESSION['message'] = 'Referencia de archivo eliminada (archivo no encontrado en servidor).';
            $_SESSION['message_type'] = 'warning';
        } else {
            $_SESSION['message'] = 'Error al actualizar la base de datos.';
            $_SESSION['message_type'] = 'danger';
        }
        $update_stmt->close();
    }
    ob_end_clean();
    header("Location: editar_gab.php?id_not_gabinete=$id_not_gabinete");
    exit();
}

// Handle file upload
if (isset($_POST['edit']) && isset($_FILES['resultado'])) {
    $error = false;
    $message = '';
    $new_files = [];

    // Debug $_FILES and $_POST
    error_log("POST: " . print_r($_POST, true));
    error_log("FILES: " . print_r($_FILES, true));

    // Validate $_FILES['resultado'] is an array
    if (!is_array($_FILES['resultado'])) {
        $error = true;
        $message = 'Error en la estructura de los archivos subidos.';
        error_log("Resultado: " . var_export($_FILES['resultado'], true));
    } elseif (!isset($_FILES['resultado']['name']) || empty($_FILES['resultado']['name'])) {
        $error = true;
        $message = 'Por favor, seleccione al menos un archivo.';
    } else {
        // Ensure name is an array, even for single file
        $files = $_FILES['resultado'];
        $file_names = is_array($files['name']) ? $files['name'] : [$files['name']];
        $file_tmp_names = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
        $file_sizes = is_array($files['size']) ? $files['size'] : [$files['size']];
        $file_errors = is_array($files['error']) ? $files['error'] : [$files['error']];

        // Check if any valid files were uploaded
        $has_valid_files = false;
        for ($i = 0; $i < count($file_names); $i++) {
            if ($file_errors[$i] === UPLOAD_ERR_OK && !empty($file_names[$i])) {
                $has_valid_files = true;
                break;
            }
        }

        if (!$has_valid_files) {
            $error = true;
            $message = 'No se seleccionaron archivos válidos.';
        } else {
            for ($i = 0; $i < count($file_names); $i++) {
                $file_name = $file_names[$i];
                $file_tmp = $file_tmp_names[$i];
                $file_size = $file_sizes[$i];
                $file_error = $file_errors[$i];

                // Skip empty or failed uploads
                if ($file_error === UPLOAD_ERR_NO_FILE || empty($file_name)) {
                    continue;
                }

                // Validate file
                $file_mime = $file_tmp && file_exists($file_tmp) ? mime_content_type($file_tmp) : '';
                $file_ext = $file_name ? strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) : '';

                if (!$file_ext || !in_array($file_ext, $allowed_extensions) || !$file_mime || !in_array($file_mime, $allowed_mime_types)) {
                    $error = true;
                    $message = 'Solo se permiten archivos PDF, PNG o JPEG.';
                    break;
                } elseif ($file_size > $max_file_size) {
                    $error = true;
                    $message = 'El archivo es demasiado grande (máximo 25MB).';
                    break;
                } elseif ($file_error !== UPLOAD_ERR_OK) {
                    $error = true;
                    $message = 'Error al subir el archivo: ' . $file_error;
                    break;
                } else {
                    // Generate unique filename
                    $new_filename = "resultado_gabinete_{$id_not_gabinete}_" . date('Ymd_His') . "_$i" . uniqid() . '.' . $file_ext;
                    $destination = $upload_dir . $new_filename;

                    // Move new file
                    if (move_uploaded_file($file_tmp, $destination)) {
                        if (file_exists($destination)) {
                            $new_files[] = $new_filename;
                        } else {
                            $error = true;
                            $message = 'Archivo no encontrado después de subir: ' . $file_name;
                            error_log("File not found after upload: $destination");
                            break;
                        }
                    } else {
                        $error = true;
                        $message = 'Error al mover el archivo a: ' . $destination;
                        error_log("Failed to move uploaded file to: $destination");
                        break;
                    }
                }
            }

            if (!$error && !empty($new_files)) {
                // Append new files to existing files
                $current_files = array_merge($current_files, $new_files);
                $new_resultado = implode(',', array_filter($current_files));

                // Update database
                $update_query = "UPDATE notificaciones_gabinete SET resultado = ?, fecha_resultado = NOW(), id_usua_resul = ? WHERE id_not_gabinete = ?";
                $update_stmt = $conexion->prepare($update_query);
                $update_stmt->bind_param("sii", $new_resultado, $usuario['id_usua'], $id_not_gabinete);
                if ($update_stmt->execute()) {
                    $message = count($new_files) . ' archivo(s) subido(s) correctamente.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $error = true;
                    $message = 'Error al actualizar la base de datos.';
                    error_log("Failed to update database for id_not_gabinete $id_not_gabinete: " . $conexion->error);
                }
                $update_stmt->close();
            } elseif (!$error && empty($new_files)) {
                $error = true;
                $message = 'No se seleccionaron archivos válidos.';
            }
        }
    }

    // Set message and redirect
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $error ? 'danger' : 'success';
    ob_end_clean();
    header("Location: editar_gab.php?id_not_gabinete=$id_not_gabinete");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
    <title>Editar Resultados de Gabinete</title>
    <style>
        .preview-container { max-width: 100%; height: 400px; overflow: auto; margin-bottom: 20px; }
        .preview-container img { max-width: 100%; height: auto; }
        .preview-container iframe { width: 100%; height: 400px; border: none; }
        .missing-file { color: red; font-style: italic; }
    </style>
    <script>
        $(document).ready(function() {
            // Reset file input after form submission
            $('#upload-form').on('submit', function() {
                setTimeout(function() {
                    $('#resultado').val('');
                }, 100);
            });

            // Validate file input before submission
            $('#submit-btn').on('click', function(e) {
                if (!$('#resultado').val()) {
                    alert('Por favor, seleccione al menos un archivo.');
                    e.preventDefault();
                }
            });
        });
    </script>
</head>
<body>
<div class="container-fluid">
    <?php
    if (in_array($id_rol, [4, 5, 10, 12])) {
        echo '<a class="btn btn-danger" href="resultados_gab.php">Regresar</a>';
    }
    ?>
    <br><br>
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
        <center><strong>EDITAR RESULTADOS DE GABINETE</strong></center>
    </div><br>
</div>

<section class="content container-fluid">
    <div class="container box">
        <div class="content">
            <div class="col-md-10">
                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert alert-' . htmlspecialchars($_SESSION['message_type']) . ' alert-dismissible fade show" role="alert">'
                        . htmlspecialchars($_SESSION['message'])
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'
                    . '</div>';
                    unset($_SESSION['message'], $_SESSION['message_type']);
                }
                ?>

                <form id="upload-form" class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <?php if (!empty($file_paths)): ?>
                                    <?php foreach ($file_paths as $index => $file_path): ?>
                                        <?php
                                        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                                        $filename = basename($file_path);
                                        $file_exists = isset($file_status[$filename]) && $file_status[$filename] === 'Exists';
                                        ?>
                                        <div class="preview-container">
                                            <p>Archivo <?php echo $index + 1; ?>: <?php echo htmlspecialchars($filename); ?>
                                                <?php if (!$file_exists): ?>
                                                    <span class="missing-file">(Archivo no encontrado en el servidor)</span>
                                                <?php endif; ?>
                                            </p>
                                            <?php if ($file_exists): ?>
                                                <?php if ($ext === 'pdf'): ?>
                                                    <iframe src="<?php echo htmlspecialchars($file_path); ?>" class="preview-container"></iframe>
                                                <?php elseif (in_array($ext, ['png', 'jpg', 'jpeg'])): ?>
                                                    <img src="<?php echo htmlspecialchars($file_path); ?>" alt="Resultado <?php echo $index + 1; ?>" class="preview-container">
                                                <?php else: ?>
                                                    <p>Formato de archivo no compatible para vista previa.</p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <form action="" method="post" style="margin-top: 10px;">
                                                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($filename); ?>">
                                                <button type="submit" name="delete" class="btn btn-warning btn-sm">Eliminar este archivo</button>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No hay archivos disponibles para este resultado.</p>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm">
                                <label for="resultado"><strong><font size="2">SELECCIONAR ARCHIVO(S) (PDF, PNG, JPEG)</font></strong></label>
                                <input type="file" class="form-control-file" id="resultado" name="resultado[]" accept="application/pdf,image/png,image/jpeg" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <div class="col-sm-12">
                            <a href="resultados_gab.php" class="btn btn-danger">Cancelar</a>
                            <input type="submit" id="submit-btn" name="edit" class="btn btn-success" value="Subir Archivo(s)">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<footer class="main-footer">
    <?php include "../../template/footer.php"; ?>
</footer>

<script src="../../template/dist/js/app.min.js"></script>
</body>
</html>
<?php
$conexion->close();
ob_end_flush();
?>