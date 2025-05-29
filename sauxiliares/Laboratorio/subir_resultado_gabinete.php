<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['login']) || !in_array($_SESSION['login']['id_rol'], [4, 5, 10])) {
    header("Location: ../../index.php");
    exit();
}

$not_id = isset($_GET['not_id']) ? (int)$_GET['not_id'] : 0;
$usuario = $_SESSION['login'];
$upload_error = '';
$upload_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resultado'])) {
    $files = $_FILES['resultado'];
    $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/gestion_medica/notas_medicas/resultados_gabinete/';
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    $file_names = [];
    $all_uploaded = true;

    // Handle multiple files
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $nombre_archivo = "resultado_gabinete_" . $not_id . "_" . date('Ymd_His') . "_$i.pdf";
            $ruta_archivo = $carpeta . $nombre_archivo;

            if (move_uploaded_file($files['tmp_name'][$i], $ruta_archivo)) {
                $file_names[] = $nombre_archivo; // Store only the filename
            } else {
                $all_uploaded = false;
                $upload_error = "Error al mover el archivo: " . htmlspecialchars($files['name'][$i]);
                break;
            }
        } else {
            $all_uploaded = false;
            $upload_error = "Error al subir el archivo: " . $files['error'][$i];
            break;
        }
    }

    if ($all_uploaded && !empty($file_names)) {
        // Encode filenames as JSON (for multiple files support)
        $file_names_json = json_encode($file_names);

        $sql = "UPDATE notificaciones_gabinete SET realizado = 'SI', resultado = ?, fecha_resultado = NOW(), id_usua_resul = ? WHERE id_not_gabinete = ?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            $upload_error = "Error al preparar la consulta: " . $conexion->error;
        } else {
            $stmt->bind_param("sii", $file_names_json, $usuario['id_usua'], $not_id);
            if ($stmt->execute()) {
                $upload_success = "Archivos subidos correctamente.";
                header("Location: sol_gabinete.php?success=" . urlencode($upload_success));
                exit();
            } else {
                $upload_error = "Error al actualizar la base de datos: " . $conexion->error;
            }
            $stmt->close();
        }
    }
}

// Fetch notification details
$sql = "SELECT n.sol_estudios, n.habitacion, p.papell, p.sapell, p.nom_pac 
        FROM notificaciones_gabinete n 
        JOIN dat_ingreso d ON n.id_atencion = d.id_atencion 
        JOIN paciente p ON d.Id_exp = p.Id_exp 
        WHERE n.id_not_gabinete = ?";
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $not_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notificacion = $result->fetch_assoc();
    $stmt->close();
} else {
    $upload_error = "Error al preparar la consulta de notificación: " . $conexion->error;
}

include "../header_labo.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid">
    <a href="sol_gabinete.php" class="btn btn-danger">Regresar</a>
    <h2>Subir Resultado de Estudio de Gabinete</h2>
    <?php if ($notificacion): ?>
        <p><strong>Paciente:</strong> <?php echo htmlspecialchars($notificacion['papell'] . ' ' . $notificacion['sapell'] . ' ' . $notificacion['nom_pac']); ?></p>
        <p><strong>Habitación:</strong> <?php echo htmlspecialchars($notificacion['habitacion']); ?></p>
        <p><strong>Estudio(s):</strong> <?php echo htmlspecialchars($notificacion['sol_estudios']); ?></p>
        <?php if ($upload_error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($upload_error); ?></div>
        <?php endif; ?>
        <?php if ($upload_success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($upload_success); ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="resultado">Seleccionar archivo(s) PDF:</label>
                <input type="file" class="form-control-file" id="resultado" name="resultado[]" accept=".pdf" multiple required>
            </div>
            <button type="submit" class="btn btn-success">Subir Resultados</button>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Notificación no encontrada.</div>
    <?php endif; ?>
</div>
</body>
</html>
<?php $conexion->close(); ?>