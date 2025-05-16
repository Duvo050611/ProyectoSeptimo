<?php
include 'conexion.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $conn->query("DELETE FROM segmento_anterior WHERE id = $id");
}

header("Location: listar_segmento.php");
exit;
