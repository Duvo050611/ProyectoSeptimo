<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_atencion = $_SESSION['hospital'];

    $sql = "SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        $_SESSION['message'] = "Error preparando la consulta: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: refraccion_actual.php");
        exit();
    }
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $id_exp = $row['Id_exp'];
    } else {
        $_SESSION['message'] = "No se encontró Id_exp para id_atencion = $id_atencion";
        $_SESSION['message_type'] = "danger";
        header("Location: refraccion_actual.php");
        exit();
    }
    $stmt->close();

    function obtener($nombre) {
        global $conexion;
        return isset($_POST[$nombre]) ? $conexion->real_escape_string($_POST[$nombre]) : '';
    }

    function obtenerCheck($nombre) {
        return isset($_POST[$nombre]) ? 1 : 0;
    }

    $campos = [
        'av_binocular', 'av_lejana_sin_correc', 'av_estenopico', 'av_lejana_con_correc_prop',
        'av_lejana_mejor_corregida', 'av_potencial', 'oi_lejana_sin_correc', 'oi_estenopico',
        'oi_lejana_con_correc_prop', 'oi_lejana_mejor_corregida', 'oi_potencial', 'detalle_refra',
        'esferas_sin_ciclo_od', 'cilindros_sin_ciclo_od', 'eje_sin_ciclo_od', 'add_sin_ciclo_od',
        'dip_sin_ciclo_od', 'prisma_sin_ciclo_od', 'esferas_sin_ciclo_oi', 'cilindros_sin_ciclo_oi',
        'eje_sin_ciclo_oi', 'add_sin_ciclo_oi', 'dip_sin_ciclo_oi', 'prisma_sin_ciclo_oi',
        'detalle_ref_subjetiv_sin', 'esferas_con_ciclo_od', 'cilindros_con_ciclo_od',
        'eje_con_ciclo_od', 'add_con_ciclo_od', 'dip_con_ciclo_od', 'prisma_con_ciclo_od',
        'esferas_con_ciclo_oi', 'cilindros_con_ciclo_oi', 'eje_con_ciclo_oi', 'add_con_ciclo_oi',
        'dip_con_ciclo_oi', 'prisma_con_ciclo_oi', 'av_intermedia_od', 'av_intermedia_oi',
        'av_cercana_sin_corr_od', 'av_cercana_sin_corr_oi', 'av_cercana_con_corr_od',
        'av_cercana_con_corr_oi', 'detalle_ref_subjetiv', 'esf_cerca_od', 'cil_cerca_od',
        'eje_cerca_od', 'prisma_cerca_od', 'esf_cerca_oi', 'cil_cerca_oi', 'eje_cerca_oi',
        'dip_cerca_oi', 'prisma_cerca_oi'
    ];

    $valores = [];
    foreach ($campos as $campo) {
        if (strpos($campo, 'prisma') !== false) {
            $valores[] = obtenerCheck($campo);
        } else {
            $valores[] = obtener($campo);
        }
    }

    $placeholders = implode(", ", array_fill(0, count($valores), "?"));
    $tipos = str_repeat("s", count($valores)); 

    $sql = "INSERT INTO refraccion_actual (" . implode(", ", $campos) . ") VALUES ($placeholders)";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        $_SESSION['message'] = "Error preparando el INSERT: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: refraccion_actual.php");
        exit();
    }

    $stmt->bind_param($tipos, ...$valores);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Refracción actual guardada correctamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al guardar la refracción: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    $conexion->close();

    header("Location: refraccion_actual.php");
    exit();
}
?>
