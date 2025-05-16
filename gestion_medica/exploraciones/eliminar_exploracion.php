<?php
require 'conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM exploraciones WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: listar_exploraciones.php?mensaje=eliminado");
    } else {
        echo "Error al eliminar el registro.";
    }

    $stmt->close();
} else {
    echo "ID no proporcionado.";
}
?>
