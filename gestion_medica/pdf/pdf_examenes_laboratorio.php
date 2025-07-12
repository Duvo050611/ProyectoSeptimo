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
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.folio, p.sexo, p.tel, p.ocup, p.dir, di.fecha, di.tipo_a 
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ?";
$stmt_pac = $conexion->prepare($sql_pac);
$stmt_pac->bind_param("i", $id_atencion);
$stmt_pac->execute();
$result_pac = $stmt_pac->get_result();
if (!$row_pac = $result_pac->fetch_assoc()) {
    die("Paciente no encontrado.");
}
$tipo_a = $row_pac['tipo_a'];
$pac_papell = $row_pac['papell'];
$pac_sapell = $row_pac['sapell'];
$pac_nom_pac = $row_pac['nom_pac'];
$pac_fecnac = $row_pac['fecnac'] ?? null;
$folio = $row_pac['folio'];
$pac_fecing = $row_pac['fecha'];
$pac_sexo = $row_pac['sexo'];
$pac_tel = $row_pac['tel'] ?? 'No especificado';
$pac_ocup = $row_pac['ocup'] ?? 'No especificado';
$pac_dir = $row_pac['dir'] ?? 'No especificado';
$stmt_pac->close();

// Fetch doctor data
$sql_doc = "SELECT pre, papell, sapell, nombre, firma, cedp, cargp FROM reg_usuarios WHERE id_usua = ?";
$stmt_doc = $conexion->prepare($sql_doc);
$stmt_doc->bind_param("i", $lab['id_usua']);
$stmt_doc->execute();
$result_doc = $stmt_doc->get_result();
$medico = "Médico no asignado";
$pre_med = '';
$app_med = '';
$apm_med = '';
$nom_med = '';
$firma = '';
$ced_p = '';
$cargp = '';
if ($row_doc = $result_doc->fetch_assoc()) {
    $pre_med = $row_doc['pre'] ?? '';
    $app_med = $row_doc['papell'] ?? '';
    $apm_med = $row_doc['sapell'] ?? '';
    $nom_med = $row_doc['nombre'] ?? '';
    $firma = $row_doc['firma'] ?? '';
    $ced_p = $row_doc['cedp'] ?? '';
    $cargp = $row_doc['cargp'] ?? '';
    $medico = ($pre_med ? $pre_med . ". " : "") . $app_med . " " . $apm_med . " " . $nom_med;
}
$stmt_doc->close();

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
$fecha_actual = date("d/m/Y H:i");

// Create PDF class
class PDF extends FPDF {
    function Header() {
        include "../../conexionbd.php";
        $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
        while ($f = mysqli_fetch_assoc($resultado)) {
            $this->Image("../../configuracion/admin/img2/{$f['img_ipdf']}", 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 168, 16, 38, 14);
        }
        $this->SetY(40);
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(0, 12, utf8_decode('NOTA EXÁMENES DE LABORATORIO'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i'), 0, 1, 'R');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('INEO-000'), 0, 1, 'R');
    }
}

// Generate PDF
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 30);
// Datos del paciente (formato compacto y alineado)
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 6, 'Datos del Paciente:', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(255, 255, 255);

// Línea 1: Servicio y Fecha de Registro
$pdf->Cell(35, 5, 'Servicio:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($tipo_a), 0, 0, 'L');
$pdf->Cell(35, 5, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 5, date('d/m/Y H:i', strtotime($pac_fecing)), 0, 1, 'L');

// Línea 2: Paciente y Teléfono
$pdf->Cell(35, 5, 'Paciente:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($folio . ' - ' . $pac_papell . ' ' . $pac_sapell. ' ' . $pac_nom_pac), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac_tel), 0, 1, 'L');

// Línea 3: Fecha de nacimiento, Edad y Género
$pdf->Cell(35, 5, 'Fecha de nacimiento:', 0, 0, 'L');
$pdf->Cell(30, 5, date('d/m/Y', strtotime($pac_fecnac)), 0, 0, 'L');
$pdf->Cell(10, 5, 'Edad:', 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac_sexo), 0, 1, 'L');

// Línea 4: Domicilio
$pdf->Cell(20, 5, 'Domicilio:', 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($pac_dir), 0, 1, 'L');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 7, utf8_decode('EXÁMENES DE LABORATORIO'), 0, 1, 'C', true);
$pdf->Ln(5);

// Exámenes Solicitados Table
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
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(185, 8, utf8_decode('Exámenes Solicitados'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
foreach ($exams as $exam => $value) {
    if ($value && $value !== 0) {
        $pdf->Cell(185, 8, utf8_decode($exam . ': ' . ($exam === 'Otros' ? $value : 'Solicitado')), 1, 1, 'L');
    }
}

$pdf->Ln(5);

// Resultados de Laboratorio Table
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(185, 8, utf8_decode('Detalles de Resultados'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
if ($realizado === 'SÍ' && $resultado_file) {
    $file_path = realpath("../notas_medicas/resultados/$resultado_file");
    $ext = strtolower(pathinfo($resultado_file, PATHINFO_EXTENSION));
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $base_url = "$protocol://$host";
    $pdf_result_url = "$base_url/gestion_medica/notas_medicas/resultados/$resultado_file";

    $result_fields = [
        'Fecha de resultado' => $fecha_resultado ? date_format(date_create($fecha_resultado), "d/m/Y H:i") : 'N/A',
        'Detalles' => extractComment($det_labo),
    ];

    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $result_fields['Resultado'] = 'Imagen disponible en la siguiente página';
    } elseif ($ext === 'pdf') {
        $result_fields['Resultado'] = "Ver PDF: $pdf_result_url";
    } else {
        $result_fields['Resultado'] = "Formato no soportado: $resultado_file";
    }

    foreach ($result_fields as $label => $value) {
        $pdf->Cell(40, 8, utf8_decode($label), 1, 0, 'L');
        if ($label === 'Resultado' && $ext === 'pdf') {
            $pdf->SetTextColor(0, 0, 255);
            $pdf->Cell(185, 8, utf8_decode($value), 1, 1, 'J', false, $pdf_result_url);
            $pdf->SetTextColor(0, 0, 0);
        } else {
            $pdf->Cell(185, 8, utf8_decode($value), 1, 1, 'J');
        }
    }

    if (in_array($ext, ['jpg', 'jpeg', 'png']) && $file_path && file_exists($file_path)) {
        $pdf->AddPage();
        $pdf->Image($file_path, 10, 50, 190, 0);
    }
} else {
    $pdf->Cell(40, 8, utf8_decode('Comentarios'), 1, 0, 'L');
    $pdf->Cell(145, 8, utf8_decode(extractComment($det_labo)), 1, 1, 'J');
}

$pdf->SetY(-60);
if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 30;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(12);
}
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 6, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 6, utf8_decode($cargp), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Céd. Prof. ' . $ced_p), 0, 1, 'C');

// Output PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="examenes_laboratorio.pdf"');
$pdf->Output('I', 'examenes_laboratorio.pdf');
exit();
?>