<?php
include '../../conexionbd.php';
require '../../fpdf/fpdf.php';
mysqli_set_charset($conexion, "utf8");

$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;
$not_id = isset($_GET['not_id']) ? (int)$_GET['not_id'] : (isset($_GET['¬_id']) ? (int)$_GET['¬_id'] : 0);

if ($not_id === 0 || $id_atencion === 0) {
    error_log("Invalid parameters: not_id=$not_id, id_atencion=$id_atencion");
    header("Location: sol_laboratorio.php?error=" . urlencode("Parámetros inválidos."));
    exit();
}

// Fetch notification data
$sql = "SELECT n.*, u.pre, u.nombre, u.papell, u.sapell 
        FROM notificaciones_labo n 
        JOIN reg_usuarios u ON n.id_usua = u.id_usua 
        WHERE n.not_id = ? AND n.id_atencion = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    error_log("Prepare failed: " . $conexion->error);
    header("Location: sol_laboratorio.php?error=" . urlencode("Error en la consulta."));
    exit();
}
$stmt->bind_param("ii", $not_id, $id_atencion);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    error_log("No data found for not_id=$not_id, id_atencion=$id_atencion");
    header("Location: sol_laboratorio.php?error=" . urlencode("Datos no encontrados."));
    exit();
}

// Extract notification data
$id_usua = $row['id_usua'];
$habitacion = $row['habitacion'] ?? 'N/A';
$fecha_ord = $row['fecha_ord'];
$sol_estudios = $row['sol_estudios'];
$det_labo = $row['det_labo'] ?? 'Consulta médica';
$medico = ($row['pre'] ? $row['pre'] . ". " : "") . $row['nombre'] . " " . $row['papell'] . " " . ($row['sapell'] ? $row['sapell'] : "");
$doctor = $row['papell'] ?? "Médico no asignado";

// Fetch patient data
$sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.fecnac, p.Id_exp, p.folio, di.fecha, p.sexo, di.alergias 
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ?";
$stmt_pac = $conexion->prepare($sql_pac);
$stmt_pac->bind_param("i", $id_atencion);
$stmt_pac->execute();
$result_pac = $stmt_pac->get_result();
$row_pac = $result_pac->fetch_assoc();
$stmt_pac->close();

if (!$row_pac) {
    error_log("No patient data found for id_atencion=$id_atencion");
    header("Location: sol_laboratorio.php?error=" . urlencode("Datos del paciente no encontrados."));
    exit();
}

$pac_papell = $row_pac['papell'] ?? '';
$pac_sapell = $row_pac['sapell'] ?? '';
$pac_nom_pac = $row_pac['nom_pac'] ?? '';
$pac_fecnac = $row_pac['fecnac'] ?? '';
$folio = $row_pac['folio'] ?? '';
$pac_fecing = $row_pac['fecha'] ?? '';
$pac_sexo = $row_pac['sexo'] ?? '';
$pac_alergias = $row_pac['alergias'] ?? 'No especificado';

<<<<<<< HEAD
// Fetch vital signs
$sql_signs = "SELECT p_sistol, p_diastol, fresp, temper, satoxi 
              FROM signos_vitales 
              WHERE id_atencion = ? 
              ORDER BY id_sig DESC LIMIT 1";
=======
// Fetch vital signs from exploracion_fisica
$sql_signs = "SELECT presion_sistolica, presion_diastolica, frecuencia_respiratoria, temperatura, spo2 
              FROM exploracion_fisica 
              WHERE id_atencion = ? 
              ORDER BY fecha DESC LIMIT 1";
>>>>>>> 984cba820b36f13e9c30ee7913b8e5011f1d9d07
$stmt_signs = $conexion->prepare($sql_signs);
$stmt_signs->bind_param("i", $id_atencion);
$stmt_signs->execute();
$result_signs = $stmt_signs->get_result();
$row_signs = $result_signs->fetch_assoc();
<<<<<<< HEAD
$stmt_signs->close();

$p_sistolica = $row_signs['p_sistol'] ?? '';
$p_diastolica = $row_signs['p_diastol'] ?? '';
$f_resp = $row_signs['fresp'] ?? '';
$temp = $row_signs['temper'] ?? '';
$sat_oxigeno = $row_signs['satoxi'] ?? '';

=======
$p_sistolica = $row_signs['presion_sistolica'] ?? '';
$p_diastolica = $row_signs['presion_diastolica'] ?? '';
$f_resp = $row_signs['frecuencia_respiratoria'] ?? '';
$temp = $row_signs['temperatura'] ?? '';
$sat_oxigeno = $row_signs['spo2'] ?? '';
$stmt_signs->close();

>>>>>>> 984cba820b36f13e9c30ee7913b8e5011f1d9d07
// Calculate age
function calculaedad($fechanacimiento) {
    if (!$fechanacimiento || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechanacimiento)) {
        return 'Edad no disponible';
    }
    list($ano, $mes, $dia) = explode("-", $fechanacimiento);
    $ano_diferencia = date("Y") - $ano;
    $mes_diferencia = date("m") - $mes;
    $dia_diferencia = date("d") - $dia;
    if ($dia_diferencia < 0) {
        $mes_diferencia--;
        $dia_diferencia += date("t", strtotime("$ano-$mes-01"));
    }
    if ($mes_diferencia < 0) {
        $ano_diferencia--;
        $mes_diferencia += 12;
    }
    if ($ano_diferencia > 0) {
        return $ano_diferencia . ' AÑOS';
    } elseif ($mes_diferencia > 0) {
        return $mes_diferencia . ' MESES';
    } else {
        return $dia_diferencia . ' DÍAS';
    }
}
$edad = calculaedad($pac_fecnac);

// Prepare studies list
$studies = preg_split('/[,;]/', $sol_estudios, -1, PREG_SPLIT_NO_EMPTY);
$numbered_studies = [];
foreach ($studies as $index => $study) {
    $numbered_studies[] = ($index + 1) . ". " . trim($study);
}
$studies_list = implode("\n", $numbered_studies);

// Current date/time
$fecha_actual = date("d/m/Y H:i:s");

// PDF class
class PDF extends FPDF {
    function Header() {
        include '../../conexionbd.php';
        $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
        while ($f = mysqli_fetch_array($resultado)) {
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
$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(1, 8, 209, 8);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, utf8_decode('SOLICITUD DE ESTUDIOS DE LABORATORIO'), 0, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 5, utf8_decode("Paciente: {$folio} - {$pac_papell} {$pac_sapell} {$pac_nom_pac}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Signos vitales:"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Presión arterial: {$p_sistolica}/{$p_diastolica} mmHg                            Frecuencia: {$f_resp} Resp/min                       Temperatura: {$temp} °C                       Saturación: {$sat_oxigeno}%"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Edad: {$edad}                                          Sexo: {$pac_sexo}                                     Fecha de ingreso: {$pac_fecing}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Fecha de solicitud: {$fecha_actual}                                                               Fecha y hora: {$fecha_actual}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Médico tratante: {$doctor}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Estudio(s) solicitado(s):"), 0, 1, 'L');
$pdf->MultiCell(0, 5, utf8_decode($studies_list), 0, 'L');
$pdf->Cell(0, 5, utf8_decode("Detalle de estudio:"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Diagnóstico probable: Consulta médica"), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Solicita: {$medico}"), 0, 1, 'C');
$pdf->Ln(15);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, utf8_decode('_____________________'), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode('Firma'), 0, 1, 'C');

$bottom_y = $pdf->GetY() + 10;
$pdf->Line(1, $bottom_y, 209, $bottom_y);
$pdf->Line(1, 8, 1, $bottom_y);
$pdf->Line(209, 8, 209, $bottom_y);

// Output PDF to browser
ob_end_clean();
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="solicitud_lab.pdf"');
$pdf->Output('I', 'solicitud_lab.pdf');
exit();

<<<<<<< HEAD
$conexion->close();
?>ame="solicitud_lab.pdf"');
$pdf->Output('I', 'solicitud_lab.pdf');
exit();

=======
>>>>>>> 984cba820b36f13e9c30ee7913b8e5011f1d9d07
$conexion->close();
?>