<?php
session_start();
include "../../conexionbd.php"; // Ensure this file sets mysqli_set_charset($conexion, 'utf8mb4')

// Redirect if user not logged in or hospital session not set
if (!isset($_SESSION['login']['id_usua']) || !isset($_SESSION['hospital'])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize error array
    $errors = [];
    $data = [];

    // --- Sanitize and Collect POST Data ---
    // IDs and Foreign Keys
    $data['id_atencion'] = filter_var($_POST['id_atencion'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $data['Id_exp'] = filter_var($_POST['Id_exp'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $data['id_usua'] = filter_var($_POST['id_usua'] ?? '', FILTER_SANITIZE_NUMBER_INT);

    // General Information
    $data['ojo_operado'] = filter_var($_POST['ojo_operado'] ?? '', FILTER_SANITIZE_STRING);
    $data['tecnica_anestesica'] = filter_var($_POST['tecnica_anestesica'] ?? '', FILTER_SANITIZE_STRING);
    $data['sangre_liquidos'] = filter_var($_POST['sangre_liquidos'] ?? '', FILTER_SANITIZE_STRING);
    $data['incidentes'] = filter_var($_POST['incidentes'] ?? '', FILTER_SANITIZE_STRING);
    $data['detalle_incidentes'] = filter_var($_POST['detalle_incidentes'] ?? '', FILTER_SANITIZE_STRING);
    $data['plan_manejo'] = filter_var($_POST['plan_manejo'] ?? '', FILTER_SANITIZE_STRING);

    // Vital Signs at Admission
    $data['ta_ingreso'] = filter_var($_POST['ta_ingreso'] ?? '', FILTER_SANITIZE_STRING);
    $data['fc_ingreso'] = filter_var($_POST['fc_ingreso'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['fr_ingreso'] = filter_var($_POST['fr_ingreso'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['sato2_ingreso'] = filter_var($_POST['sato2_ingreso'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['pio_ingreso'] = filter_var($_POST['pio_ingreso'] ?? '', FILTER_SANITIZE_STRING);

    // Aldrete Scale
    $aldrete_fields = [
        'actividad_ingreso', 'respiracion_ingreso', 'circulacion_ingreso', 'conciencia_ingreso', 'saturacion_ingreso',
        'actividad_alta', 'respiracion_alta', 'circulacion_alta', 'conciencia_alta', 'saturacion_alta'
    ];
    foreach ($aldrete_fields as $field) {
        $value = filter_var($_POST[$field] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 2]]) ?: 0;
        $data[$field] = $value;
    }

    // Aldrete Time Inputs
    $time_fields = [
        'actividad_hora_ingreso', 'respiracion_hora_ingreso', 'circulacion_hora_ingreso', 'conciencia_hora_ingreso', 'saturacion_hora_ingreso',
        'actividad_hora_alta', 'respiracion_hora_alta', 'circulacion_hora_alta', 'conciencia_hora_alta', 'saturacion_hora_alta',
        'hora_ingreso', 'hora_alta'
    ];
    foreach ($time_fields as $field) {
        $time_str = $_POST[$field] ?? '';
        $data[$field] = !empty($time_str) ? date('H:i:s', strtotime($time_str)) : null;
    }

    // Aldrete Total Scores
    $data['total_ingreso'] = filter_var($_POST['total_ingreso'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: 0;
    $data['total_alta'] = filter_var($_POST['total_alta'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: 0;

    // Post-Anesthetic Evolution
    $data['evolucion_alta'] = filter_var($_POST['evolucion_alta'] ?? '', FILTER_SANITIZE_STRING);

    // Vital Signs at Discharge
    $data['ta_alta'] = filter_var($_POST['ta_alta'] ?? '', FILTER_SANITIZE_STRING);
    $data['fc_alta'] = filter_var($_POST['fc_alta'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['fr_alta'] = filter_var($_POST['fr_alta'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['sato2_alta'] = filter_var($_POST['sato2_alta'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['pio_alta'] = filter_var($_POST['pio_alta'] ?? '', FILTER_SANITIZE_STRING);

    // Pain Control
    $data['control_dolor'] = filter_var($_POST['control_dolor'] ?? '', FILTER_SANITIZE_STRING);

    // Anesthesiology Discharge
    $data['horas_post_anestesia'] = filter_var($_POST['horas_post_anestesia'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['ta_final'] = filter_var($_POST['ta_final'] ?? '', FILTER_SANITIZE_STRING);
    $data['pulso_final'] = filter_var($_POST['pulso_final'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['resp_final'] = filter_var($_POST['resp_final'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['estado_conciencia'] = filter_var($_POST['estado_conciencia'] ?? '', FILTER_SANITIZE_STRING);

    // Symptoms Checkboxes
    $checkboxes = ['nauseas', 'vomito', 'cefalea', 'diuresis', 'dolor_ocular', 'vision_borrosa'];
    foreach ($checkboxes as $checkbox) {
        $data[$checkbox] = isset($_POST[$checkbox]) && $_POST[$checkbox] === 'Sí' ? 1 : 0;
    }

    // Final Evolution and Ambulation
    $data['evolucion_final'] = filter_var($_POST['evolucion_final'] ?? '', FILTER_SANITIZE_STRING);
    $data['deambulacion'] = filter_var($_POST['deambulacion'] ?? '', FILTER_SANITIZE_STRING);
    $data['indicaciones_alta'] = filter_var($_POST['indicaciones_alta'] ?? '', FILTER_SANITIZE_STRING);

    // Date Field (not in POST, default to NOW())
    $data['fecha_registro'] = date('Y-m-d H:i:s'); // Current timestamp

    // --- Validate Required Fields ---
    if (empty($data['id_atencion'])) $errors[] = "El ID de atención es obligatorio.";
    if (empty($data['Id_exp'])) $errors[] = "El ID de expediente es obligatorio.";
    if (empty($data['id_usua'])) $errors[] = "El ID de usuario es obligatorio.";
    if (empty($data['ojo_operado'])) $errors[] = "El ojo operado es obligatorio.";
    if (empty($data['tecnica_anestesica'])) $errors[] = "La técnica anestésica es obligatoria.";
    if (empty($data['plan_manejo'])) $errors[] = "El plan de manejo es obligatorio.";
    if (empty($data['ta_ingreso'])) $errors[] = "La T.A. de ingreso es obligatoria.";
    if (!is_numeric($data['fc_ingreso'])) $errors[] = "La F.C. de ingreso es obligatoria.";
    if (!is_numeric($data['fr_ingreso'])) $errors[] = "La F.R. de ingreso es obligatoria.";
    if (!is_numeric($data['sato2_ingreso'])) $errors[] = "La SatO2 de ingreso es obligatoria.";
    if (empty($data['pio_ingreso'])) $errors[] = "La presión intraocular de ingreso es obligatoria.";
    if (empty($data['evolucion_alta'])) $errors[] = "La evolución y alta postanestésica es obligatoria.";
    if (empty($data['ta_alta'])) $errors[] = "La T.A. de alta es obligatoria.";
    if (!is_numeric($data['fc_alta'])) $errors[] = "La F.C. de alta es obligatoria.";
    if (!is_numeric($data['fr_alta'])) $errors[] = "La F.R. de alta es obligatoria.";
    if (!is_numeric($data['sato2_alta'])) $errors[] = "La SatO2 de alta es obligatoria.";
    if (empty($data['pio_alta'])) $errors[] = "La presión intraocular de alta es obligatoria.";
    if (empty($data['control_dolor'])) $errors[] = "El control de dolor es obligatorio.";
    if (!is_numeric($data['horas_post_anestesia'])) $errors[] = "Las horas post anestesia son obligatorias.";
    if (empty($data['ta_final'])) $errors[] = "La T.A. final es obligatoria.";
    if (!is_numeric($data['pulso_final'])) $errors[] = "El pulso final es obligatorio.";
    if (!is_numeric($data['resp_final'])) $errors[] = "La respiración final es obligatoria.";
    if (empty($data['estado_conciencia'])) $errors[] = "El estado de conciencia es obligatorio.";
    if (empty($data['evolucion_final'])) $errors[] = "La evolución final es obligatoria.";
    if (empty($data['deambulacion'])) $errors[] = "La deambulación es obligatoria.";
    if (empty($data['indicaciones_alta'])) $errors[] = "Las indicaciones de alta son obligatorias.";

    // Validate incidentes
    if (!in_array($data['incidentes'], ['Sí', 'No'])) {
        $errors[] = "Seleccione un valor válido para incidentes: Sí o No.";
    } elseif ($data['incidentes'] === 'Sí' && empty($data['detalle_incidentes'])) {
        $errors[] = "El detalle de incidentes es obligatorio si hay incidentes.";
    }

    // Validate Aldrete fields
    foreach ($aldrete_fields as $field) {
        if (!is_numeric($data[$field])) {
            $errors[] = "El campo '{$field}' del score Aldrete debe ser 0, 1 o 2.";
        }
    }

    // Validate time fields
    foreach ($time_fields as $field) {
        if (empty($data[$field])) {
            $errors[] = "El campo de hora '{$field}' es obligatorio.";
        }
    }

    // --- Insert Data into Database ---
    if (empty($errors)) {
        // Define table columns (47 from POST + 1 fecha_registro)
        $columns = [
            'id_atencion', 'Id_exp', 'id_usua', 'ojo_operado', 'tecnica_anestesica', 'sangre_liquidos',
            'incidentes', 'detalle_incidentes', 'plan_manejo', 'ta_ingreso', 'fc_ingreso', 'fr_ingreso', 'sato2_ingreso',
            'pio_ingreso', 'actividad_ingreso', 'actividad_hora_ingreso', 'actividad_alta', 'actividad_hora_alta',
            'respiracion_ingreso', 'respiracion_hora_ingreso', 'respiracion_alta', 'respiracion_hora_alta',
            'circulacion_ingreso', 'circulacion_hora_ingreso', 'circulacion_alta', 'circulacion_hora_alta',
            'conciencia_ingreso', 'conciencia_hora_ingreso', 'conciencia_alta', 'conciencia_hora_alta',
            'saturacion_ingreso', 'saturacion_hora_ingreso', 'saturacion_alta', 'saturacion_hora_alta',
            'total_ingreso', 'total_alta', 'hora_ingreso', 'hora_alta', 'evolucion_alta', 'ta_alta', 'fc_alta',
            'fr_alta', 'sato2_alta', 'pio_alta', 'control_dolor', 'horas_post_anestesia', 'ta_final', 'pulso_final',
            'resp_final', 'estado_conciencia', 'nauseas', 'vomito', 'cefalea', 'diuresis', 'dolor_ocular',
            'vision_borrosa', 'evolucion_final', 'deambulacion', 'indicaciones_alta', 'fecha_registro'
        ];

        // Log raw POST data for debugging
        file_put_contents('post_data.log', print_r($_POST, true));

        // Log incidentes for debugging encoding
        $incidentes_bytes = bin2hex($data['incidentes']);
        file_put_contents('incidentes_bytes.log', $incidentes_bytes . "\n", FILE_APPEND);
        file_put_contents('incidentes.log', $data['incidentes'] . "\n", FILE_APPEND);

        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $sql = "INSERT INTO nota_post_anestesia_oftalmologia (" . implode(', ', $columns) . ") VALUES ($placeholders)";

        $stmt = $conexion->prepare($sql);
        if ($stmt === false) {
            error_log("Error preparing query: " . $conexion->error, 3, 'sql_error.log');
            $_SESSION['message'] = "Error al preparar la consulta: " . $conexion->error;
            $_SESSION['message_type'] = "danger";
            header("Location: reg_post_anestesia.php");
            exit;
        }

        // Prepare values and types for bind_param
        $values = [];
        $types = '';
        foreach ($columns as $col) {
            $val = $data[$col];
            $values[] = $val;
            if (is_int($val) || ($col === 'id_atencion' && is_numeric($val)) || ($col === 'Id_exp' && is_numeric($val)) || ($col === 'id_usua' && is_numeric($val))) {
                $types .= 'i';
            } elseif (in_array($col, $checkboxes)) {
                $types .= 'i'; // Checkboxes are stored as 0 or 1
            } elseif (is_null($val)) {
                $types .= 's'; // NULL values are treated as strings
            } else {
                $types .= 's';
            }
        }

        // Bind parameters
        $bind_params = array_merge([$types], $values);
        call_user_func_array([$stmt, 'bind_param'], refValues($bind_params));

        // Execute query
        if ($stmt->execute()) {
            $_SESSION['message'] = "Nota post anestésica guardada exitosamente.";
            $_SESSION['message_type'] = "success";
            header("Location: reg_post_anestesia.php");
        } else {
            error_log("Error executing query: " . $stmt->error, 3, 'sql_error.log');
            $_SESSION['message'] = "Error al guardar la nota: " . $stmt->error;
            $_SESSION['message_type'] = "danger";
            header("Location: reg_post_anestesia.php");
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = implode("<br>", $errors);
        $_SESSION['message_type'] = "danger";
        header("Location: reg_post_anestesia.php");
    }
} else {
    $_SESSION['message'] = "Método de solicitud no válido.";
    $_SESSION['message_type'] = "danger";
    header("Location: reg_post_anestesia.php");
}

// Helper function for bind_param
function refValues($arr) {
    if (strnatcmp(phpversion(), '5.3') >= 0) {
        $refs = [];
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }
    return $arr;
}

$conexion->close();
?>