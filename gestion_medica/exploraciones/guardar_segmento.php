<?php
include 'conexion.php';

$cornea = $_POST['cornea'];
$conjuntiva = $_POST['conjuntiva'];
$camara_anterior = $_POST['camara_anterior'];
$pupila = $_POST['pupila'];
$iris = $_POST['iris'];
$observaciones = $_POST['observaciones'];

$sql = "INSERT INTO segmento_anterior (cornea, conjuntiva, camara_anterior, pupila, iris, observaciones, fecha_registro)
        VALUES ('$cornea', '$conjuntiva', '$camara_anterior', '$pupila', '$iris', '$observaciones', NOW())";

if ($conn->query($sql) === TRUE) {
    header("Location: listar_segmento.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
