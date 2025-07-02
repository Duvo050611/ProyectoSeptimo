```php
<?php
session_start();
include "../../conexionbd.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['message'] = "Método no permitido.";
    $_SESSION['message_type'] = "danger";
    header("Location: reg_anestesia.php");
    exit;
}

if (!isset($_SESSION['login']['id_usua']) || !isset($_SESSION['hospital'])) {
    $_SESSION['message'] = "No está autorizado para realizar esta acción.";
    $_SESSION['message_type'] = "danger";
    header("Location: ../../index.php");
    exit;
}

function validateInput($data) {
    return htmlspecialchars(trim($data));
}

function validateNumeric($value, $min, $max) {
    if ($value === '' || $value === null) return null;
    $value = floatval($value);
    return ($value >= $min && $value <= $max) ? $value : null;
}

function validateDateTime($value) {
    if (empty($value)) return null;
    try {
        $date = new DateTime($value);
        return $date->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        return null;
    }
}

function validateArray($array) {
    if (!is_array($array)) return [];
    return array_map('validateInput', array_filter($array));
}

// Validate and sanitize inputs
$Id_exp = validateInput($_POST['Id_exp'] ?? '');
$id_usua = validateInput($_POST['id_usua'] ?? '');
$id_atencion = validateInput($_POST['id_atencion'] ?? '');
$anestesiologo_id = validateInput($_POST['anestesiologo_id'] ?? '');
$tipo_anestesia = in_array($_POST['tipo_anestesia'] ?? '', ['General', 'Regional', 'Local', 'Sedación']) ? $_POST['tipo_anestesia'] : null;
$diagnostico_preoperatorio = validateInput($_POST['diagnostico_preoperatorio'] ?? '');
$cirugia_programada = validateInput($_POST['cirugia_programada'] ?? '');
$diagnostico_postoperatorio = validateInput($_POST['diagnostico_postoperatorio'] ?? '');
$cirugia_realizada = validateInput($_POST['cirugia_realizada'] ?? '');
$cirujano_id = validateInput($_POST['cirujano_id'] ?? '');
$ayudantes_ids = isset($_POST['ayudantes_ids']) ? validateArray($_POST['ayudantes_ids']) : [];
$ayudantes_ids_str = !empty($ayudantes_ids) ? implode(',', $ayudantes_ids) : null;
$revision_equipo = in_array($_POST['revision_equipo'] ?? '', ['OK', 'No OK', '']) ? $_POST['revision_equipo'] : null;
$o2_hora = validateInput($_POST['o2_hora'] ?? '');
$agente_inhalado = validateInput($_POST['agente_inhalado'] ?? '');
$farmacos = validateArray($_POST['farmacos'] ?? []);
$dosis_total = validateArray($_POST['dosis_total'] ?? []);
// Combine farmacos and dosis_total into a single string
$farmacos_dosis_total = '';
if (!empty($farmacos) && count($farmacos) === count($dosis_total)) {
    $farmacos_pairs = [];
    for ($i = 0; $i < count($farmacos); $i++) {
        if (!empty($farmacos[$i]) && !empty($dosis_total[$i])) {
            $farmacos_pairs[] = "{$farmacos[$i]}:{$dosis_total[$i]}";
        }
    }
    $farmacos_dosis_total = !empty($farmacos_pairs) ? implode(',', $farmacos_pairs) : null;
}
$ecg_continua = isset($_POST['ecg_continua']) ? 1 : 0;
$pulsoximetria = isset($_POST['pulsoximetria']) ? 1 : 0;
$capnografia = isset($_POST['capnografia']) ? 1 : 0;
$intubacion = validateInput($_POST['intubacion'] ?? '');
$incidentes = validateInput($_POST['incidentes'] ?? '');
$canula = validateInput($_POST['canula'] ?? '');
$dificultad_tecnica = in_array($_POST['dificultad_tecnica'] ?? '', ['Sí', 'No', '']) ? $_POST['dificultad_tecnica'] : null;
$ventilacion = validateInput($_POST['ventilacion'] ?? '');
$hartmann = validateNumeric($_POST['hartmann'] ?? null, 0, 10000);
$glucosa = validateNumeric($_POST['glucosa'] ?? null, 0, 10000);
$nacl = validateNumeric($_POST['nacl'] ?? null, 0, 10000);
$total_ingresos = validateNumeric($_POST['total_ingresos'] ?? null, 0, 30000);
$diuresis = validateNumeric($_POST['diuresis'] ?? null, 0, 10000);
$sangrado = validateNumeric($_POST['sangrado'] ?? null, 0, 10000);
$perdidas_insensibles = validateNumeric($_POST['perdidas_insensibles'] ?? null, 0, 10000);
$total_egresos = validateNumeric($_POST['total_egresos'] ?? null, 0, 30000);
$balance = validateNumeric($_POST['balance'] ?? null, -30000, 30000);
$aldrete_actividad = validateNumeric($_POST['aldrete_actividad'] ?? null, 0, 2);
$aldrete_respiracion = validateNumeric($_POST['aldrete_respiracion'] ?? null, 0, 2);
$aldrete_circulacion = validateNumeric($_POST['aldrete_circulacion'] ?? null, 0, 2);
$aldrete_conciencia = validateNumeric($_POST['aldrete_conciencia'] ?? null, 0, 2);
$aldrete_saturacion = validateNumeric($_POST['aldrete_saturacion'] ?? null, 0, 2);
$aldrete_total = validateNumeric($_POST['aldrete_total'] ?? null, 0, 10);
$anestesia_regional_tipo = in_array($_POST['anestesia_regional_tipo'] ?? '', ['', 'Peribulbar', 'Retrobulbar', 'Subtenoniana']) ? $_POST['anestesia_regional_tipo'] : null;
$aguja = validateInput($_POST['aguja'] ?? '');
$nivel_puncion = validateInput($_POST['nivel_puncion'] ?? '');
$cateter = in_array($_POST['cateter'] ?? '', ['Sí', 'No', '']) ? $_POST['cateter'] : null;
$agentes_administrados = validateInput($_POST['agentes_administrados'] ?? '');
$llega_quirofano = validateDateTime($_POST['llega_quirofano'] ?? '');
$inicia_anestesia = validateDateTime($_POST['inicia_anestesia'] ?? '');
$inicia_cirugia = validateDateTime($_POST['inicia_cirugia'] ?? '');
$termina_cirugia = validateDateTime($_POST['termina_cirugia'] ?? '');
$termina_anestesia = validateDateTime($_POST['termina_anestesia'] ?? '');
$pasa_recuperacion = validateDateTime($_POST['pasa_recuperacion'] ?? '');
$tiempo_anestesico = validateNumeric($_POST['tiempo_anestesico'] ?? null, 0, 1440);

// Vital signs
$sist = validateNumeric($_POST['sistg'] ?? null, 10, 300);
$diast = validateNumeric($_POST['diastg'] ?? null, 10, 200);
$ta = ($sist !== null && $diast !== null) ? "$sist/$diast" : null;
$fc = validateNumeric($_POST['fcardg'] ?? null, 0, 300);
$fr = validateNumeric($_POST['frespg'] ?? null, 0, 100);
$spo2 = validateNumeric($_POST['satg'] ?? null, 0, 100);
$temp = validateNumeric($_POST['tempg'] ?? null, 0, 45);

// Validate required fields
$required_fields = ['Id_exp', 'id_usua', 'id_atencion', 'anestesiologo_id', 'tipo_anestesia', 'cirujano_id'];
$missing_fields = [];
foreach ($required_fields as $field) {
    if (empty($$field)) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    $_SESSION['message'] = "Faltan campos obligatorios: " . implode(', ', $missing_fields);
    $_SESSION['message_type'] = "danger";
    header("Location: reg_anestesia.php");
    exit;
}

// Validate vital signs
if (!$ta || $fc === null || $fr === null || $spo2 === null || $temp === null) {
    $_SESSION['message'] = "Debe registrar un conjunto completo de signos vitales.";
    $_SESSION['message_type'] = "danger";
    header("Location: reg_anestesia.php");
    exit;
}

// Prepare and execute SQL for registro_anestesico
$sql = "INSERT INTO registro_anestesico (
    id_atencion, Id_exp, id_usua, anestesiologo_id, tipo_anestesia, diagnostico_preoperatorio, 
    cirugia_programada, diagnostico_postoperatorio, cirugia_realizada, cirujano_id, ayudantes_ids, 
    ta, fc, fr, spo2, temp, revision_equipo, o2_hora, agente_inhalado, farmacos_dosis_total, 
    ecg_continua, pulsoximetria, capnografia, intubacion, incidentes, canula, dificultad_tecnica, 
    ventilacion, hartmann, glucosa, nacl, total_ingresos, diuresis, sangrado, perdidas_insensibles, 
    total_egresos, balance, aldrete_actividad, aldrete_respiracion, aldrete_circulacion, 
    aldrete_conciencia, aldrete_saturacion, aldrete_total, anestesia_regional_tipo, aguja, 
    nivel_puncion, cateter, agentes_administrados, llega_quirofano, inicia_anestesia, 
    inicia_cirugia, termina_cirugia, termina_anestesia, pasa_recuperacion, tiempo_anestesico
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    $_SESSION['message'] = "Error al preparar la consulta: " . $conexion->error;
    $_SESSION['message_type'] = "danger";
    header("Location: reg_anestesia.php");
    exit;
}

// Bind parameters (54 parameters: 11 integers, 25 strings, 6 datetimes, 12 integers)
$stmt->bind_param(
    "iiisssssssssiiddissssiissssiidddiddddiiiiiisssssssssssi",
    $id_atencion, $Id_exp, $id_usua, $anestesiologo_id, $tipo_anestesia, $diagnostico_preoperatorio,
    $cirugia_programada, $diagnostico_postoperatorio, $cirugia_realizada, $cirujano_id, $ayudantes_ids_str,
    $ta, $fc, $fr, $spo2, $temp, $revision_equipo, $o2_hora, $agente_inhalado, $farmacos_dosis_total,
    $ecg_continua, $pulsoximetria, $capnografia, $intubacion, $incidentes, $canula, $dificultad_tecnica,
    $ventilacion, $hartmann, $glucosa, $nacl, $total_ingresos, $diuresis, $sangrado, $perdidas_insensibles,
    $total_egresos, $balance, $aldrete_actividad, $aldrete_respiracion, $aldrete_circulacion,
    $aldrete_conciencia, $aldrete_saturacion, $aldrete_total, $anestesia_regional_tipo, $aguja,
    $nivel_puncion, $cateter, $agentes_administrados, $llega_quirofano, $inicia_anestesia,
    $inicia_cirugia, $termina_cirugia, $termina_anestesia, $pasa_recuperacion, $tiempo_anestesico
);

if ($stmt->execute()) {
    $_SESSION['message'] = "Registro anestésico guardado exitosamente.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error al guardar el registro: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}

$stmt->close();
$conexion->close();
header("Location: reg_anestesia.php");
exit;
?>
```