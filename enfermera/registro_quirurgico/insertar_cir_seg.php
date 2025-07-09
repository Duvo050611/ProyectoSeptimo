<?php
session_start();
include '../../conexionbd.php';

$id_exp = isset($_POST['id_exp']) && $_POST['id_exp'] !== '' ? intval($_POST['id_exp']) : null;
$id_usua = isset($_POST['id_usua']) && $_POST['id_usua'] !== '' ? intval($_POST['id_usua']) : null;
$id_atencion = isset($_POST['id_atencion']) && $_POST['id_atencion'] !== '' ? intval($_POST['id_atencion']) : null;

function getCheckbox($key) {
    return isset($_POST[$key]) ? 1 : 0;
}

// Variables previas
$confirmacion_identidad = getCheckbox('confirmacion_identidad');
$sitio_marcado_si = in_array("Sí", $_POST['sitio_marcado'] ?? []) ? 1 : 0;
$sitio_marcado_np = in_array("No procede", $_POST['sitio_marcado'] ?? []) ? 1 : 0;
$verificacion_anestesia = getCheckbox('verificacion_anestesia');
$pulsioximetro = getCheckbox('pulsioximetro');

$alergias_no = in_array("No", $_POST['alergias'] ?? []) ? 1 : 0;
$alergias_si = in_array("Sí", $_POST['alergias'] ?? []) ? 1 : 0;

$via_aerea_no = in_array("No", $_POST['via_aerea_dificil'] ?? []) ? 1 : 0;
$via_aerea_si = in_array("Sí, y hay materiales y equipos / ayuda disponible", $_POST['via_aerea_dificil'] ?? []) ? 1 : 0;

$riesgo_hemo_no = in_array("No", $_POST['riesgo_hemorragia'] ?? []) ? 1 : 0;
$riesgo_hemo_si = in_array("Sí, y se ha previsto la disponibilidad de líquidos y dos vías IV o centrales", $_POST['riesgo_hemorragia'] ?? []) ? 1 : 0;

$miembros_presentados = getCheckbox('miembros_presentados');
$confirmacion_identidad_equipo = getCheckbox('confirmacion_identidad_equipo');
$profilaxis_antibiotica_si = getCheckbox('profilaxis_antibiotica_si');
$profilaxis_antibiotica_np = getCheckbox('profilaxis_antibiotica_np');

$pasos_criticos = getCheckbox('pasos_criticos');
$duracion_operacion = getCheckbox('duracion_operacion');
$perdida_sangre = getCheckbox('perdida_sangre');

$problemas_paciente = getCheckbox('problemas_paciente');
$esterilidad_confirmada = getCheckbox('esterilidad_confirmada');
$problemas_instrumental = getCheckbox('problemas_instrumental');

$imagenes_visibles_si = getCheckbox('imagenes_visibles_si');
$imagenes_visibles_np = getCheckbox('imagenes_visibles_np');

$nombre_procedimiento = getCheckbox('nombre_procedimiento');
$recuento_instrumental = getCheckbox('recuento_instrumental');
$etiquetado_muestras = getCheckbox('etiquetado_muestras');
$problemas_instrumental_final = getCheckbox('problemas_instrumental_final');
$aspectos_recuperacion = getCheckbox('aspectos_recuperacion');
// echo "ID_EXP: $id_exp<br>";
// echo "ID_ATENCION: $id_atencion<br>";
// echo "ID_USUA: $id_usua<br>";
// exit(); // Detén para inspeccionar antes de ejecutar el insert

$stmt = $conexion->prepare("
    INSERT INTO dat_cir_seg (
        id_exp, id_atencion, id_usua,
        confirmacion_identidad, sitio_marcado_si, sitio_marcado_np,
        verificacion_anestesia, pulsioximetro,
        alergias_no, alergias_si,
        via_aerea_no, via_aerea_si,
        riesgo_hemo_no, riesgo_hemo_si,
        miembros_presentados, confirmacion_identidad_equipo,
        profilaxis_antibiotica_si, profilaxis_antibiotica_np,
        pasos_criticos, duracion_operacion, perdida_sangre,
        problemas_paciente, esterilidad_confirmada, problemas_instrumental,
        imagenes_visibles_si, imagenes_visibles_np,
        nombre_procedimiento, recuento_instrumental,
        etiquetado_muestras, problemas_instrumental_final, aspectos_recuperacion
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? 
		)");

$stmt->bind_param(
    'iiiiiiiiiiiiiiiiiiiiiiiiiiiiiii',
    $id_exp, $id_atencion, $id_usua,
    $confirmacion_identidad, $sitio_marcado_si, $sitio_marcado_np,
    $verificacion_anestesia, $pulsioximetro,
    $alergias_no, $alergias_si,
    $via_aerea_no, $via_aerea_si,
    $riesgo_hemo_no, $riesgo_hemo_si,
    $miembros_presentados, $confirmacion_identidad_equipo,
    $profilaxis_antibiotica_si, $profilaxis_antibiotica_np,
    $pasos_criticos, $duracion_operacion, $perdida_sangre,
    $problemas_paciente, $esterilidad_confirmada, $problemas_instrumental,
    $imagenes_visibles_si, $imagenes_visibles_np,
    $nombre_procedimiento, $recuento_instrumental,
    $etiquetado_muestras, $problemas_instrumental_final, $aspectos_recuperacion
);

if ($stmt->execute()) {
    header("Location: enf_cirugia_segura.php");
} else {
    echo "Error al guardar: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
