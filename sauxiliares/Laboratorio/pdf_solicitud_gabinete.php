<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !in_array($_SESSION['login']['id_rol'], [4, 5, 10])) {
    header("Location: ../../index.php");
    exit();
}

$not_id = isset($_GET['not_id']) ? (int)$_GET['not_id'] : 0;
$id_atencion = isset($_GET['id_atencion']) ? (int)$_GET['id_atencion'] : 0;

if ($not_id <= 0 || $id_atencion <= 0) {
    die("ID de notificación o atención no válido.");
}

// Fetch notification data
$sql = "SELECT n.*, u.pre, u.papell, u.sapell 
        FROM notificaciones_gabinete n 
        JOIN reg_usuarios u ON n.id_usua = u.id_usua 
        WHERE n.id_not_gabinete = ? AND n.id_atencion = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $not_id, $id_atencion);
$stmt->execute();
$result = $stmt->get_result();
if (!$notificacion = $result->fetch_assoc()) {
    die("Solicitud de estudio no encontrada.");
}
$stmt->close();

$habitacion = $notificacion['habitacion'];
$fecha_ord = $notificacion['fecha_ord'];
$id_usua = $notificacion['id_usua'];
$sol_estudios = $notificacion['sol_estudios'];
$det_gab = $notificacion['det_gab'];
$medico = ($notificacion['pre'] ? $notificacion['pre'] . ". " : "") . $notificacion['papell'] . " " . ($notificacion['sapell'] ?? "");
$doctor = $notificacion['papell'];

// Fetch patient data
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.fecnac, p.folio, di.fecha, p.sexo, di.alergias 
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
$pac_papell = $row_pac['papell'];
$pac_sapell = $row_pac['sapell'];
$pac_nom_pac = $row_pac['nom_pac'];
$pac_fecnac = $row_pac['fecnac'] ?? null;
$folio = $row_pac['folio'];
$pac_fecing = $row_pac['fecha'];
$pac_sexo = $row_pac['sexo'];
$pac_alergias = $row_pac['alergias'] ?? 'No especificado';
$stmt_pac->close();

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
$p_sistolica = $row_signs['p_sistol'] ?? '';
$p_diastolica = $row_signs['p_diastol'] ?? '';
$f_resp = $row_signs['fresp'] ?? '';
$temp = $row_signs['temper'] ?? '';
$sat_oxigeno = $row_signs['satoxi'] ?? '';
$stmt_signs->close();

// Calculate total price
$total_price = 0.0;
$sql_price = "SELECT precio_total FROM ocular_examenes_gabinete WHERE id_atencion = ? AND (id_serv IS NOT NULL OR otros_gabinete IS NOT NULL)";
$stmt_price = $conexion->prepare($sql_price);
$stmt_price->bind_param("i", $id_atencion);
$stmt_price->execute();
$result_price = $stmt_price->get_result();
while ($row_price = $result_price->fetch_assoc()) {
    $total_price += $row_price['precio_total'];
}
$stmt_price->close();

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

// Compile studies list for PDF
$studies = preg_split('/[,;]/', $sol_estudios, -1, PREG_SPLIT_NO_EMPTY);
$numbered_studies = [];
foreach ($studies as $index => $study) {
    $numbered_studies[] = ($index + 1) . ". " . trim($study);
}
$studies_list = implode("\n", $numbered_studies);

// Current date and time for PDF
$fecha_actual = date("d/m/Y H:i:s");

// Create PDF class
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

=======
$p_sistolica = $row_signs['presion_sistolica'] ?? '';
$p_diastolica = $row_signs['presion_diastolica'] ?? '';
$f_resp = $row_signs['frecuencia_respiratoria'] ?? '';
$temp = $row_signs['temperatura'] ?? '';
$sat_oxigeno = $row_signs['spo2'] ?? '';
$stmt_signs->close();

// Fetch all services with their prices from cat_servicios
$sql_prices = "SELECT id_serv, serv_desc, serv_costo FROM cat_servicios WHERE serv_activo = 'SI'";
$result_prices = $conexion->query($sql_prices);
if (!$result_prices) {
    error_log("Failed to fetch services: " . $conexion->error);
    die("Error al cargar servicios.");
}
$prices = [];
$service_names = [];
while ($row = $result_prices->fetch_assoc()) {
    $prices[trim($row['serv_desc'])] = $row['serv_costo'];
    $service_names[$row['id_serv']] = $row['serv_desc'];
}

// Calculate total price based on sol_estudios
$total_price = 0.0;
$studies = preg_split('/[,;]/', $sol_estudios, -1, PREG_SPLIT_NO_EMPTY);
$valid_studies = [];
foreach ($studies as $study) {
    $study = trim($study);
    if (isset($prices[$study])) {
        $total_price += $prices[$study];
        $valid_studies[] = $study;
    } else {
        // Check if it's a custom study in ocular_examenes_gabinete
        $sql_custom = "SELECT precio_total, otros_gabinete FROM ocular_examenes_gabinete 
                       WHERE id_atencion = ? AND otros_gabinete = ?";
        $stmt_custom = $conexion->prepare($sql_custom);
        $stmt_custom->bind_param("is", $id_atencion, $study);
        $stmt_custom->execute();
        $result_custom = $stmt_custom->get_result();
        if ($row_custom = $result_custom->fetch_assoc()) {
            $total_price += $row_custom['precio_total'];
            $valid_studies[] = $study;
        }
        $stmt_custom->close();
    }
}

// Compile studies list for PDF
$numbered_studies = [];
foreach ($valid_studies as $index => $study) {
    $numbered_studies[] = ($index + 1) . ". " . $study;
}
$studies_list = implode("\n", $numbered_studies);

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

// Current date and time for PDF
$fecha_actual = date("d/m/Y H:i:s");

// Create PDF class
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

>>>>>>> 984cba820b36f13e9c30ee7913b8e5011f1d9d07
// Generate PDF
$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(1, 8, 209, 8);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, utf8_decode('SOLICITUD DE ESTUDIOS'), 0, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 5, utf8_decode("Paciente: {$folio} - {$pac_papell} {$pac_sapell} {$pac_nom_pac}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Signos vitales:"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Presión arterial: {$p_sistolica}/{$p_diastolica} mmHg                            Frecuencia: {$f_resp} Resp/min                       Temperatura: {$temp} °C                       Saturación: {$sat_oxigeno}%"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Edad: {$edad}                                          Sexo: {$pac_sexo}                                     Fecha de ingreso: {$pac_fecing}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Fecha de solicitud: {$fecha_actual}                                                               Fecha y hora de solicitud: {$fecha_actual}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Médico tratante: {$doctor}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Estudio(s) solicitado(s):"), 0, 1, 'L');
$pdf->MultiCell(0, 5, utf8_decode($studies_list), 0, 'L');
$pdf->Cell(0, 5, utf8_decode("Costo total: \${$total_price} MXN"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Detalle de estudio:"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Diagnóstico probable: Consulta médica"), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Solicita: {$medico}"), 0, 1, 'C');
$pdf->Ln(15);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, utf8_decode("_____________________"), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode("Firma"), 0, 1, 'C');

$bottom_y = $pdf->GetY() + 10;
$pdf->Line(1, $bottom_y, 209, $bottom_y);
$pdf->Line(1, 8, 1, $bottom_y);
$pdf->Line(209, 8, 209, $bottom_y);

// Output PDF to browser
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="solicitud_gab.pdf"');
$pdf->Output('I', 'solicitud_gab.pdf');
exit();
<<<<<<< HEAD
?>                             Fecha y hora de solicitud: {$fecha_actual}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Médico tratante: {$doctor}"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Estudio(s) solicitado(s):"), 0, 1, 'L');
$pdf->MultiCell(0, 5, utf8_decode($studies_list), 0, 'L');
$pdf->Cell(0, 5, utf8_decode("Costo total: \${$total_price} MXN"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Detalle de estudio:"), 0, 1, 'L');
$pdf->Cell(0, 5, utf8_decode("Diagnóstico probable: Consulta médica"), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Solicita: {$medico}"), 0, 1, 'C');
$pdf->Ln(15);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 5, utf8_decode("_____________________"), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode("Firma"), 0, 1, 'C');

$bottom_y = $pdf->GetY() + 10;
$pdf->Line(1, $bottom_y, 209, $bottom_y);
$pdf->Line(1, 8, 1, $bottom_y);
$pdf->Line(209, 8, 209, $bottom_y);

// Output PDF to browser
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="solicitud_gab.pdf"');
$pdf->Output('I', 'solicitud_gab.pdf');
exit();
=======
>>>>>>> 984cba820b36f13e9c30ee7913b8e5011f1d9d07
?>