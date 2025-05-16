<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $papila = $_POST['papila'];
    $retina = $_POST['retina'];
    $macula = $_POST['macula'];
    $vasos_retinianos = $_POST['vasos_retinianos'];
    $observaciones = $_POST['observaciones'];
    $fecha_registro = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO segmento_posterior (papila, retina, macula, vasos_retinianos, observaciones, fecha_registro) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $papila, $retina, $macula, $vasos_retinianos, $observaciones, $fecha_registro);

    if ($stmt->execute()) {
        header("Location: listar_segmento_posterior.php?mensaje=guardado");
    } else {
        echo "Error al guardar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

