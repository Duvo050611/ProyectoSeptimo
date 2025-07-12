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
$stmt_doc->bind_param("i", $gab['id_usua']);
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
        $this->Cell(0, 12, utf8_decode('NOTA EXÁMENES DE GABINETE'), 0, 1, 'C');
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
$pdf->Cell(0, 7, utf8_decode('EXÁMENES DE GABINETE'), 0, 1, 'C', true);
$pdf->Ln(5);

// Exámenes Solicitados Table
$exams = [
    'Estudios Solicitados' => $gab['sol_estudios'] ? $gab['sol_estudios'] : null,
];

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(185, 8, utf8_decode('Exámenes Solicitados'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
foreach ($exams as $exam => $value) {
    if ($value && $value !== 0) {
        $pdf->Cell(185, 8, utf8_decode($value), 1, 1, 'L');
    }
}

$pdf->Ln(5);

// Resultados de Gabinete Table
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(185, 8, utf8_decode('Detalles de Resultados'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
if ($realizado === 'SÍ' && $resultado_file) {
    $file_path = realpath("../notas_medicas/resultados_gabinete/$resultado_file");
    $ext = strtolower(pathinfo($resultado_file, PATHINFO_EXTENSION));
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $base_url = "$protocol://$host";
    $pdf_result_url = "$base_url/gestion_medica/notas_medicas/resultados_gabinete/$resultado_file";

    $result_fields = [
        'Fecha de resultado' => $fecha_resultado ? date_format(date_create($fecha_resultado), "d/m/Y H:i") : 'N/A',
        'Detalles' => extractComment($det_gab),
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
            $pdf->Cell(145, 8, utf8_decode($value), 1, 1, 'J', false, $pdf_result_url);
            $pdf->SetTextColor(0, 0, 0);
        } else {
            $pdf->Cell(145, 8, utf8_decode($value), 1, 1, 'J');
        }
    }

    if (in_array($ext, ['jpg', 'jpeg', 'png']) && $file_path && file_exists($file_path)) {
        $pdf->AddPage();
        $pdf->Image($file_path, 10, 50, 190, 0);
    }
} else {
    $pdf->Cell(40, 8, utf8_decode('Comentarios'), 1, 0, 'L');
    $pdf->Cell(145, 8, utf8_decode(extractComment($det_gab)), 1, 1, 'J');
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
header('Content-Disposition: inline; filename="examenes_gabinete.pdf"');
$pdf->Output('I', 'examenes_gabinete.pdf');
exit();
?>