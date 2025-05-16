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
        echo '<script>alert("Error: No se encontró el expediente del paciente."); window.location.href="tratamiento_laser.php";</script>';
        exit();
    }

    $tipo_laser_derecho = $_POST['tipo_laser_derecho'] ?? 'Selecciona';
    $detalles_laser_derecho = $_POST['detalles_laser_derecho'] ?? '';
    $tipo_laser_izquierdo = $_POST['tipo_laser_izquierdo'] ?? 'Selecciona';
    $detalles_laser_izquierdo = $_POST['detalles_laser_izquierdo'] ?? '';

    $stmt = $conexion->prepare("INSERT INTO ocular_tratamiento_laser (
        id_atencion, Id_exp, tipo_laser_derecho, detalles_laser_derecho, tipo_laser_izquierdo, detalles_laser_izquierdo
    ) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isssss",
        $id_atencion,
        $id_exp,
        $tipo_laser_derecho,
        $detalles_laser_derecho,
        $tipo_laser_izquierdo,
        $detalles_laser_izquierdo
    );

    if ($stmt->execute()) {
        echo '<script>alert("Tratamiento láser registrado correctamente."); window.location.href="tratamiento_laser.php";</script>';
    } else {
        echo '<script>alert("Error al registrar el tratamiento láser: ' . $stmt->error . '"); window.location.href="tratamiento_laser.php";</script>';
    }
    $stmt->close();
    $conexion->close();
} else {
    header("Location: tratamiento_laser.php");
    exit();
}
?>