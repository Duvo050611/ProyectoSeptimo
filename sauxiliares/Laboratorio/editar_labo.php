<?php
// Start session and ensure no output before this
session_start();

// Validate session
if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['login'])) {
    header("Location: ../../index.php");
    exit();
}

include "../../conexionbd.php";

$usuario = $_SESSION['login'];
$id_rol = $usuario['id_rol'];

// Restrict access to roles 4, 5, 10
if (!in_array($id_rol, [4, 5, 10])) {
    header("Location: ../../index.php");
    exit();
}

// Validate not_id
$not_id = isset($_GET['not_id']) && is_numeric($_GET['not_id']) ? (int)$_GET['not_id'] : 0;
if ($not_id <= 0) {
    header("Location: resultados_labo.php");
    exit();
}

// Fetch current PDF
$query = "SELECT not_id, resultado FROM notificaciones_labo WHERE not_id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $not_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    header("Location: resultados_labo.php");
    exit();
}

$current_pdf = $row['resultado'] ? htmlspecialchars($row['resultado']) : '';
$pdf_path = $current_pdf && file_exists("/gestion_medica/notas_medicas/resultados/$current_pdf") ? "/gestion_medica/notas_medicas/resultados/$current_pdf" : '';

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Bootstrap Select JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <title>Editar Resultados de Laboratorio</title>
</head>
<body>
<div class="container-fluid">
    <?php
    // Navigation buttons
    if ($id_rol == 4) {
        echo '<a class="btn btn-danger" href="../../template/menu_sauxiliares.php">Regresar</a>';
    } elseif ($id_rol == 10) {
        echo '<a class="btn btn-danger" href="../../template/menu_laboratorio.php">Regresar</a>';
    } elseif ($id_rol == 5) {
        echo '<a class="btn btn-danger" href="../../template/menu_gerencia.php">Regresar</a>';
    }
    ?>
    <br><br>
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
        <center><strong>EDITAR RESULTADOS DE LABORATORIO</strong></center>
    </div><br>
</div>

<section class="content container-fluid">
    <div class="container box">
        <div class="content">
            <div class="col-md-10">
                <?php
                // Display success/error messages
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert alert-' . htmlspecialchars($_SESSION['message_type']) . ' alert-dismissible fade show" role="alert">'
                        . htmlspecialchars($_SESSION['message'])
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                        . '</div>';
                    unset($_SESSION['message'], $_SESSION['message_type']);
                }
                ?>

                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <?php if ($pdf_path): ?>
                                    <iframe src="<?php echo $pdf_path; ?>" width="450px" height="400px"></iframe>
                                <?php else: ?>
                                    <p>No hay PDF disponible para este resultado.</p>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm">
                                <label for="resultado"><strong><font size="2">SELECCIONAR UN ARCHIVO PDF</font></strong></label>
                                <input type="file" class="form-control-file" id="resultado" name="resultado" accept="application/pdf">
                            </div>
                        </div>
                    </div>
                    <center>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <a href="resultados_labo.php" class="btn btn-danger">Cancelar</a>
                                <input type="submit" name="edit" class="btn btn-success" value="Guardar Datos">
                            </div>
                        </div>
                    </center>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
if (isset($_POST['edit'])) {
    $error = false;
    $message = '';

    // Handle PDF upload
    if (isset($_FILES['resultado']) && $_FILES['resultado']['name'] != '') {
        $file_name = $_FILES['resultado']['name'];
        $file_tmp = $_FILES['resultado']['tmp_name'];
        $file_size = $_FILES['resultado']['size'];
        $file_error = $_FILES['resultado']['error'];

        // Validate file
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if ($file_ext !== 'pdf') {
            $error = true;
            $message = 'Solo se permiten archivos PDF.';
        } elseif ($file_size > 5242880) { // 5MB limit
            $error = true;
            $message = 'El archivo es demasiado grande (máximo 5MB).';
        } elseif ($file_error !== UPLOAD_ERR_OK) {
            $error = true;
            $message = 'Error al subir el archivo.';
        } else {
            // Generate unique filename
            $new_filename = time() . '_' . $not_id . '.pdf';
            $destination = 'resultados/' . $new_filename;

            // Move new file
            if (move_uploaded_file($file_tmp, $destination)) {
                // Delete old file if exists
                if ($current_pdf && file_exists("/gestion_medica/notas_medicas/resultados/$current_pdf")) {
                    unlink("/gestion_medica/notas_medicas/resultados/$current_pdf");
                }

                // Update database
                $update_query = "UPDATE notificaciones_labo SET resultado = ? WHERE not_id = ?";
                $update_stmt = $conexion->prepare($update_query);
                $update_stmt->bind_param("si", $new_filename, $not_id);
                if ($update_stmt->execute()) {
                    $message = 'PDF actualizado correctamente.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $error = true;
                    $message = 'Error al actualizar la base de datos.';
                }
                $update_stmt->close();
            } else {
                $error = true;
                $message = 'Error al guardar el archivo.';
            }
        }
    } else {
        $error = true;
        $message = 'Por favor, seleccione un archivo PDF.';
    }

    // Set message and redirect
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $error ? 'danger' : 'success';
    header("Location: editar_resultado.php?not_id=$not_id");
    exit();
}
?>

<footer class="main-footer">
    <?php include "../../template/footer.php"; ?>
</footer>

<script src="../../template/plugins/fastclick/fastclick.min.js"></script>
<script src="../../template/dist/js/app.min.js"></script>
</body>
</html>
<?php $conexion->close(); ?>