<?php
session_start();
include "../../conexionbd.php"; // Asegúrate de que esta ruta sea correcta para tu configuración de base de datos

// Redirigir si el usuario no ha iniciado sesión o no hay un hospital en sesión
if (!isset($_SESSION['login']['id_usua']) || !isset($_SESSION['hospital'])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicializar el array de errores
    $errors = [];
    $data = []; // Array para almacenar los datos sanitizados

    // --- Recopilar y Sanitizar Datos del POST ---

    // Llaves Foráneas y IDs
    $data['id_atencion'] = filter_var($_POST['id_atencion'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $data['Id_exp'] = filter_var($_POST['Id_exp'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    // Añadido: id_usua del usuario que está registrando (obtenido del formulario)
    $data['id_usua'] = filter_var($_POST['id_usua'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $data['anestesiologo_id'] = filter_var($_POST['anestesiologo_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $data['cirujano_id'] = filter_var($_POST['cirujano_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

    // Información General
    $data['tipo_anestesia'] = filter_var($_POST['tipo_anestesia'] ?? '', FILTER_SANITIZE_STRING);
    $data['diagnostico_preoperatorio'] = filter_var($_POST['diagnostico_preoperatorio'] ?? '', FILTER_SANITIZE_STRING);
    $data['cirugia_programada'] = filter_var($_POST['cirugia_programada'] ?? '', FILTER_SANITIZE_STRING);
    $data['diagnostico_postoperatorio'] = filter_var($_POST['diagnostico_postoperatorio'] ?? '', FILTER_SANITIZE_STRING);
    $data['cirugia_realizada'] = filter_var($_POST['cirugia_realizada'] ?? '', FILTER_SANITIZE_STRING);

    // Ayudantes (si es un select múltiple, se almacena como una cadena separada por comas)
    if (isset($_POST['ayudantes_ids']) && is_array($_POST['ayudantes_ids'])) {
        $data['ayudantes_ids'] = implode(',', array_map('intval', $_POST['ayudantes_ids']));
    } else {
        $data['ayudantes_ids'] = null; // O una cadena vacía si no hay ayudantes
    }

    // Signos Vitales
    $data['ta'] = filter_var($_POST['ta'] ?? '', FILTER_SANITIZE_STRING);
    $data['fc'] = filter_var($_POST['fc'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $data['fr'] = filter_var($_POST['fr'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $data['temp'] = filter_var($_POST['temp'] ?? '', FILTER_VALIDATE_FLOAT);
    $data['spo2'] = filter_var($_POST['spo2'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $data['otros_signos'] = filter_var($_POST['otros_signos'] ?? '', FILTER_SANITIZE_STRING);
    $data['hoja_grafico'] = filter_var($_POST['hoja_grafico'] ?? '', FILTER_SANITIZE_STRING);

    // Detalles de Anestesia
    $data['revision_equipo'] = filter_var($_POST['revision_equipo'] ?? '', FILTER_SANITIZE_STRING);
    // Validar 'revision_equipo'
    if (!in_array($data['revision_equipo'], ['OK', 'No OK'])) {
        $errors[] = "Seleccione un estado válido para la revisión del equipo: OK o No OK.";
    }

    $data['o2_hora'] = filter_var($_POST['o2_hora'] ?? '', FILTER_SANITIZE_STRING);
    // Formatear la hora O2 a HH:MM:SS o NULL
    if (!empty($data['o2_hora'])) {
        $data['o2_hora'] = date('H:i:s', strtotime($data['o2_hora']));
    } else {
        $data['o2_hora'] = null;
    }

    $data['agente_inhalado'] = filter_var($_POST['agente_inhalado'] ?? '', FILTER_SANITIZE_STRING);

    // Fármacos y Dosis Total (concatenar desde arrays)
    $farmacos_data = [];
    if (isset($_POST['farmacos']) && is_array($_POST['farmacos']) &&
        isset($_POST['dosis_total']) && is_array($_POST['dosis_total'])) {
        $num_farmacos = count($_POST['farmacos']);
        for ($i = 0; $i < $num_farmacos; $i++) {
            $farmaco = filter_var($_POST['farmacos'][$i] ?? '', FILTER_SANITIZE_STRING);
            $dosis = filter_var($_POST['dosis_total'][$i] ?? '', FILTER_SANITIZE_STRING);
            if (!empty($farmaco) || !empty($dosis)) {
                $farmacos_data[] = trim($farmaco . ': ' . $dosis);
            }
        }
    }
    $data['farmacos_dosis_total'] = !empty($farmacos_data) ? implode('; ', $farmacos_data) : null;


    // Checkboxes (convertir a 1 o 0)
    $data['ecg_continua'] = isset($_POST['ecg_continua']) ? 1 : 0;
    $data['pulsoximetria'] = isset($_POST['pulsoximetria']) ? 1 : 0;
    $data['capnografia'] = isset($_POST['capnografia']) ? 1 : 0;

    // Intubación y Ventilación
    $data['intubacion'] = filter_var($_POST['intubacion'] ?? '', FILTER_SANITIZE_STRING);
    $data['incidentes'] = filter_var($_POST['incidentes'] ?? '', FILTER_SANITIZE_STRING);
    $data['canula'] = filter_var($_POST['canula'] ?? '', FILTER_SANITIZE_STRING);

    $data['dificultad_tecnica'] = filter_var($_POST['dificultad_tecnica'] ?? '', FILTER_SANITIZE_STRING);
    // Validar 'dificultad_tecnica'
    if (!in_array($data['dificultad_tecnica'], ['Sí', 'No'])) {
        $errors[] = "Seleccione un valor válido para dificultad técnica: Sí o No.";
    }

    $data['ventilacion'] = filter_var($_POST['ventilacion'] ?? '', FILTER_SANITIZE_STRING);

    // Líquidos y Balance (campos numéricos, permitir nulo si vacío)
    $data['hartmann'] = filter_var($_POST['hartmann'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['glucosa'] = filter_var($_POST['glucosa'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['nacl'] = filter_var($_POST['nacl'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['total_ingresos'] = filter_var($_POST['total_ingresos'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['diuresis'] = filter_var($_POST['diuresis'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['sangrado'] = filter_var($_POST['sangrado'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['perdidas_insensibles'] = filter_var($_POST['perdidas_insensibles'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['total_egresos'] = filter_var($_POST['total_egresos'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;
    $data['balance'] = filter_var($_POST['balance'] ?? '', FILTER_VALIDATE_INT) ?: null;

    // Puntuación Aldrete (requeridos, valores 0, 1, 2)
    $aldrete_fields = [
        'aldrete_actividad', 'aldrete_respiracion', 'aldrete_circulacion',
        'aldrete_conciencia', 'aldrete_saturacion'
    ];
    foreach ($aldrete_fields as $field) {
        $value = filter_var($_POST[$field] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 2]]);
        if ($value === false || $_POST[$field] === '') {
            $errors[] = "El campo '{$field}' del score Aldrete es obligatorio y debe ser 0, 1 o 2.";
        }
        $data[$field] = $value;
    }
    // Calcular aldrete_total si todos los componentes son válidos, si no se establece como null
    if (empty($errors)) {
        $data['aldrete_total'] = $data['aldrete_actividad'] + $data['aldrete_respiracion'] + $data['aldrete_circulacion'] +
                                 $data['aldrete_conciencia'] + $data['aldrete_saturacion'];
    } else {
        $data['aldrete_total'] = null; // Si hay errores en las partes, el total no es válido
    }


    // Anestesia Regional
    $data['anestesia_regional_tipo'] = filter_var($_POST['anestesia_regional_tipo'] ?? '', FILTER_SANITIZE_STRING);
    $data['aguja'] = filter_var($_POST['aguja'] ?? '', FILTER_SANITIZE_STRING);
    $data['nivel_puncion'] = filter_var($_POST['nivel_puncion'] ?? '', FILTER_SANITIZE_STRING);

    $data['cateter'] = filter_var($_POST['cateter'] ?? '', FILTER_SANITIZE_STRING);
    // Validar 'cateter'
    if (!empty($data['cateter']) && !in_array($data['cateter'], ['Sí', 'No'])) {
        $errors[] = "Seleccione un valor válido para catéter: Sí o No.";
    } elseif (empty($data['cateter'])) {
        $data['cateter'] = null;
    }

    $data['agentes_administrados'] = filter_var($_POST['agentes_administrados'] ?? '', FILTER_SANITIZE_STRING);

    // Tiempos (campos DATETIME-LOCAL, convertir a 'YYYY-MM-DD HH:MM:SS' o NULL)
    $datetime_fields = [
        'llega_quirofano', 'inicia_anestesia', 'inicia_cirugia',
        'termina_cirugia', 'termina_anestesia', 'pasa_recuperacion'
    ];
    foreach ($datetime_fields as $field) {
        $datetime_str = $_POST[$field] ?? '';
        if (!empty($datetime_str)) {
            // Asumiendo el formato 'YYYY-MM-DDTHH:MM' del input datetime-local
            $data[$field] = date('Y-m-d H:i:s', strtotime($datetime_str));
        } else {
            $data[$field] = null; // Permitir nulo para campos de fecha y hora vacíos
        }
    }

    $data['tiempo_anestesico'] = filter_var($_POST['tiempo_anestesico'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;

    // --- Validaciones de Campos Requeridos (básicas, ajusta según necesidad de tu negocio) ---
    if (empty($data['id_atencion'])) $errors[] = "El ID de atención es obligatorio.";
    if (empty($data['Id_exp'])) $errors[] = "El ID de expediente es obligatorio.";
    if (empty($data['id_usua']) && $data['id_usua'] !== 0) $errors[] = "El ID de usuario es obligatorio."; // Añadido
    if (empty($data['anestesiologo_id'])) $errors[] = "El Anestesiólogo es obligatorio.";
    if (empty($data['cirujano_id'])) $errors[] = "El Cirujano es obligatorio.";
    if (empty($data['tipo_anestesia'])) $errors[] = "El Tipo de Anestesia es obligatorio.";
    if (empty($data['diagnostico_preoperatorio'])) $errors[] = "El Diagnóstico Preoperatorio es obligatorio.";
    if (empty($data['cirugia_programada'])) $errors[] = "La Cirugía Programada es obligatoria.";
    if (empty($data['diagnostico_postoperatorio'])) $errors[] = "El Diagnóstico Postoperatorio es obligatorio.";
    if (empty($data['cirugia_realizada'])) $errors[] = "La Cirugía Realizada es obligatoria.";
    if (empty($data['ta'])) $errors[] = "La Tensión Arterial es obligatoria.";
    // Para campos numéricos que pueden ser 0, verificar si el valor es realmente numérico y no vacío
    if (!is_numeric($data['fc']) && !is_null($data['fc'])) $errors[] = "La Frecuencia Cardíaca es obligatoria y debe ser un número.";
    if (!is_numeric($data['fr']) && !is_null($data['fr'])) $errors[] = "La Frecuencia Respiratoria es obligatoria y debe ser un número.";
    if (!is_numeric($data['temp']) && !is_null($data['temp'])) $errors[] = "La Temperatura es obligatoria y debe ser un número.";
    if (!is_numeric($data['spo2']) && !is_null($data['spo2'])) $errors[] = "La SpO2 es obligatoria y debe ser un número.";
    if (empty($data['hoja_grafico'])) $errors[] = "La Hoja Gráfica de Monitoreo es obligatoria.";


    // Si no hay errores, proceder con la inserción
    if (empty($errors)) {
        // Definir las columnas de la tabla 'registro_anestesico' en el orden correcto
        // y los valores a insertar.
        $columns = [
            'id_atencion', 'Id_exp', 'id_usua', 'anestesiologo_id', 'cirujano_id', 'tipo_anestesia', // id_usua añadido aquí
            'diagnostico_preoperatorio', 'cirugia_programada', 'diagnostico_postoperatorio', 'cirugia_realizada',
            'ayudantes_ids', 'ta', 'fc', 'fr', 'temp', 'spo2', 'otros_signos', 'hoja_grafico',
            'revision_equipo', 'o2_hora', 'agente_inhalado', 'farmacos_dosis_total',
            'ecg_continua', 'pulsoximetria', 'capnografia', 'intubacion', 'incidentes', 'canula',
            'dificultad_tecnica', 'ventilacion', 'hartmann', 'glucosa', 'nacl', 'total_ingresos',
            'diuresis', 'sangrado', 'perdidas_insensibles', 'total_egresos', 'balance',
            'aldrete_actividad', 'aldrete_respiracion', 'aldrete_circulacion', 'aldrete_conciencia',
            'aldrete_saturacion', 'aldrete_total', 'anestesia_regional_tipo', 'aguja', 'nivel_puncion',
            'cateter', 'agentes_administrados', 'llega_quirofano', 'inicia_anestesia',
            'inicia_cirugia', 'termina_cirugia', 'termina_anestesia', 'pasa_recuperacion',
            'tiempo_anestesico'
        ];

        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $sql = "INSERT INTO registro_anestesico (" . implode(', ', $columns) . ") VALUES ({$placeholders})";

        $stmt = $conexion->prepare($sql);
        if ($stmt === false) {
            error_log("Fallo en la preparación de la consulta: " . $conexion->error);
            $_SESSION['message'] = "Error al preparar la consulta: " . $conexion->error;
            $_SESSION['message_type'] = "danger";
            header("Location: reg_anestesia.php"); // Redirigir a la misma página
            exit;
        }

        // Obtener los valores en el orden de las columnas y definir los tipos
        $values = [];
        $types = '';
        foreach ($columns as $col) {
            $val = $data[$col];
            $values[] = $val;

            // Determinar el tipo para bind_param
            if (is_int($val)) {
                $types .= 'i';
            } elseif (is_float($val)) {
                $types .= 'd';
            } elseif (is_string($val) || is_null($val) || is_bool($val)) {
                $types .= 's'; // Las cadenas y NULLs se tratan como 's', los booleanos como 's' para 0/1
            } else {
                $types .= 's'; // Fallback
            }
        }

        // Para bind_param, necesitamos referencias para PHP < 8.1
        // (La función refValues es una utilidad común para esto)
        $bind_params = array_merge([$types], $values);
        call_user_func_array([$stmt, 'bind_param'], refValues($bind_params));

        if ($stmt->execute()) {
            $_SESSION['message'] = "Registro anestésico guardado exitosamente.";
            $_SESSION['message_type'] = "success";
            header("Location: reg_anestesia.php"); // Redirigir a la misma página
        } else {
            error_log("Fallo en la ejecución: " . $stmt->error);
            $_SESSION['message'] = "Error al guardar el registro: " . $stmt->error;
            $_SESSION['message_type'] = "danger";
            header("Location: reg_anestesia.php"); // Redirigir a la misma página
        }
        $stmt->close();
        $conexion->close();
    } else {
        $_SESSION['message'] = implode("<br>", $errors);
        $_SESSION['message_type'] = "danger";
        header("Location: reg_anestesia.php"); // Redirigir a la misma página para mostrar errores
    }
} else {
    // Si la solicitud no es POST, redirigir con un mensaje de error
    $_SESSION['message'] = "Método de solicitud no válido.";
    $_SESSION['message_type'] = "danger";
    header("Location: reg_anestesia.php" );
}

// Función auxiliar para bind_param con arrays dinámicos
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
