<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_exp = isset($_GET['id_exp']) ? (int)$_GET['id_exp'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

if ($id <= 0 || $id_exp <= 0 || $id_atencion <= 0) {
    die("ID de examen, expediente o atención inválido.");
}

// Fetch exam data
$sql_lab = "SELECT * FROM ocular_examenes_laboratorio WHERE id = ? AND id_atencion = ? AND Id_exp = ?";
$stmt_lab = $conexion->prepare($sql_lab);
if (!$stmt_lab) {
    error_log("Error preparando consulta de examen: " . $conexion->error);
    die("Error preparando consulta de examen: " . $conexion->error);
}
$stmt_lab->bind_param("iii", $id, $id_atencion, $id_exp);
$stmt_lab->execute();
$result_lab = $stmt_lab->get_result();
if (!$lab = $result_lab->fetch_assoc()) {
    error_log("Examen no encontrado para id: $id, id_atencion: $id_atencion, id_exp: $id_exp");
    die("Examen no encontrado.");
}
$stmt_lab->close();

// Fetch patient data
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.folio, di.fecha, p.sexo, di.alergias 
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ?";
$stmt_pac = $conexion->prepare($sql_pac);
if (!$stmt_pac) {
    error_log("Error preparando consulta de paciente: " . $conexion->error);
    die("Error preparando consulta de paciente: " . $conexion->error);
}
$stmt_pac->bind_param("i", $id_atencion);
$stmt_pac->execute();
$result_pac = $stmt_pac->get_result();
if (!$row_pac = $result_pac->fetch_assoc()) {
    error_log("Paciente no encontrado para id_atencion: $id_atencion");
    die("Paciente no encontrado.");
}
$pac_papell = $row_pac['papell'];
$pac_sapell = $row_pac['sapell'];
$pac_nom_pac = $row_pac['nom_pac'];
$pac_fecnac = $row_pac['fecnac'] ?? null;
$folio = $row_pac['folio'];
$pac_fecing = $row_pac['fecha'];
$pac_sexo = $row_pac['sexo'];
$pac_alergias = $row_pac['alergias'] ?? 'No especificado';
$stmt_pac->close();

// Fetch doctor data
$sql_doc = "SELECT pre, papell, sapell FROM reg_usuarios WHERE id_usua = ?";
$stmt_doc = $conexion->prepare($sql_doc);
if (!$stmt_doc) {
    error_log("Error preparando consulta de médico: " . $conexion->error);
    die("Error preparando consulta de médico: " . $conexion->error);
}
$stmt_doc->bind_param("i", $lab['id_usua']);
$stmt_doc->execute();
$result_doc = $stmt_doc->get_result();
$medico = "Médico no asignado";
$doctor = $medico;
if ($row_doc = $result_doc->fetch_assoc()) {
    $medico = ($row_doc['pre'] ? $row_doc['pre'] . ". " : "") . $row_doc['papell'] . " " . ($row_doc['sapell'] ?? "");
    $doctor = $row_doc['papell'];
}
$stmt_doc->close();

// Fetch vital signs from exploracion_fisica
$sql_signs = "SELECT presion_sistolica, presion_diastolica, frecuencia_respiratoria, temperatura, spo2 
              FROM exploracion_fisica 
              WHERE id_atencion = ? 
              ORDER BY fecha DESC LIMIT 1";
$stmt_signs = $conexion->prepare($sql_signs);
if (!$stmt_signs) {
    error_log("Error preparando consulta de signos vitales: " . $conexion->error);
    die("Error preparando consulta de signos vitales: " . $conexion->error);
}
$stmt_signs->bind_param("i", $id_atencion);
$stmt_signs->execute();
$result_signs = $stmt_signs->get_result();
$row_signs = $result_signs->fetch_assoc();
$p_sistolica = $row_signs['presion_sistolica'] ?? '';
$p_diastolica = $row_signs['presion_diastolica'] ?? '';
$f_resp = $row_signs['frecuencia_respiratoria'] ?? '';
$temp = $row_signs['temperatura'] ?? '';
$sat_oxigeno = $row_signs['spo2'] ?? '';
$stmt_signs->close();

// Fetch lab result from notificaciones_labo
$sql_notif = "SELECT realizado, resultado, fecha_resultado, det_labo 
              FROM notificaciones_labo 
              WHERE id_atencion = ? AND id_examen_labo = ?";
$stmt_notif = $conexion->prepare($sql_notif);
if (!$stmt_notif) {
    error_log("Error preparando consulta de notificaciones: " . $conexion->error);
    die("Error preparando consulta de notificaciones: " . $conexion->error);
}
$stmt_notif->bind_param("ii", $id_atencion, $id);
$stmt_notif->execute();
$result_notif = $stmt_notif->get_result();
$resultado_file = null;
$fecha_resultado = null;
$det_labo = null;
$realizado = 'NO';
if ($row_notif = $result_notif->fetch_assoc()) {
    $realizado = $row_notif['realizado'] ?? 'NO';
    $resultado_file = $row_notif['resultado'] ?? null;
    $fecha_resultado = $row_notif['fecha_resultado'];
    $det_labo = $row_notif['det_labo'] ?? null;
    error_log("Notificación encontrada - id_atencion: $id_atencion, id_examen_labo: $id, det_labo: " . ($det_labo ?? 'NULL'));
} else {
    error_log("No se encontró notificación para id_atencion: $id_atencion, id_examen_labo: $id");
}
$stmt_notif->close();

// Parse det_labo to extract comment
function extractComment($det_labo) {
    if (!$det_labo) {
        error_log("det_labo es NULL o vacío");
        return 'No hay comentarios disponibles.';
    }
    error_log("det_labo crudo: " . $det_labo);
    // Match format: [YYYY-MM-DD HH:MM - Username]: Comment
    if (preg_match('/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2} - [^\]]+\]:\s*(.+)$/s', $det_labo, $matches)) {
        $comment = trim($matches[1]);
        error_log("Comentario extraído: " . $comment);
        return $comment ?: 'No hay comentarios disponibles.';
    }
    // Fallback: return trimmed det_labo if format doesn't match
    $trimmed = trim($det_labo);
    error_log("Formato no coincide, usando det_labo recortado: " . $trimmed);
    return $trimmed ?: 'No hay comentarios disponibles.';
}

// Calculate age
function calculaedad($fechanacimiento) {
    if (!$fechanacimiento || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechanacimiento)) {
        return 'Edad no disponible';
    }
    $fecha_actual = date("Y-m-d");
    $arr_nac = explode("-", $fechanacimiento);
    $arr_act = explode("-", $fecha_actual);
    $anos = $arr_act[0] - $arr_nac[0];
    $meses = $arr_act[1] - $arr_nac[1];
    $dias = $arr_act[2] - $arr_nac[2];

    if ($dias < 0) {
        $meses--;
        $dias += date("t", strtotime("$arr_act[0]-$arr_act[1]-01"));
    }
    if ($meses < 0) {
        $anos--;
        $meses += 12;
    }

    if ($anos > 0) {
        return $anos . " años";
    } elseif ($meses > 0) {
        return $meses . " meses";
    } else {
        return $dias . " días";
    }
}
$edad = calculaedad($pac_fecnac);

// Current date and time
$fecha_actual = date("d/m/Y H:i:s");

// Create PDF class
class PDF extends FPDF {
    function Header() {
        include "../../conexionbd.php";
        $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
        while ($f = mysqli_fetch_assoc($resultado)) {
            $bas = $f['img_ipdf'];
            $this->Image("../../configuracion/admin/img2/{$bas}", 7, 9, 40, 25);
            $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 168, 16, 38, 14);
        }
        $this->Ln(32);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('INEO-000'), 0, 1, 'R');
    }
}

// Generate PDF
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetDrawColor(43, 45, 127);
/* $pdf->Line(1, 8, 209, 8); */

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('REPORTE DE EXÁMENES DE LABORATORIO OCULAR'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, utf8_decode("Paciente: {$folio} - {$pac_papell} {$pac_sapell} {$pac_nom_pac}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Signos vitales:"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Presión arterial: {$p_sistolica}/{$p_diastolica} mmHg    Frecuencia: {$f_resp} Resp/min    Temperatura: {$temp} °C    Saturación: {$sat_oxigeno}%"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Edad: {$edad}    Sexo: {$pac_sexo}    Fecha de ingreso: {$pac_fecing}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Fecha de registro: {$fecha_actual}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Médico tratante: {$doctor}"), 0, 1, 'L');
$pdf->Ln(5);

// List requested exams
$exams = [
    'Biometría Hemática' => $lab['biometria_hematica'],
    'Química Sanguínea' => $lab['quimica_sanguinea'],
    'Química Sanguínea Valores' => $lab['quimica_sanguinea_valores'] ? 'Solicitado' : null,
    'Tiempos de Coagulación' => $lab['tiempos_coagulacion'],
    'Hemoglobina Glucosilada' => $lab['hemoglobina_glucosilada'],
    'Examen General de Orina' => $lab['examen_general_orina'],
    'Electrocardiograma' => $lab['electrocardiograma'],
    'Pruebas de Función Hepática' => $lab['pruebas_funcion_hepatica'],
    'Antígeno SARS-CoV-2' => $lab['antigeno_sars_cov_2'],
    'PCR SARS-CoV-2' => $lab['pcr_sars_cov_2'],
    'Electrolitos Séricos' => $lab['electroitos_sericos'],
    'Pruebas de Función Tiroidea' => $lab['pruebas_funcion_tiroidea'],
    'Ac. Anti-Tiroglobulina' => $lab['acs_anti_tiroglubolina'],
    'Ac. Antireceptores TSH' => $lab['acs_antireceptores_tsh'],
    'Ac. Antiperoxidasa' => $lab['acs_antiperoxadasa'],
    'Velocidad de Sedimentación Globular' => $lab['velocidad_sedimentacion_globular'],
    'Proteína C Reactiva' => $lab['proteina_c_reactiva'],
    'VDRL' => $lab['vdrl'],
    'FTA-ABS' => $lab['fta_abs'],
    'PPD' => $lab['ppd'],
    'ELISA VIH 1 y 2' => $lab['elisa_vih_1_y_2'],
    'Ac. Toxoplasmosis IgG/IgM' => $lab['acs_toxoplasmosis_igg_igm'],
    'Factor Reumatoide' => $lab['factor_reumatoide'],
    'Ac. Anti-CCP' => $lab['acs_anti_ccp'],
    'Antígeno HLA-B27' => $lab['antigeno_hla_b27'],
    'Ac. Antinucleares' => $lab['acs_antinucleares'],
    'Ac. Anticardiolipina' => $lab['acs_anticardiolipina'],
    'Ac. p-ANCA y c-ANCA' => $lab['acs_p_ancasy_c_ancas'],
    'Otros' => $lab['otros_laboratorio'] ? $lab['otros_laboratorio'] : null,
];

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode('Exámenes Solicitados'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
foreach ($exams as $exam => $value) {
    if ($value && $value !== 0) {
        $pdf->SetX(15);
        $pdf->MultiCell(175, 5, utf8_decode($exam . ': ' . ($exam === 'Otros' ? $value : 'Solicitado')), 0, 'L');
        $pdf->Ln(2);
    }
}
$pdf->Ln(3);

// Result details
$image_added = false; // Track if an image is added
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode('Resultados de Laboratorio'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
if ($realizado === 'SI' && $resultado_file) {
    $file_path = realpath("../notas_medicas/resultados/$resultado_file");
    $ext = strtolower(pathinfo($resultado_file, PATHINFO_EXTENSION));
    $pdf->SetX(15);
    $pdf->MultiCell(175, 5, utf8_decode('Fecha de resultado: ' . ($fecha_resultado ? date_format(date_create($fecha_resultado), "d/m/Y H:i") : 'N/A')), 0, 'L');
    $pdf->Ln(2);
    $pdf->SetX(15);
    $pdf->MultiCell(175, 5, utf8_decode('Detalles: ' . extractComment($det_labo)), 0, 'L');
    $pdf->Ln(2);

    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
        if ($file_path && file_exists($file_path)) {
            $pdf->AddPage();
            $pdf->Image($file_path, 10, 50, 190, 0); // Adjusted y=40 to avoid header overlap
            $image_added = true;
            // Move cursor below the image (190mm width, auto-scaled height)
            $pdf->SetY($pdf->GetY() + 120); // Approximate height, adjust as needed
        } else {
            $pdf->SetX(15);
            $pdf->MultiCell(175, 5, utf8_decode('Archivo de resultado no encontrado: ' . $resultado_file . ' (Ruta: ' . ($file_path ?: 'No resuelto') . ')'), 0, 'L');
        }
    } elseif ($ext === 'pdf') {
        // Dynamically generate the base URL
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $base_url = "$protocol://$host";
        $pdf_result_url = "$base_url/gestion_medica/notas_medicas/resultados/$resultado_file";
        
        $pdf->SetX(15);
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Cell(0, 5, utf8_decode('Ver resultado PDF: ' . $pdf_result_url), 'L', 1, 'L', false, $pdf_result_url);
        $pdf->SetTextColor(0, 0, 0);
    } else {
        $pdf->SetX(15);
        $pdf->MultiCell(175, 5, utf8_decode('Formato de archivo no soportado: ' . $resultado_file), 0, 'L');
    }
} else {
    $pdf->SetX(15);
    $pdf->MultiCell(175, 5, utf8_decode('Comentarios: ' . extractComment($det_labo)), 0, 'L');
}
$pdf->Ln(5);

// Add signature section (below image if added)
if (!$image_added) {
    $pdf->Ln(5);
}
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, utf8_decode("Solicitado por: {$medico}"), 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, utf8_decode("_____________________"), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode("Firma"), 0, 1, 'C');

/* $bottom_y = $pdf->GetY() + 10;
$pdf->Line(1, $bottom_y, 209, $bottom_y);
$pdf->Line(1, 8, 1, $bottom_y);
$pdf->Line(209, 8, 209, $bottom_y); */

// Output PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="examenes_laboratorio.pdf"');
$pdf->Output('I', '');
exit();
?>