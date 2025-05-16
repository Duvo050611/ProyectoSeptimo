<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $cornea = $_POST['cornea'];
    $conjuntiva = $_POST['conjuntiva'];
    $camara_anterior = $_POST['camara_anterior'];
    $pupila = $_POST['pupila'];
    $iris = $_POST['iris'];
    $observaciones = $_POST['observaciones'];

    $sql = "UPDATE segmento_anterior SET 
        cornea = ?, 
        conjuntiva = ?, 
        camara_anterior = ?, 
        pupila = ?, 
        iris = ?, 
        observaciones = ? 
        WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $cornea, $conjuntiva, $camara_anterior, $pupila, $iris, $observaciones, $id);

    if ($stmt->execute()) {
        header("Location: listar_segmento.php?mensaje=actualizado");
        exit();
    } else {
        echo "Error al actualizar el registro: " . $conn->error;
    }
} else {
    echo "Acceso no permitido.";
}
?>
