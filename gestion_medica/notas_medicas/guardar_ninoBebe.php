<?php
include '../../conexionbd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_exp = $_POST['id_exp'];
    $id_usua = $_POST['id_usua'];
    $id_atencion = $_POST['id_atencion'];

    $reflejo_od = $_POST['reflejo_od'] ?? null;
    $eje_visual_od = $_POST['eje_visual_od'] ?? null;
    $fijacion_od = $_POST['fijacion_od'] ?? null;
    $esquiascopia_od = $_POST['esquiascopia_od'] ?? null;
    $posicion_od = $_POST['posicion_od'] ?? null;

    $reflejo_oi = $_POST['reflejo_oi'] ?? null;
    $eje_visual_oi = $_POST['eje_visual_oi'] ?? null;
    $fijacion_oi = $_POST['fijacion_oi'] ?? null;
    $esquiascopia_oi = $_POST['esquiascopia_oi'] ?? null;
    $posicion_oi = $_POST['posicion_oi'] ?? null;

    $sql = "INSERT INTO ninobebe (
        reflejo_od, eje_visual_od, fijacion_od, esquiascopia_od, posicion_od,
        reflejo_oi, eje_visual_oi, fijacion_oi, esquiascopia_oi, posicion_oi,
        id_exp, id_usua, id_atencion
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param(
        "ssssssssssiii",
        $reflejo_od, $eje_visual_od, $fijacion_od, $esquiascopia_od, $posicion_od,
        $reflejo_oi, $eje_visual_oi, $fijacion_oi, $esquiascopia_oi, $posicion_oi,
        $id_exp, $id_usua, $id_atencion
    );

    if ($stmt->execute()) {
        header("Location: formulario_nino_bebe.php");
        exit();
    } else {
        echo "Error al guardar: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Acceso no permitido.";
}
