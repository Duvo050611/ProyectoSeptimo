<?php
include "../../conexionbd.php";
session_start();

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

    $id_atencion = $_SESSION['hospital'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $previa_tipo1 = $_POST['previa_tipo1'] ?? '';
    $previa1_od_esf = $_POST['previa1_od_esf'] ?? '';
    $previa1_od_cil = $_POST['previa1_od_cil'] ?? '';
    $previa1_od_eje = $_POST['previa1_od_eje'] ?? '';
    $previa1_od_dip = $_POST['previa1_od_dip'] ?? '';
    $previa1_oi_esf = $_POST['previa1_oi_esf'] ?? '';
    $previa1_oi_cil = $_POST['previa1_oi_cil'] ?? '';
    $previa1_oi_eje = $_POST['previa1_oi_eje'] ?? '';
    $previa1_oi_dip = $_POST['previa1_oi_dip'] ?? '';

    $previa_tipo2 = $_POST['previa_tipo2'] ?? '';
    $previa2_od_esf = $_POST['previa2_od_esf'] ?? '';
    $previa2_od_cil = $_POST['previa2_od_cil'] ?? '';
    $previa2_od_eje = $_POST['previa2_od_eje'] ?? '';
    $previa2_od_dip = $_POST['previa2_od_dip'] ?? '';
    $previa2_oi_esf = $_POST['previa2_oi_esf'] ?? '';
    $previa2_oi_cil = $_POST['previa2_oi_cil'] ?? '';
    $previa2_oi_eje = $_POST['previa2_oi_eje'] ?? '';
    $previa2_oi_dip = $_POST['previa2_oi_dip'] ?? '';

    $nueva_sin_od_esf = $_POST['nueva_sin_od_esf'] ?? '';
    $nueva_sin_od_cil = $_POST['nueva_sin_od_cil'] ?? '';
    $nueva_sin_od_eje = $_POST['nueva_sin_od_eje'] ?? '';
    $nueva_sin_oi_esf = $_POST['nueva_sin_oi_esf'] ?? '';
    $nueva_sin_oi_cil = $_POST['nueva_sin_oi_cil'] ?? '';
    $nueva_sin_oi_eje = $_POST['nueva_sin_oi_eje'] ?? '';

    $ciclo_tipo = $_POST['ciclo_tipo'] ?? '';
    $nueva_con_od_esf = $_POST['nueva_con_od_esf'] ?? '';
    $nueva_con_od_cil = $_POST['nueva_con_od_cil'] ?? '';
    $nueva_con_od_eje = $_POST['nueva_con_od_eje'] ?? '';
    $nueva_con_od_dip = $_POST['nueva_con_od_dip'] ?? '';
    $nueva_con_oi_esf = $_POST['nueva_con_oi_esf'] ?? '';
    $nueva_con_oi_cil = $_POST['nueva_con_oi_cil'] ?? '';
    $nueva_con_oi_eje = $_POST['nueva_con_oi_eje'] ?? '';
    $nueva_con_oi_dip = $_POST['nueva_con_oi_dip'] ?? '';

    $ret_ref_od_esf = $_POST['ret_ref_od_esf'] ?? '';
    $ret_ref_od_cil = $_POST['ret_ref_od_cil'] ?? '';
    $ret_ref_od_eje = $_POST['ret_ref_od_eje'] ?? '';
    $ret_ref_oi_esf = $_POST['ret_ref_oi_esf'] ?? '';
    $ret_ref_oi_cil = $_POST['ret_ref_oi_cil'] ?? '';
    $ret_ref_oi_eje = $_POST['ret_ref_oi_eje'] ?? '';

    $q_od_k1 = $_POST['q_od_k1'] ?? '';
    $q_od_k1_eje = $_POST['q_od_k1_eje'] ?? '';
    $q_od_k2 = $_POST['q_od_k2'] ?? '';
    $q_od_k2_eje = $_POST['q_od_k2_eje'] ?? '';
    $q_od_cyl = $_POST['q_od_cyl'] ?? '';
    $q_od_cyl_eje = $_POST['q_od_cyl_eje'] ?? '';
    $q_oi_k1 = $_POST['q_oi_k1'] ?? '';
    $q_oi_k1_eje = $_POST['q_oi_k1_eje'] ?? '';
    $q_oi_k2 = $_POST['q_oi_k2'] ?? '';
    $q_oi_k2_eje = $_POST['q_oi_k2_eje'] ?? '';
    $q_oi_cyl = $_POST['q_oi_cyl'] ?? '';
    $q_oi_cyl_eje = $_POST['q_oi_cyl_eje'] ?? '';

    $sql = "INSERT INTO autorefractor (
        id_atencion,previa_tipo1, previa1_od_esf, previa1_od_cil, previa1_od_eje, previa1_od_dip,
        previa1_oi_esf, previa1_oi_cil, previa1_oi_eje, previa1_oi_dip,
        previa_tipo2, previa2_od_esf, previa2_od_cil, previa2_od_eje, previa2_od_dip,
        previa2_oi_esf, previa2_oi_cil, previa2_oi_eje, previa2_oi_dip,
        nueva_sin_od_esf, nueva_sin_od_cil, nueva_sin_od_eje,
        nueva_sin_oi_esf, nueva_sin_oi_cil, nueva_sin_oi_eje,
        ciclo_tipo, nueva_con_od_esf, nueva_con_od_cil, nueva_con_od_eje, nueva_con_od_dip,
        nueva_con_oi_esf, nueva_con_oi_cil, nueva_con_oi_eje, nueva_con_oi_dip,
        ret_ref_od_esf, ret_ref_od_cil, ret_ref_od_eje,
        ret_ref_oi_esf, ret_ref_oi_cil, ret_ref_oi_eje,
        q_od_k1, q_od_k1_eje, q_od_k2, q_od_k2_eje, q_od_cyl, q_od_cyl_eje,
        q_oi_k1, q_oi_k1_eje, q_oi_k2, q_oi_k2_eje, q_oi_cyl, q_oi_cyl_eje
    ) VALUES (
    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
)";
    $parametros_post = [
    $id_atencion,
    $previa_tipo1, $previa1_od_esf, $previa1_od_cil, $previa1_od_eje, $previa1_od_dip,
    $previa1_oi_esf, $previa1_oi_cil, $previa1_oi_eje, $previa1_oi_dip,
    $previa_tipo2, $previa2_od_esf, $previa2_od_cil, $previa2_od_eje, $previa2_od_dip,
    $previa2_oi_esf, $previa2_oi_cil, $previa2_oi_eje, $previa2_oi_dip,
    $nueva_sin_od_esf, $nueva_sin_od_cil, $nueva_sin_od_eje,
    $nueva_sin_oi_esf, $nueva_sin_oi_cil, $nueva_sin_oi_eje,
    $ciclo_tipo, $nueva_con_od_esf, $nueva_con_od_cil, $nueva_con_od_eje, $nueva_con_od_dip,
    $nueva_con_oi_esf, $nueva_con_oi_cil, $nueva_con_oi_eje, $nueva_con_oi_dip,
    $ret_ref_od_esf, $ret_ref_od_cil, $ret_ref_od_eje,
    $ret_ref_oi_esf, $ret_ref_oi_cil, $ret_ref_oi_eje,
    $q_od_k1, $q_od_k1_eje, $q_od_k2, $q_od_k2_eje, $q_od_cyl, $q_od_cyl_eje,
    $q_oi_k1, $q_oi_k1_eje, $q_oi_k2, $q_oi_k2_eje, $q_oi_cyl, $q_oi_cyl_eje
];

    $parametros_llegan = count($parametros_post);

    preg_match_all('/\?/', $sql, $matches);
    $parametros_esperados = count($matches[0]);

    echo "<pre>";
    echo "Parámetros que llegan: $parametros_llegan\n";
    echo "Parámetros que espera el SQL: $parametros_esperados\n";
    echo "</pre>";


    $stmt = $conexion->prepare($sql);
    $stmt->bind_param(
    "ssssssssssssssssssssssssssssssssssssssssssssssssssss",
    $id_atencion,$previa_tipo1, $previa1_od_esf, $previa1_od_cil, $previa1_od_eje, $previa1_od_dip,
    $previa1_oi_esf, $previa1_oi_cil, $previa1_oi_eje, $previa1_oi_dip,
    $previa_tipo2, $previa2_od_esf, $previa2_od_cil, $previa2_od_eje, $previa2_od_dip,
    $previa2_oi_esf, $previa2_oi_cil, $previa2_oi_eje, $previa2_oi_dip,
    $nueva_sin_od_esf, $nueva_sin_od_cil, $nueva_sin_od_eje,
    $nueva_sin_oi_esf, $nueva_sin_oi_cil, $nueva_sin_oi_eje,
    $ciclo_tipo, $nueva_con_od_esf, $nueva_con_od_cil, $nueva_con_od_eje, $nueva_con_od_dip,
    $nueva_con_oi_esf, $nueva_con_oi_cil, $nueva_con_oi_eje, $nueva_con_oi_dip,
    $ret_ref_od_esf, $ret_ref_od_cil, $ret_ref_od_eje,
    $ret_ref_oi_esf, $ret_ref_oi_cil, $ret_ref_oi_eje,
    $q_od_k1, $q_od_k1_eje, $q_od_k2, $q_od_k2_eje, $q_od_cyl, $q_od_cyl_eje,
    $q_oi_k1, $q_oi_k1_eje, $q_oi_k2, $q_oi_k2_eje, $q_oi_cyl, $q_oi_cyl_eje
);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Datos guardados correctamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al guardar los datos: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }
    $stmt->close();
    header("Location: autorefractor.php");
    exit();
}
?>