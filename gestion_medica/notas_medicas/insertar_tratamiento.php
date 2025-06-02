<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital']) || !isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_atencion = $_SESSION['hospital'];
    $id_usua = isset($_POST['id_usua']) ? (int)$_POST['id_usua'] : 0;

    // Validate id_usua
    if ($id_usua === 0) {
        $_SESSION['message'] = "Error: ID de usuario no válido.";
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }

    // Get Id_exp from dat_ingreso
    $stmt = $conexion->prepare("SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?");
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id_exp = $row['Id_exp'] ?? null;
    $stmt->close();

    if (!$id_exp) {
        $_SESSION['message'] = "Error: No se encontró el expediente para esta atención.";
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }

    // Get form data
    $medicamento_derecho = trim($_POST['medicamento_derecho'] ?? '');
    $tipo_tratamiento_derecho = trim($_POST['tipo_tratamiento_derecho'] ?? '');
    $procedimientos_derecho = trim($_POST['procedimientos_derecho'] ?? '');
    $quirurgico_derecho = trim($_POST['quirurgico_derecho'] ?? '');
    $medicamento_izquierdo = trim($_POST['medicamento_izquierdo'] ?? '');
    $tipo_tratamiento_izquierdo = trim($_POST['tipo_tratamiento_izquierdo'] ?? '');
    $procedimientos_izquierdo = trim($_POST['procedimientos_izquierdo'] ?? '');
    $quirurgico_izquierdo = trim($_POST['quirurgico_izquierdo'] ?? '');

    // Check if at least one field is filled
    if (empty($medicamento_derecho) && empty($tipo_tratamiento_derecho) && empty($procedimientos_derecho) && empty($quirurgico_derecho) &&
        empty($medicamento_izquierdo) && empty($tipo_tratamiento_izquierdo) && empty($procedimientos_izquierdo) && empty($quirurgico_izquierdo)) {
        $_SESSION['message'] = "Error: Debe completar al menos un campo de tratamiento.";
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }

    // Insert into ocular_tratamiento
    $stmt = $conexion->prepare("
        INSERT INTO ocular_tratamiento (
            Id_exp, id_atencion, id_usua, medicamento_derecho, tipo_tratamiento_derecho, procedimientos_derecho, quirurgico_derecho,
            medicamento_izquierdo, tipo_tratamiento_izquierdo, procedimientos_izquierdo, quirurgico_izquierdo
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        $_SESSION['message'] = "Error preparando la consulta: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }

    $stmt->bind_param(
        "iiissssssss",
        $id_exp,
        $id_atencion,
        $id_usua,
        $medicamento_derecho,
        $tipo_tratamiento_derecho,
        $procedimientos_derecho,
        $quirurgico_derecho,
        $medicamento_izquierdo,
        $tipo_tratamiento_izquierdo,
        $procedimientos_izquierdo,
        $quirurgico_izquierdo
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Tratamiento registrado exitosamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al registrar: " . $stmt->error;
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