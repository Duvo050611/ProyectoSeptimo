<?php
session_start();
include "../../conexionbd.php";
include "../header_medico.php";

if (!isset($_SESSION['login']) || !in_array($_SESSION['login']['id_rol'], [4, 5, 10])) {
    header("Location: ../../index.php");
    exit();
}

$not_id = isset($_GET['not_id']) ? (int)$_GET['not_id'] : 0;
$usuario = $_SESSION['login'];
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['anotacion'])) {
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
        } else {
            $error_message = "Error al guardar la anotación: " . $conexion->error;
        }
        $stmt->close();
    } else {
        $error_message = "La anotación no puede estar vacía.";
    }
}

if ($not_id === 0) {
    $error_message = "ID de notificación inválido.";
} else {
    // Fetch Gabinete result and det_gab
    $file_doc = "SELECT id_not_gabinete, resultado, det_gab FROM notificaciones_gabinete WHERE id_not_gabinete = ?";
    $stmt = $conexion->prepare($file_doc);
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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.result-file').click(function(e) {
                e.preventDefault();
                var newSrc = $(this).attr('href');
                $('#pdfViewer').attr('src', newSrc);
            });
        });
    </script>
    <style>
        .annotation-area {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        #det_gab_display {
            white-space: pre-wrap;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>
                <center><i class="fa fa-plus-square"></i> Resultados de Gabinete</center>
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
                    <?php elseif ($row && !empty($row['resultado'])): ?>
                        <?php
                        $file_names = json_decode($row['resultado'], true);
                        if ($file_names && is_array($file_names)) {
                            $first_file = $file_names[0];
                            $first_file_path = '/gestion_medica/notas_medicas/resultados_gabinete/' . $first_file;
                        ?>
                            <h3>Resultados Disponibles:</h3>
                            <ul>
                                <?php
                                foreach ($file_names as $index => $file_name) {
                                    $file_path = '/gestion_medica/notas_medicas/resultados_gabinete/' . $file_name;
                                    echo '<li><a href="' . htmlspecialchars($file_path) . '" class="result-file">Resultado ' . ($index + 1) . '</a></li>';
                                }
                                ?>
                            </ul>
                            <center>
                                <h3>Vista Previa</h3>
                                <iframe id="pdfViewer" src="<?php echo htmlspecialchars($first_file_path); ?>" width="100%" height="600px"></iframe>
                            </center>
                        <?php } else { ?>
                            <div class="alert alert-warning">No se encontraron archivos válidos.</div>
                        <?php } ?>
                    <?php else: ?>
                        <div class="alert alert-danger">No se encontraron resultados para la notificación ID <?php echo $not_id; ?>.</div>
                    <?php endif ?>
                </div>
                <div class="col-md-4">
                    <div class="annotation-area">
                        <h3>Anotaciones del Médico</h3>
                        <?php if ($row && !empty($row['det_gab'])): ?>
                            <div id="det_gab_display"><?php echo htmlspecialchars($row['det_gab']); ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="anotacion">Agregar Anotación:</label>
                                <textarea class="form-control" id="anotacion" name="anotacion" rows="5" placeholder="Escriba sus observaciones sobre los resultados"></textarea>
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

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js"></script>
</body>
</html>
<?php $conexion->close(); ?>