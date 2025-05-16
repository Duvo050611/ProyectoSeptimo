<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    $campos = [
        'apertura_palpebral', 'hendidura_palpebral', 'funcion_musculo_elevador',
        'fenomeno_bell', 'laxitud_horizontal', 'laxitud_vertical',
        'desplazamiento_ocular', 'maniobra_vatsaha', 'observaciones'
    ];

    $valores = [];
    foreach ($campos as $campo) {
        $valores[$campo] = isset($_POST[$campo]) ? $_POST[$campo] : null;
    }

    $sql = "UPDATE exploraciones SET
        apertura_palpebral = ?, hendidura_palpebral = ?, funcion_musculo_elevador = ?,
        fenomeno_bell = ?, laxitud_horizontal = ?, laxitud_vertical = ?,
        desplazamiento_ocular = ?, maniobra_vatsaha = ?, observaciones = ?
        WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "dddsdssssi",
        $valores['apertura_palpebral'],
        $valores['hendidura_palpebral'],
        $valores['funcion_musculo_elevador'],
        $valores['fenomeno_bell'],
        $valores['laxitud_horizontal'],
        $valores['laxitud_vertical'],
        $valores['desplazamiento_ocular'],
        $valores['maniobra_vatsaha'],
        $valores['observaciones'],
        $id
    );

    if ($stmt->execute()) {
        header("Location: listar_exploraciones.php?mensaje=actualizado");
        exit();
    } else {
        echo "<div class='container mt-5 alert alert-danger'>Error al actualizar: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Acceso no permitido.";
}
?>
