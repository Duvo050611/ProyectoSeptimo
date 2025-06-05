<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital']) || !isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_atencion = $_SESSION['hospital'];
    $id_usua = isset($_POST['id_usua']) ? (int)$_POST['id_usua'] : 0;

    // Validate id_usua
    if ($id_usua === 0) {
        $_SESSION['message'] = "Error: ID de usuario no válido.";
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }

    // Get Id_exp
    $stmt = $conexion->prepare("SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?");
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    $result = $stmt->get_result();
    $id_exp = $result->fetch_assoc()['Id_exp'] ?? null;
    $stmt->close();

    if (!$id_exp) {
        $_SESSION['message'] = "Error: No se encontró el expediente del paciente.";
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }

    // Sanitize form data
    $tipo_laser_derecho = $_POST['tipo_laser_derecho'] ?? 'Selecciona';
    $detalles_laser_derecho = $conexion->real_escape_string($_POST['detalles_laser_derecho'] ?? '');
    $tipo_laser_izquierdo = $_POST['tipo_laser_izquierdo'] ?? 'Selecciona';
    $detalles_laser_izquierdo = $conexion->real_escape_string($_POST['detalles_laser_izquierdo'] ?? '');

    // Insert data
    $stmt = $conexion->prepare("INSERT INTO ocular_tratamiento_laser (
        id_atencion, Id_exp, id_usua, tipo_laser_derecho, detalles_laser_derecho, tipo_laser_izquierdo, detalles_laser_izquierdo
    ) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        $_SESSION['message'] = "Error preparando la consulta: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }

    $stmt->bind_param(
        "iiissss",
        $id_atencion,
        $id_exp,
        $id_usua,
        $tipo_laser_derecho,
        $detalles_laser_derecho,
        $tipo_laser_izquierdo,
        $detalles_laser_izquierdo
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Tratamiento láser guardado exitosamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al guardar: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    $conexion->close();
    header("Location: diagnostico.php");
    exit();
} else {
    $_SESSION['message'] = "Método no permitido.";
    $_SESSION['message_type'] = "danger";
    header("Location: diagnostico.php");
    exit();
}
?>