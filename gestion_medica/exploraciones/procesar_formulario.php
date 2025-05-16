<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $campos = [
        "id_exp", "apertura_palpebral", "hendidura_palpebral", "funcion_musculo_elevador",
        "distancia_margen_reflejo_1", "distancia_margen_reflejo_2", "exposicion_escleral_superior", "exposicion_escleral_inferior",
        "altura_surco", "distancia_ceja_pestana", "fenomeno_bell", "laxitud_horizontal", "laxitud_vertical",
        "exoftalmometria", "exoftalmometria_base", "desplazamiento_ocular", "maniobra_vatsaha", "observaciones",
        "apertura_palpebral_oi", "hendidura_palpebral_oi", "funcion_musculo_elevador_oi", "fenomeno_bell_oi",
        "laxitud_horizontal_oi", "laxitud_vertical_oi", "desplazamiento_ocular_oi", "maniobra_vatsaha_oi"
    ];

    $datos = [];
    foreach ($campos as $campo) {
        $datos[$campo] = $_POST[$campo] ?? null;
    }

    $stmt = $conn->prepare("
        INSERT INTO exploraciones (
            id_exp, apertura_palpebral, hendidura_palpebral, funcion_musculo_elevador,
            distancia_margen_reflejo_1, distancia_margen_reflejo_2, exposicion_escleral_superior, exposicion_escleral_inferior,
            altura_surco, distancia_ceja_pestana, fenomeno_bell, laxitud_horizontal, laxitud_vertical,
            exoftalmometria, exoftalmometria_base, desplazamiento_ocular, maniobra_vatsaha, observaciones,
            apertura_palpebral_oi, hendidura_palpebral_oi, funcion_musculo_elevador_oi, fenomeno_bell_oi,
            laxitud_horizontal_oi, laxitud_vertical_oi, desplazamiento_ocular_oi, maniobra_vatsaha_oi
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

    $stmt->bind_param(
        "iddddddddddsssdsssdddsssss",
        $datos["id_exp"], $datos["apertura_palpebral"], $datos["hendidura_palpebral"], $datos["funcion_musculo_elevador"],
        $datos["distancia_margen_reflejo_1"], $datos["distancia_margen_reflejo_2"], $datos["exposicion_escleral_superior"], $datos["exposicion_escleral_inferior"],
        $datos["altura_surco"], $datos["distancia_ceja_pestana"], $datos["fenomeno_bell"], $datos["laxitud_horizontal"], $datos["laxitud_vertical"],
        $datos["exoftalmometria"], $datos["exoftalmometria_base"], $datos["desplazamiento_ocular"], $datos["maniobra_vatsaha"], $datos["observaciones"],
        $datos["apertura_palpebral_oi"], $datos["hendidura_palpebral_oi"], $datos["funcion_musculo_elevador_oi"], $datos["fenomeno_bell_oi"],
        $datos["laxitud_horizontal_oi"], $datos["laxitud_vertical_oi"], $datos["desplazamiento_ocular_oi"], $datos["maniobra_vatsaha_oi"]
    );

    if ($stmt->execute()) {
        header("Location: listar_exploraciones.php?exito=1");
        exit();
    } else {
        echo "Error al insertar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
