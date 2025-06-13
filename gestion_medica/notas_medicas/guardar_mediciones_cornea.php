<?php
include "../../conexionbd.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conexion->prepare("INSERT INTO mediciones_cornea (
        id_exp, id_usua, id_atencion,
        od_dcv, od_dch, od_dpf, od_dpm, od_micro, od_paq,
        oi_dcv, oi_dch, oi_dpf, oi_dpm, oi_micro, oi_paq
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("iiissssssssssss",
        $_POST['id_exp'], $_POST['id_usua'], $_POST['id_atencion'],
        $_POST['od_dcv'], $_POST['od_dch'], $_POST['od_dpf'], $_POST['od_dpm'], $_POST['od_micro'], $_POST['od_paq'],
        $_POST['oi_dcv'], $_POST['oi_dch'], $_POST['oi_dpf'], $_POST['oi_dpm'], $_POST['oi_micro'], $_POST['oi_paq']
    );

    if ($stmt->execute()) {
        header("Location: formulario_mediciones_cornea.php");
        exit();
    } else {
        echo "Error al guardar los datos: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Acceso denegado.";
}
?>
