<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'];

// Retrieve form data
$biometria_hematica = isset($_POST['biometria_hematica']) ? 1 : 0;
$quimica_sanguinea = isset($_POST['quimica_sanguinea']) ? 1 : 0;
$quimica_sanguinea_valores = isset($_POST['quimica_sanguinea_valores']) ? (int)$_POST['quimica_sanguinea_valores'] : 0;
$tiempos_coagulacion = isset($_POST['tiempos_coagulacion']) ? 1 : 0;
$hemoglobina_glucosilada = isset($_POST['hemoglobina_glucosilada']) ? 1 : 0;
$examen_general_orina = isset($_POST['examen_general_orina']) ? 1 : 0;
$electrocardiograma = isset($_POST['electrocardiograma']) ? 1 : 0;
$pruebas_funcion_hepatica = isset($_POST['pruebas_funcion_hepatica']) ? 1 : 0;
$antigeno_sars_cov_2 = isset($_POST['antigeno_sars_cov_2']) ? 1 : 0;
$pcr_sars_cov_2 = isset($_POST['pcr_sars_cov_2']) ? 1 : 0;
$electrolitos_sericos = isset($_POST['electrolitos_sericos']) ? 1 : 0;
$pruebas_funcion_tiroidea = isset($_POST['pruebas_funcion_tiroidea']) ? 1 : 0;
$acs_anti_tiroglubolina = isset($_POST['acs_anti_tiroglubolina']) ? 1 : 0;
$acs_antireceptores_tsh = isset($_POST['acs_antireceptores_tsh']) ? 1 : 0;
$acs_antiperoxidasa = isset($_POST['acs_antiperoxidasa']) ? 1 : 0;
$velocidad_sedimentacion_globular = isset($_POST['velocidad_sedimentacion_globular']) ? 1 : 0;
$proteina_c_reactiva = isset($_POST['proteina_c_reactiva']) ? 1 : 0;
$vdrl = isset($_POST['vdrl']) ? 1 : 0;
$fta_abs = isset($_POST['fta_abs']) ? 1 : 0;
$ppd = isset($_POST['ppd']) ? 1 : 0;
$elisa_vih_1_y_2 = isset($_POST['elisa_vih_1_y_2']) ? 1 : 0;
$acs_toxoplasmosis_igg_igm = isset($_POST['acs_toxoplasmosis_igg_igm']) ? 1 : 0;
$factor_reumatoide = isset($_POST['factor_reumatoide']) ? 1 : 0;
$acs_anti_ccp = isset($_POST['acs_anti_ccp']) ? 1 : 0;
$antigeno_hla_b27 = isset($_POST['antigeno_hla_b27']) ? 1 : 0;
$acs_antinucleares = isset($_POST['acs_antinucleares']) ? 1 : 0;
$acs_anticardiolipina = isset($_POST['acs_anticardiolipina']) ? 1 : 0;
$acs_p_ancasy_c_ancas = isset($_POST['acs_p_ancasy_c_ancas']) ? 1 : 0;
$otros_laboratorio = isset($_POST['otros_laboratorio']) ? mysqli_real_escape_string($conexion, $_POST['otros_laboratorio']) : '';

// Gabinete exams
$calculo_lio_iol_master = isset($_POST['calculo_lio_iol_master']) ? 1 : 0;
$calculo_lio_inmersion = isset($_POST['calculo_lio_inmersion']) ? 1 : 0;
$topografia_corneal_opcion = isset($_POST['topografia_corneal_opcion']) ? mysqli_real_escape_string($conexion, $_POST['topografia_corneal_opcion']) : '';
$microscopia_especular = isset($_POST['microscopia_especular']) ? 1 : 0;
$paquimetria = isset($_POST['paquimetria']) ? 1 : 0;
$ultrabiomicroscopia = isset($_POST['ultrabiomicroscopia']) ? 1 : 0;
$fotografia_segmento_anterior = isset($_POST['fotografia_segmento_anterior']) ? 1 : 0;
$angiografia_opcion = isset($_POST['angiografia_opcion']) ? mysqli_real_escape_string($conexion, $_POST['angiografia_opcion']) : '';
$oct_macular = isset($_POST['oct_macular']) ? 1 : 0;
$campos_visuales_opcion = isset($_POST['campos_visuales_opcion']) ? mysqli_real_escape_string($conexion, $_POST['campos_visuales_opcion']) : '';
$oct_nervio_optico = isset($_POST['oct_nervio_optico']) ? 1 : 0;
$hrt_nervio_optico = isset($_POST['hrt_nervio_optico']) ? 1 : 0;
$gdx_analisis_fibras_nerviosas = isset($_POST['gdx_analisis_fibras_nerviosas']) ? 1 : 0;
$curva_horaria_pio = isset($_POST['curva_horaria_pio']) ? 1 : 0;
$resonancia_magnetica_orbita = isset($_POST['resonancia_magnetica_orbita']) ? 1 : 0;
$tomografia_orbita = isset($_POST['tomografia_orbita']) ? 1 : 0;
$autofluorescencia_infrarrojo = isset($_POST['autofluorescencia_infrarrojo']) ? 1 : 0;
$ecografia_opcion = isset($_POST['ecografia_opcion']) ? mysqli_real_escape_string($conexion, $_POST['ecografia_opcion']) : '';
$fotografia_9_campos = isset($_POST['fotografia_9_campos']) ? 1 : 0;
$fotografia_fondo_ojo = isset($_POST['fotografia_fondo_ojo']) ? 1 : 0;
$fotografia_nervio_optico = isset($_POST['fotografia_nervio_optico']) ? 1 : 0;
$otros_gabinete = isset($_POST['otros_gabinete']) ? mysqli_real_escape_string($conexion, $_POST['otros_gabinete']) : '';

try {
    // Insert into the database
    $sql = "INSERT INTO ocular_examenes_lab_gabinete (
        id_atencion, biometria_hematica, quimica_sanguinea, quimica_sanguinea_valores, tiempos_coagulacion, hemoglobina_glucosilada,
        examen_general_orina, electrocardiograma, pruebas_funcion_hepatica, antigeno_sars_cov_2, pcr_sars_cov_2, electrolitos_sericos,
        pruebas_funcion_tiroidea, acs_anti_tiroglubolina, acs_antireceptores_tsh, acs_antiperoxidasa, velocidad_sedimentacion_globular,
        proteina_c_reactiva, vdrl, fta_abs, ppd, elisa_vih_1_y_2, acs_toxoplasmosis_igg_igm, factor_reumatoide, acs_anti_ccp,
        antigeno_hla_b27, acs_antinucleares, acs_anticardiolipina, acs_p_ancasy_c_ancas, otros_laboratorio,
        calculo_lio_iol_master, calculo_lio_inmersion, topografia_corneal_opcion, microscopia_especular, paquimetria,
        ultrabiomicroscopia, fotografia_segmento_anterior, angiografia_opcion, oct_macular, campos_visuales_opcion,
        oct_nervio_optico, hrt_nervio_optico, gdx_analisis_fibras_nerviosas, curva_horaria_pio, resonancia_magnetica_orbita,
        tomografia_orbita, autofluorescencia_infrarrojo, ecografia_opcion, fotografia_9_campos, fotografia_fondo_ojo,
        fotografia_nervio_optico, otros_gabinete
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conexion->error);
    }

    $stmt->bind_param("iiiiiiiiiiiiiiiiiiiiiiiiiiiiisiiiiiiiisissiiiiissssi",
        $id_atencion, $biometria_hematica, $quimica_sanguinea, $quimica_sanguinea_valores, $tiempos_coagulacion, $hemoglobina_glucosilada,
        $examen_general_orina, $electrocardiograma, $pruebas_funcion_hepatica, $antigeno_sars_cov_2, $pcr_sars_cov_2, $electrolitos_sericos,
        $pruebas_funcion_tiroidea, $acs_anti_tiroglubolina, $acs_antireceptores_tsh, $acs_antiperoxidasa, $velocidad_sedimentacion_globular,
        $proteina_c_reactiva, $vdrl, $fta_abs, $ppd, $elisa_vih_1_y_2, $acs_toxoplasmosis_igg_igm, $factor_reumatoide, $acs_anti_ccp,
        $antigeno_hla_b27, $acs_antinucleares, $acs_anticardiolipina, $acs_p_ancasy_c_ancas, $otros_laboratorio,
        $calculo_lio_iol_master, $calculo_lio_inmersion, $topografia_corneal_opcion, $microscopia_especular, $paquimetria,
        $ultrabiomicroscopia, $fotografia_segmento_anterior, $angiografia_opcion, $oct_macular, $campos_visuales_opcion,
        $oct_nervio_optico, $hrt_nervio_optico, $gdx_analisis_fibras_nerviosas, $curva_horaria_pio, $resonancia_magnetica_orbita,
        $tomografia_orbita, $autofluorescencia_infrarrojo, $ecografia_opcion, $fotografia_9_campos, $fotografia_fondo_ojo,
        $fotografia_nervio_optico, $otros_gabinete
    );

    if ($stmt->execute()) {
        header("Location: examenes_lab_gabinete.php?success=1");
    } else {
        header("Location: examenes_lab_gabinete.php?error=" . urlencode("Error al insertar los datos: " . $stmt->error));
    }
    $stmt->close();
} catch (Exception $e) {
    header("Location: examenes_lab_gabinete.php?error=" . urlencode("Error al insertar los datos: " . $e->getMessage()));
}

    // Recolección de datos
    $biometria_hematica = isset($_POST['biometria_hematica']) ? 1 : 0;
    $quimica_sanguinea = isset($_POST['quimica_sanguinea']) ? 1 : 0;
    $quimica_sanguinea_valores = $quimica_sanguinea ? json_encode([
        'glucosa' => $_POST['quimica_sanguinea_glucosa'] ?? null,
        'colesterol' => $_POST['quimica_sanguinea_colesterol'] ?? null,
        'trigliceridos' => $_POST['quimica_sanguinea_trigliceridos'] ?? null,
        'creatinina' => $_POST['quimica_sanguinea_creatinina'] ?? null
    ]) : null;
    $elementos = isset($_POST['elementos']) ? 1 : 0;
    $tiempos_coagulacion = isset($_POST['tiempos_coagulacion']) ? 1 : 0;
    $hemoglobina_glucosilada = isset($_POST['hemoglobina_glucosilada']) ? 1 : 0;
    $examen_general_orina = isset($_POST['examen_general_orina']) ? 1 : 0;
    $electrocardiograma = isset($_POST['electrocardiograma']) ? 1 : 0;
    $pruebas_funcion_hepatica = isset($_POST['pruebas_funcion_hepatica']) ? 1 : 0;
    $antigeno_sars_cov_2 = isset($_POST['antigeno_sars_cov_2']) ? 1 : 0;
    $pcr_sars_cov_2 = isset($_POST['pcr_sars_cov_2']) ? 1 : 0;
    $otros_laboratorio = $_POST['otros_laboratorio'] ?? '';
    $electroitos_sericos = isset($_POST['electroitos_sericos']) ? 1 : 0;
    $pruebas_funcion_tiroidea = isset($_POST['pruebas_funcion_tiroidea']) ? 1 : 0;
    $acs_anti_tiroglubolina = isset($_POST['acs_anti_tiroglubolina']) ? 1 : 0;
    $acs_antireceptores_tsh = isset($_POST['acs_antireceptores_tsh']) ? 1 : 0;
    $acs_antiperoxidasa = isset($_POST['acs_antiperoxidasa']) ? 1 : 0;
    $velocidad_sedimentacion_globular = isset($_POST['velocidad_sedimentacion_globular']) ? 1 : 0;
    $proteina_c_reactiva = isset($_POST['proteina_c_reactiva']) ? 1 : 0;
    $vdrl = isset($_POST['vdrl']) ? 1 : 0;
    $fta_abs = isset($_POST['fta_abs']) ? 1 : 0;
    $ppd = isset($_POST['ppd']) ? 1 : 0;
    $elisa_vih_1_y_2 = isset($_POST['elisa_vih_1_y_2']) ? 1 : 0;
    $acs_toxoplasmosis_igg_igm = isset($_POST['acs_toxoplasmosis_igg_igm']) ? 1 : 0;
    $factor_reumatoide = isset($_POST['factor_reumatoide']) ? 1 : 0;
    $acs_anti_ccp = isset($_POST['acs_anti_ccp']) ? 1 : 0;
    $antigeno_hla_b27 = isset($_POST['antigeno_hla_b27']) ? 1 : 0;
    $acs_antinucleares = isset($_POST['acs_antinucleares']) ? 1 : 0;
    $acs_anticardiolipina = isset($_POST['acs_anticardiolipina']) ? 1 : 0;
    $acs_p_ancasy_c_ancas = isset($_POST['acs_p_ancasy_c_ancas']) ? 1 : 0;
    $calculo_lio_iol_master = isset($_POST['calculo_lio_iol_master']) ? 1 : 0;
    $calculo_lio_inmersion = isset($_POST['calculo_lio_inmersion']) ? 1 : 0;
    $topografia_corneal_opcion = $_POST['topografia_corneal_opcion'] ?? '';
    $microscopia_especular = isset($_POST['microscopia_especular']) ? 1 : 0;
    $paquimetria = isset($_POST['paquimetria']) ? 1 : 0;
    $ultrabiomicroscopia = isset($_POST['ultrabiomicroscopia']) ? 1 : 0;
    $fotografia_segmento_anterior = isset($_POST['fotografia_segmento_anterior']) ? 1 : 0;
    $angiografia_opcion = $_POST['angiografia_opcion'] ?? '';
    $oct_macular = isset($_POST['oct_macular']) ? 1 : 0;
    $campos_visuales_opcion = $_POST['campos_visuales_opcion'] ?? '';
    $oct_nervio_optico = isset($_POST['oct_nervio_optico']) ? 1 : 0;
    $hrt_nervio_optico = isset($_POST['hrt_nervio_optico']) ? 1 : 0;
    $gdx_analisis_fibras_nerviosas = isset($_POST['gdx_analisis_fibras_nerviosas']) ? 1 : 0;
    $curva_horaria_pio = isset($_POST['curva_horaria_pio']) ? 1 : 0;
    $otros_gabinete = $_POST['otros_gabinete'] ?? '';

    $stmt = $conexion->prepare("
        INSERT INTO ocular_examenes_lab_gabinete (
            id_atencion, Id_exp, biometria_hematica, quimica_sanguinea, quimica_sanguinea_valores, elementos,
            tiempos_coagulacion, hemoglobina_glucosilada, examen_general_orina, electrocardiograma,
            pruebas_funcion_hepatica, antigeno_sars_cov_2, pcr_sars_cov_2, otros_laboratorio,
            electroitos_sericos, pruebas_funcion_tiroidea, acs_anti_tiroglubolina, acs_antireceptores_tsh,
            acs_antiperoxidasa, velocidad_sedimentacion_globular, proteina_c_reactiva, vdrl, fta_abs, ppd,
            elisa_vih_1_y_2, acs_toxoplasmosis_igg_igm, factor_reumatoide, acs_anti_ccp, antigeno_hla_b27,
            acs_antinucleares, acs_anticardiolipina, acs_p_ancasy_c_ancas, calculo_lio_iol_master,
            calculo_lio_inmersion, topografia_corneal_opcion, microscopia_especular, paquimetria,
            ultrabiomicroscopia, fotografia_segmento_anterior, angiografia_opcion, oct_macular,
            campos_visuales_opcion, oct_nervio_optico, hrt_nervio_optico, gdx_analisis_fibras_nerviosas,
            curva_horaria_pio, otros_gabinete
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

    $stmt->bind_param(
        "iisiiiiiiiiissiiiiiiiiiiiiiiiiissiiissiiiiis",
        $id_atencion,
        $id_exp,
        $biometria_hematica,
        $quimica_sanguinea,
        $quimica_sanguinea_valores,
        $elementos,
        $tiempos_coagulacion,
        $hemoglobina_glucosilada,
        $examen_general_orina,
        $electrocardiograma,
        $pruebas_funcion_hepatica,
        $antigeno_sars_cov_2,
        $pcr_sars_cov_2,
        $otros_laboratorio,
        $electroitos_sericos,
        $pruebas_funcion_tiroidea,
        $acs_anti_tiroglubolina,
        $acs_antireceptores_tsh,
        $acs_antiperoxidasa,
        $velocidad_sedimentacion_globular,
        $proteina_c_reactiva,
        $vdrl,
        $fta_abs,
        $ppd,
        $elisa_vih_1_y_2,
        $acs_toxoplasmosis_igg_igm,
        $factor_reumatoide,
        $acs_anti_ccp,
        $antigeno_hla_b27,
        $acs_antinucleares,
        $acs_anticardiolipina,
        $acs_p_ancasy_c_ancas,
        $calculo_lio_iol_master,
        $calculo_lio_inmersion,
        $topografia_corneal_opcion,
        $microscopia_especular,
        $paquimetria,
        $ultrabiomicroscopia,
        $fotografia_segmento_anterior,
        $angiografia_opcion,
        $oct_macular,
        $campos_visuales_opcion,
        $oct_nervio_optico,
        $hrt_nervio_optico,
        $gdx_analisis_fibras_nerviosas,
        $curva_horaria_pio,
        $otros_gabinete
    );

    if ($stmt->execute()) {
        echo '<script>alert("Exámenes registrados correctamente."); window.location.href="examenes_lab_gabinete.php";</script>';
    } else {
        echo '<script>alert("Error al registrar los exámenes: ' . $stmt->error . '"); window.location.href="examenes_lab_gabinete.php";</script>';
    }

    $stmt->close();
    $conexion->close();
} else {
    header("Location: examenes_lab_gabinete.php");
    exit();
}
?>
