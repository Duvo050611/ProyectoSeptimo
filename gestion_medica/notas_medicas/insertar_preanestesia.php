<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['login']['id_usua']) || !isset($_SESSION['hospital'])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize error array
    $errors = [];

    // Required fields
    $required_fields = [
        'Id_exp' => 'ID de expediente',
        'id_usua' => 'ID de usuario',
        'id_atencion' => 'ID de atención'
    ];

    // Antecedentes fields
    $antecedentes = [
        'tabaquismo', 'asma', 'alcoholismo', 'alergias', 'toxicomanias', 'diabetes',
        'hepatopatias', 'enf_tiroideas', 'neumopatias', 'hipertension', 'nefropatias',
        'cancer', 'transfusiones', 'artritis', 'cardiopatias'
    ];

    // Numeric fields with ranges
    $numeric_fields = [
        'peso' => ['min' => 0, 'max' => 500],
        'talla' => ['min' => 0, 'max' => 3],
        'ta_sistolica' => ['min' => 0, 'max' => 300],
        'ta_diastolica' => ['min' => 0, 'max' => 200],
        'fc' => ['min' => 0, 'max' => 300],
        'fr' => ['min' => 0, 'max' => 100],
        'temperatura' => ['min' => 0, 'max' => 45],
        'hb' => ['min' => 0, 'max' => 50],
        'hto' => ['min' => 0, 'max' => 100],
        'tp' => ['min' => 0, 'max' => 100],
        'tpt' => ['min' => 0, 'max' => 100],
        'glucosa' => ['min' => 0, 'max' => 1000],
        'urea' => ['min' => 0, 'max' => 1000],
        'creatinina' => ['min' => 0, 'max' => 100]
    ];

    // Validate required fields
    $data = [];
    foreach ($required_fields as $field => $label) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $errors[] = "El campo $label es obligatorio.";
        } else {
            $data[$field] = trim($_POST[$field]);
        }
    }

    // Fetch doctor names (optional)
    $anestesiologo_id = isset($_POST['anestesiologo_id']) && !empty(trim($_POST['anestesiologo_id'])) ? trim($_POST['anestesiologo_id']) : null;
    $cirujano_id = isset($_POST['cirujano_id']) && !empty(trim($_POST['cirujano_id'])) ? trim($_POST['cirujano_id']) : null;

    if ($anestesiologo_id) {
        $sql_anest = "SELECT CONCAT(nombre, ' ', papell, ' ', sapell) AS full_name FROM reg_usuarios WHERE id_usua = ? AND u_activo = 'SI' AND cargp = 'Anestesiólogo'";
        $stmt_anest = $conexion->prepare($sql_anest);
        $stmt_anest->bind_param("i", $anestesiologo_id);
        $stmt_anest->execute();
        $result_anest = $stmt_anest->get_result();
        if ($result_anest->num_rows > 0) {
            $data['anestesiologo'] = $result_anest->fetch_assoc()['full_name'];
        } else {
            $errors[] = "Anestesiólogo no encontrado o inválido.";
        }
        $stmt_anest->close();
    } else {
        $data['anestesiologo'] = null;
    }

    if ($cirujano_id) {
        $sql_cir = "SELECT CONCAT(nombre, ' ', papell, ' ', sapell) AS full_name FROM reg_usuarios WHERE id_usua = ? AND u_activo = 'SI' AND cargp = 'Cirujano'";
        $stmt_cir = $conexion->prepare($sql_cir);
        $stmt_cir->bind_param("i", $cirujano_id);
        $stmt_cir->execute();
        $result_cir = $stmt_cir->get_result();
        if ($result_cir->num_rows > 0) {
            $data['cirujano'] = $result_cir->fetch_assoc()['full_name'];
        } else {
            $errors[] = "Cirujano no encontrado o inválido.";
        }
        $stmt_cir->close();
    } else {
        $data['cirujano'] = null;
    }

    // Validate antecedentes
    foreach ($antecedentes as $antecedente) {
        $data[$antecedente] = isset($_POST[$antecedente]) && in_array($_POST[$antecedente], ['No', 'Sí']) ? $_POST[$antecedente] : 'No';
        $data[$antecedente . '_detalle'] = isset($_POST[$antecedente . '_detalle']) && $_POST[$antecedente] === 'Sí' ? trim($_POST[$antecedente . '_detalle']) : null;
    }

    // Validate optional text fields
    $optional_text_fields = [
        'diagnostico_preoperatorio', 'cirugia_programada', 'medicamentos_actuales', 'anestesias_previas',
        'otros_antecedentes', 'padecimiento_actual', 'cabeza_cuello', 'via_aerea', 'cardiopulmonar',
        'abdomen', 'columna', 'extremidades', 'otros_exploracion', 'otros_laboratorio', 'gabinete',
        'plan_anestesico', 'indicaciones_preanestesicas'
    ];
    foreach ($optional_text_fields as $field) {
        $data[$field] = isset($_POST[$field]) ? trim($_POST[$field]) : null;
    }

    // Validate numeric fields
    foreach ($numeric_fields as $field => $range) {
        if (isset($_POST[$field]) && $_POST[$field] !== '') {
            $value = floatval($_POST[$field]);
            if ($value < $range['min'] || $value > $range['max']) {
                $errors[] = "El campo $field debe estar entre {$range['min']} y {$range['max']}.";
            } else {
                $data[$field] = $value;
            }
        } else {
            $data[$field] = null;
        }
    }

    // Validate tipo_rh
    $valid_rh = ['', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    $data['tipo_rh'] = isset($_POST['tipo_rh']) && in_array($_POST['tipo_rh'], $valid_rh) ? $_POST['tipo_rh'] : null;
    if (isset($_POST['tipo_rh']) && !in_array($_POST['tipo_rh'], $valid_rh)) {
        $errors[] = "El tipo y Rh deben ser A+, A-, B+, B-, AB+, AB-, O+, O-, o vacío.";
    }

    // Validate and capture urgencia
    $valid_urgencia = ['Urgencia', 'Electiva', ''];
    $data['urgencia'] = isset($_POST['urgencia']) && in_array($_POST['urgencia'], ['Urgencia', 'Electiva']) ? $_POST['urgencia'] : null;
    if (isset($_POST['urgencia']) && !in_array($_POST['urgencia'], $valid_urgencia)) {
        $errors[] = "El tipo de cirugía debe ser 'Urgencia', 'Electiva', o vacío.";
    }

    // Validate and capture edo_conciencia
    $valid_edo_conciencia = ['Consciente', 'Inconsciente', 'Desorientado', ''];
    $data['edo_conciencia'] = isset($_POST['edo_conciencia']) && in_array($_POST['edo_conciencia'], ['Consciente', 'Inconsciente', 'Desorientado']) ? $_POST['edo_conciencia'] : null;
    if (isset($_POST['edo_conciencia']) && !in_array($_POST['edo_conciencia'], $valid_edo_conciencia)) {
        $errors[] = "El estado de conciencia debe ser 'Consciente', 'Inconsciente', 'Desorientado', o vacío.";
    }

    // Validate and capture asa
    $valid_asa = ['I', 'II', 'III', 'IV', 'V', ''];
    $data['asa'] = isset($_POST['asa']) && in_array($_POST['asa'], ['I', 'II', 'III', 'IV', 'V']) ? $_POST['asa'] : null;
    if (isset($_POST['asa']) && !in_array($_POST['asa'], $valid_asa)) {
        $errors[] = "El estado físico ASA debe ser I, II, III, IV, V, o vacío.";
    }

    // If no errors, proceed with insertion
    if (empty($errors)) {
        $columns = [
            'Id_exp', 'id_usua', 'id_atencion', 'anestesiologo', 'urgencia', 'cirujano',
            'diagnostico_preoperatorio', 'cirugia_programada', 'tabaquismo', 'tabaquismo_detalle',
            'asma', 'asma_detalle', 'alcoholismo', 'alcoholismo_detalle', 'alergias', 'alergias_detalle',
            'toxicomanias', 'toxicomanias_detalle', 'diabetes', 'diabetes_detalle', 'hepatopatias',
            'hepatopatias_detalle', 'enf_tiroideas', 'enf_tiroideas_detalle', 'neumopatias',
            'neumopatias_detalle', 'hipertension', 'hipertension_detalle', 'nefropatias',
            'nefropatias_detalle', 'cancer', 'cancer_detalle', 'transfusiones', 'transfusiones_detalle',
            'artritis', 'artritis_detalle', 'cardiopatias', 'cardiopatias_detalle', 'medicamentos_actuales',
            'anestesias_previas', 'otros_antecedentes', 'padecimiento_actual', 'peso', 'talla',
            'ta_sistolica', 'ta_diastolica', 'fc', 'fr', 'temperatura', 'edo_conciencia', 'cabeza_cuello',
            'via_aerea', 'cardiopulmonar', 'abdomen', 'columna', 'extremidades', 'otros_exploracion',
            'hb', 'hto', 'tp', 'tpt', 'tipo_rh', 'glucosa', 'urea', 'creatinina', 'otros_laboratorio',
            'gabinete', 'asa', 'plan_anestesico', 'indicaciones_preanestesicas'
        ];

        $types = '';
        $values = [];
        foreach ($columns as $col) {
            if (in_array($col, ['Id_exp', 'id_usua', 'id_atencion', 'ta_sistolica', 'ta_diastolica'])) {
                $types .= 'i';
                $values[] = isset($data[$col]) ? (int)$data[$col] : null;
            } elseif (in_array($col, ['peso', 'talla', 'fc', 'fr', 'temperatura', 'hb', 'hto', 'tp', 'tpt', 'glucosa', 'urea', 'creatinina'])) {
                $types .= 'd';
                $values[] = isset($data[$col]) && $data[$col] !== '' ? (float)$data[$col] : null;
            } else {
                $types .= 's';
                $values[] = isset($data[$col]) ? $data[$col] : null;
            }
        }

        $sql = "INSERT INTO preanesthetic_evaluation (" . implode(', ', $columns) . ") VALUES (" . implode(', ', array_fill(0, count($columns), '?')) . ")";
        $stmt = $conexion->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed: " . $conexion->error);
            $_SESSION['message'] = "Error al preparar la consulta: " . $conexion->error;
            $_SESSION['message_type'] = "danger";
            header("Location: reg_preanestesico.php");
            exit;
        }

        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Valoración preanestésica guardada exitosamente.";
            $_SESSION['message_type'] = "success";
            header("Location: reg_preanestesico.php");
        } else {
            error_log("Execute failed: " . $stmt->error);
            $_SESSION['message'] = "Error al guardar la valoración: " . $stmt->error;
            $_SESSION['message_type'] = "danger";
            header("Location: reg_preanestesico.php");
        }
        $stmt->close();
        $conexion->close();
    } else {
        $_SESSION['message'] = implode("<br>", $errors);
        $_SESSION['message_type'] = "danger";
        header("Location: reg_preanestesico.php");
    }
} else {
    $_SESSION['message'] = "Método de solicitud no válido.";
    $_SESSION['message_type'] = "danger";
    header("Location: reg_preanestesico.php");
}
?>