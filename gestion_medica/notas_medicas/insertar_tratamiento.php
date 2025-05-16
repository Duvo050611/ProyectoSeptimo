<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_atencion = $_SESSION['hospital'];
    $stmt = $conexion->prepare("SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?");
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    $result = $stmt->get_result();
    $id_exp = $result->fetch_assoc()['Id_exp'];
    $stmt->close();

    if (!$id_exp) {
        echo '<script>alert("Error: No se encontró el expediente del paciente."); window.location.href="tratamiento.php";</script>';
        exit();
    }

    $oftalmologicamente_sano = isset($_POST['oftalmologicamente_sano']) ? 1 : 0;
    $sin_tratamiento = isset($_POST['sin_tratamiento']) ? 1 : 0;
    $tratamiento_previo_derecho = $_POST['tratamiento_previo_derecho'] ?? '';
    $tratamiento_previo_izquierdo = $_POST['tratamiento_previo_izquierdo'] ?? '';
    $medicamento_derecho = $_POST['medicamento_derecho'] ?? '';
    $codigo_tratamiento_derecho = $_POST['codigo_tratamiento_derecho'] ?? '';
    $desc_tratamiento_derecho = $_POST['desc_tratamiento_derecho'] ?? '';
    $tipo_tratamiento_derecho = $_POST['tipo_tratamiento_derecho'] ?? 'Primera Vez';
    $procedimientos_derecho = $_POST['procedimientos_derecho'] ?? '';
    $quirurgico_derecho = $_POST['quirurgico_derecho'] ?? '';
    $medicamento_izquierdo = $_POST['medicamento_izquierdo'] ?? '';
    $codigo_tratamiento_izquierdo = $_POST['codigo_tratamiento_izquierdo'] ?? '';
    $desc_tratamiento_izquierdo = $_POST['desc_tratamiento_izquierdo'] ?? '';
    $tipo_tratamiento_izquierdo = $_POST['tipo_tratamiento_izquierdo'] ?? 'Primera Vez';
    $procedimientos_izquierdo = $_POST['procedimientos_izquierdo'] ?? '';
    $quirurgico_izquierdo = $_POST['quirurgico_izquierdo'] ?? '';

    $stmt = $conexion->prepare("INSERT INTO ocular_tratamiento (
        id_atencion, Id_exp, oftalmologicamente_sano, sin_tratamiento,
        tratamiento_previo_derecho, tratamiento_previo_izquierdo,
        medicamento_derecho, codigo_tratamiento_derecho, desc_tratamiento_derecho, tipo_tratamiento_derecho, procedimientos_derecho, quirurgico_derecho,
        medicamento_izquierdo, codigo_tratamiento_izquierdo, desc_tratamiento_izquierdo, tipo_tratamiento_izquierdo, procedimientos_izquierdo, quirurgico_izquierdo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isiiisssssssssssss",
        $id_atencion,
        $id_exp,
        $oftalmologicamente_sano,
        $sin_tratamiento,
        $tratamiento_previo_derecho,
        $tratamiento_previo_izquierdo,
        $medicamento_derecho,
        $codigo_tratamiento_derecho,
        $desc_tratamiento_derecho,
        $tipo_tratamiento_derecho,
        $procedimientos_derecho,
        $quirurgico_derecho,
        $medicamento_izquierdo,
        $codigo_tratamiento_izquierdo,
        $desc_tratamiento_izquierdo,
        $tipo_tratamiento_izquierdo,
        $procedimientos_izquierdo,
        $quirurgico_izquierdo
    );

    if ($stmt->execute()) {
        echo '<script>alert("Tratamiento registrado correctamente."); window.location.href="tratamiento.php";</script>';
    } else {
        echo '<script>alert("Error al registrar el tratamiento: ' . $stmt->error . '"); window.location.href="tratamiento.php";</script>';
    }
    $stmt->close();
    $conexion->close();
} else {
    header("Location: tratamiento.php");
    exit();
}
?>