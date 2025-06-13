<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_not_gabinete = isset($_GET['id_not_gabinete']) ? (int)$_GET['id_not_gabinete'] : 0;
$id_exp = isset($_GET['id_exp']) ? (int)$_GET['id_exp'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

// Validate parameters
$errors = [];
if ($id_not_gabinete <= 0) {
    $errors[] = "ID de notificación inválido (id_not_gabinete=$id_not_gabinete)";
}
if ($id_exp <= 0) {
    $errors[] = "ID de expediente inválido (id_exp=$id_exp)";
}
if ($id_atencion <= 0) {
    $errors[] = "ID de atención inválido (id_atencion=$id_atencion)";
}
if (!empty($errors)) {
    error_log("Parámetros inválidos: " . implode(", ", $errors));
    die("Error en los parámetros: " . implode(", ", $errors));
}

// Fetch exam data
$sql_gab = "SELECT ng.*, ru.pre, ru.papell, ru.sapell 
            FROM notificaciones_gabinete ng 
            LEFT JOIN reg_usuarios ru ON ng.id_usua = ru.id_usua 
            WHERE ng.id_not_gabinete = ? AND ng.id_atencion = ? AND ng.Id_exp = ?";
$stmt_gab = $conexion->prepare($sql_gab);
if (!$stmt_gab) {
    error_log("Error preparando consulta de notificación: " . $conexion->error);
    die("Error preparando consulta de notificación: " . $conexion->error);
}
$stmt_gab->bind_param("iii", $id_not_gabinete, $id_atencion, $id_exp);
$stmt_gab->execute();
$result_gab = $stmt_gab->get_result();
if (!$gab = $result_gab->fetch_assoc()) {
    error_log("Notificación no encontrada para id_not_gabinete: $id_not_gabinete, id_atencion: $id_atencion, id_exp: $id_exp");
    die("Notificación no encontrada.");
}
$stmt_gab->close();

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
$medico = "Médico no asignado";
$doctor = $medico;
if ($gab['pre'] || $gab['papell'] || $gab['sapell']) {
    $medico = ($gab['pre'] ? $gab['pre'] . ". " : "") . $gab['papell'] . " " . ($gab['sapell'] ?? "");
    $doctor = $gab['papell'];
}

// Fetch vital signs
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

// Extract result details
$resultado_file = $gab['resultado'] ?? null;
$fecha_resultado = $gab['fecha_resultado'];
$det_gab = $gab['det_gab'] ?? null;
$realizado = $gab['realizado'] ?? 'NO';

// Parse det_gab to extract comment
function extractComment($det_gab) {
    if (!$det_gab) {
        error_log("det_gab es NULL o vacío");
        return 'No hay comentarios disponibles.';
    }
    error_log("det_gab crudo: " . $det_gab);
    if (preg_match('/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2} - [^\]]+\]:\s*(.+)$/s', $det_gab, $matches)) {
        $comment = trim($matches[1]);
        error_log("Comentario extraído: " . $comment);
        return $comment ?: 'No hay comentarios disponibles.';
    }
    $trimmed = trim($det_gab);
    error_log("Formato no coincide, usando det_gab recortado: " . $trimmed);
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

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('REPORTE DE EXÁMENES DE GABINETE'), 0, 1, 'C');
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
    'Estudios Solicitados' => $gab['sol_estudios'] ? $gab['sol_estudios'] : null,
];

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode('Exámenes Solicitados'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
foreach ($exams as $exam => $value) {
    if ($value && $value !== 0) {
        $pdf->SetX(15);
        $pdf->MultiCell(175, 5, utf8_decode($exam . ': ' . $value), 0, 'L');
        $pdf->Ln(2);
    }
}
$pdf->Ln(3);

// Result details
$image_added = false;
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode('Resultados de Gabinete'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);
if ($realizado === 'SI' && $resultado_file) {
    $file_path = realpath("../notas_medicas/resultados_gabinete/$resultado_file");
    $ext = strtolower(pathinfo($resultado_file, PATHINFO_EXTENSION));
    $pdf->SetX(15);
    $pdf->MultiCell(175, 5, utf8_decode('Fecha de resultado: ' . ($fecha_resultado ? date_format(date_create($fecha_resultado), "d/m/Y H:i") : 'N/A')), 0, 'L');
    $pdf->Ln(2);
    $pdf->SetX(15);
    $pdf->MultiCell(175, 5, utf8_decode('Detalles: ' . extractComment($det_gab)), 0, 'L');
    $pdf->Ln(2);

    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
        if ($file_path && file_exists($file_path)) {
            $pdf->AddPage();
            $pdf->Image($file_path, 10, 50, 190, 0);
            $image_added = true;
            $pdf->SetY($pdf->GetY() + 120);
        } else {
            $pdf->SetX(15);
            $pdf->MultiCell(175, 5, utf8_decode('Archivo de resultado no encontrado: ' . $resultado_file . ' (Ruta: ' . ($file_path ?: 'No resuelto') . ')'), 0, 'L');
        }
    } elseif ($ext === 'pdf') {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $base_url = "$protocol://$host";
        $pdf_result_url = "$base_url/gestion_medica/notas_medicas/resultados_gabinete/$resultado_file";
        
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
    $pdf->MultiCell(175, 5, utf8_decode('Comentarios: ' . extractComment($det_gab)), 0, 'L');
}
$pdf->Ln(5);

if (!$image_added) {
    $pdf->Ln(5);
}
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, utf8_decode("Solicitado por: {$medico}"), 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, utf8_decode("_____________________"), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode("Firma"), 0, 1, 'C');

// Output PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="examenes_gabinete.pdf"');
$pdf->Output('I', '');
exit();
?>