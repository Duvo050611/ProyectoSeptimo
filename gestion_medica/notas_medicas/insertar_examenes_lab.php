<?php
ob_start(); // Start output buffering to prevent any output before PDF
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'];

// Fetch Id_exp from dat_ingreso
$sql_exp = "SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?";
$stmt_exp = $conexion->prepare($sql_exp);
$stmt_exp->bind_param("i", $id_atencion);
$stmt_exp->execute();
$result_exp = $stmt_exp->get_result();
$row_exp = $result_exp->fetch_assoc();
$Id_exp = $row_exp['Id_exp'];
$stmt_exp->close();

// Get the doctor's name from the session
$usuario = $_SESSION['login'];
$medico = isset($usuario['papell']) ? $usuario['papell'] : "Médico no asignado";

// If you need additional fields like first name or second last name, fetch them from the database
if (isset($usuario['id_usua'])) {
    $sql_doc = "SELECT pre, nombre, papell, sapell FROM reg_usuarios WHERE id_usua = ?";
    $stmt_doc = $conexion->prepare($sql_doc);
    $stmt_doc->bind_param("i", $usuario['id_usua']);
    $stmt_doc->execute();
    $result_doc = $stmt_doc->get_result();
    $row_doc = $result_doc->fetch_assoc();
    if ($row_doc) {
        $medico = ($row_doc['pre'] ? $row_doc['pre'] . ". " : "") . 
                  $row_doc['papell'] . " " . 
                  ($row_doc['sapell'] ? $row_doc['sapell'] . " " : "") . 
                  $row_doc['nombre'];
    }
    $stmt_doc->close();
}

// Prepare data from the form
$biometria_hematica = isset($_POST['biometria_hematica']) ? 1 : 0;
$quimica_sanguinea = isset($_POST['quimica_sanguinea']) ? 1 : 0;
$quimica_sanguinea_valores = $quimica_sanguinea ? (int)$_POST['quimica_sanguinea_valores'] : 0;
$tiempos_coagulacion = isset($_POST['tiempos_coagulacion']) ? 1 : 0;
$hemoglobina_glucosilada = isset($_POST['hemoglobina_glucosilada']) ? 1 : 0;
$examen_general_orina = isset($_POST['examen_general_orina']) ? 1 : 0;
$electrocardiograma = isset($_POST['electrocardiograma']) ? 1 : 0;
$pruebas_funcion_hepatica = isset($_POST['pruebas_funcion_hepatica']) ? 1 : 0;
$antigeno_sars_cov_2 = isset($_POST['antigeno_sars_cov_2']) ? 1 : 0;
$pcr_sars_cov_2 = isset($_POST['pcr_sars_cov_2']) ? 1 : 0;
$electroitos_sericos = isset($_POST['electroitos_sericos']) ? 1 : 0;
$pruebas_funcion_tiroidea = isset($_POST['pruebas_funcion_tiroidea']) ? 1 : 0;
$acs_anti_tiroglubolina = isset($_POST['acs_anti_tiroglubolina']) ? 1 : 0;
$acs_antireceptores_tsh = isset($_POST['acs_antireceptores_tsh']) ? 1 : 0;
$acs_antiperoxadasa = isset($_POST['acs_antiperoxadasa']) ? 1 : 0;
$velocidad_sedimentacion_globular = isset($_POST['velocidad_sedimentacion_globular']) ? 1 : 0;
$proteina_c_reactiva = isset($_POST['proteina_c_reactiva']) ? 1 : 0;
$vdrl = isset($_POST['vdrl']) ? 1 : 0;
$fta_abs = isset($_POST['fta_abs']) ? 1 : 0;
$ppd = isset($_POST['ppd']) ? 1 : 0;
$elisa_vih_1_y_2 = isset($_POST['elisa_vih_1_y_2']) ? 1 : 0;
$acs_toxoplasmosis_igg_igm = isset($_POST['acs_toxoplasmosis_igg_igm']) ? 1 : 0;
$factor_reumatoide = isset($_POST['factor_reumatoide']) ? 1 : 0;
$acs_anti_ccp = isset($_POST['acs_anti_ccp']) ? 1 : 0;
$antigeno_hla_b27 = isset($_POST['antigeno_hla_b27']) ? 1 : 0;
$acs_antinucleares = isset($_POST['acs_antinucleares']) ? 1 : 0;
$acs_anticardiolipina = isset($_POST['acs_anticardiolipina']) ? 1 : 0;
$acs_p_ancasy_c_ancas = isset($_POST['acs_p_ancasy_c_ancas']) ? 1 : 0;
$otros_laboratorio = isset($_POST['otros_laboratorio']) ? trim($_POST['otros_laboratorio']) : null;

// Insert into database
$sql = "INSERT INTO ocular_examenes_laboratorio (id_atencion, Id_exp, biometria_hematica, quimica_sanguinea, quimica_sanguinea_valores, tiempos_coagulacion, hemoglobina_glucosilada, examen_general_orina, electrocardiograma, pruebas_funcion_hepatica, antigeno_sars_cov_2, pcr_sars_cov_2, electroitos_sericos, pruebas_funcion_tiroidea, acs_anti_tiroglubolina, acs_antireceptores_tsh, acs_antiperoxadasa, velocidad_sedimentacion_globular, proteina_c_reactiva, vdrl, fta_abs, ppd, elisa_vih_1_y_2, acs_toxoplasmosis_igg_igm, factor_reumatoide, acs_anti_ccp, antigeno_hla_b27, acs_antinucleares, acs_anticardiolipina, acs_p_ancasy_c_ancas, otros_laboratorio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iiiiiiiiiiiiiiiiiiiiiiiiiiiiiis", $id_atencion, $Id_exp, $biometria_hematica, $quimica_sanguinea, $quimica_sanguinea_valores, $tiempos_coagulacion, $hemoglobina_glucosilada, $examen_general_orina, $electrocardiograma, $pruebas_funcion_hepatica, $antigeno_sars_cov_2, $pcr_sars_cov_2, $electroitos_sericos, $pruebas_funcion_tiroidea, $acs_anti_tiroglubolina, $acs_antireceptores_tsh, $acs_antiperoxadasa, $velocidad_sedimentacion_globular, $proteina_c_reactiva, $vdrl, $fta_abs, $ppd, $elisa_vih_1_y_2, $acs_toxoplasmosis_igg_igm, $factor_reumatoide, $acs_anti_ccp, $antigeno_hla_b27, $acs_antinucleares, $acs_anticardiolipina, $acs_p_ancasy_c_ancas, $otros_laboratorio);

if ($stmt->execute()) {
    // Fetch patient data for PDF
    $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.fecnac, p.Id_exp, p.folio, di.fecha, p.sexo, di.alergias FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp AND di.id_atencion = ?";
    $stmt_pac = $conexion->prepare($sql_pac);
    $stmt_pac->bind_param("i", $id_atencion);
    $stmt_pac->execute();
    $result_pac = $stmt_pac->get_result();
    $row_pac = $result_pac->fetch_assoc();
    $pac_papell = $row_pac['papell'];
    $pac_sapell = $row_pac['sapell'];
    $pac_nom_pac = $row_pac['nom_pac'];
    $pac_fecnac = $row_pac['fecnac'];
    $folio = $row_pac['folio'];
    $pac_fecing = $row_pac['fecha'];
    $pac_sexo = $row_pac['sexo'];
    $pac_alergias = $row_pac['alergias'] ?? 'No especificado';
    $stmt_pac->close();

    // Fetch signs vitales
    $sql_signs = "SELECT p_sistol, p_diastol, fresp, temper, satoxi FROM signos_vitales WHERE id_atencion = ? ORDER BY id_sig DESC LIMIT 1";
    $stmt_signs = $conexion->prepare($sql_signs);
    $stmt_signs->bind_param("i", $id_atencion);
    $stmt_signs->execute();
    $result_signs = $stmt_signs->get_result();
    $row_signs = $result_signs->fetch_assoc();
    $p_sistolica = $row_signs['p_sistol'] ?? '';
    $p_diastolica = $row_signs['p_diastol'] ?? '';
    $f_resp = $row_signs['fresp'] ?? '';
    $temp = $row_signs['temper'] ?? '';
    $sat_oxigeno = $row_signs['satoxi'] ?? '';
    $stmt_signs->close();

    // Calculate age
    function calculaedad($fechanacimiento) {
        list($ano, $mes, $dia) = explode("-", $fechanacimiento);
        $ano_diferencia = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia = date("d") - $dia;
        if ($ano_diferencia > 0) {
            return $ano_diferencia . ' AÑOS';
        } else if ($mes_diferencia > 0 || $ano_diferencia < 0) {
            return $mes_diferencia . ' MESES';
        } else if ($dia_diferencia > 0 || $mes_diferencia < 0 || $ano_diferencia < 0) {
            return $dia_diferencia . ' DÍAS';
        }
    }
    $edad = calculaedad($pac_fecnac);

    // Compile studies list for PDF
    $studies = [];
    if ($biometria_hematica) $studies[] = "Biometría Hemática";
    if ($quimica_sanguinea) $studies[] = "Química Sanguínea ($quimica_sanguinea_valores elementos)";
    if ($tiempos_coagulacion) $studies[] = "Tiempos de Coagulación (TP/TT)";
    if ($hemoglobina_glucosilada) $studies[] = "Hemoglobina Glucosilada";
    if ($examen_general_orina) $studies[] = "Examen General de Orina";
    if ($electrocardiograma) $studies[] = "Electrocardiograma";
    if ($pruebas_funcion_hepatica) $studies[] = "Pruebas de Función Hepática";
    if ($antigeno_sars_cov_2) $studies[] = "Antígeno SARS-CoV-2";
    if ($pcr_sars_cov_2) $studies[] = "PCR SARS-CoV-2";
    if ($electroitos_sericos) $studies[] = "Electrolitos Séricos";
    if ($pruebas_funcion_tiroidea) $studies[] = "Pruebas de Función Tiroidea";
    if ($acs_anti_tiroglubolina) $studies[] = "AC'S Anti-Tiroglubolina";
    if ($acs_antireceptores_tsh) $studies[] = "ACS Anti-Receptores TSH";
    if ($acs_antiperoxadasa) $studies[] = "ACS Antiperoxadasa";
    if ($velocidad_sedimentacion_globular) $studies[] = "Velocidad de Sedimentación Globular";
    if ($proteina_c_reactiva) $studies[] = "Proteína C Reactiva";
    if ($vdrl) $studies[] = "VDRL";
    if ($fta_abs) $studies[] = "FTA-ABS";
    if ($ppd) $studies[] = "PPD";
    if ($elisa_vih_1_y_2) $studies[] = "ELISA VIH 1 y 2";
    if ($acs_toxoplasmosis_igg_igm) $studies[] = "ACS Toxoplasmosis IgG/IgM";
    if ($factor_reumatoide) $studies[] = "Factor Reumatoide";
    if ($acs_anti_ccp) $studies[] = "ACS Anti-CCP";
    if ($antigeno_hla_b27) $studies[] = "Antígeno HLA-B27";
    if ($acs_antinucleares) $studies[] = "ACS Antinucleares";
    if ($acs_anticardiolipina) $studies[] = "ACS Anticardiolipina";
    if ($acs_p_ancasy_c_ancas) $studies[] = "ACS P-ANCAs y C-ANCAs";
    if ($otros_laboratorio) $studies[] = $otros_laboratorio;
    $studies_list = implode("\n", $studies);

    // Current date and time
    $fecha_actual = date("d/m/Y H:i:s"); // 12:35 PM CST, May 26, 2025

    // Create PDF class
    class PDF extends FPDF {
        function Header() {
            global $folio;
            // Add logo in the top left
            if (!file_exists('../../imagenes/Izquierda-receta.JPG')) {
                die('File not found: ../../imagenes/Izquierda-receta.JPG');
            }
            $this->Image('../../imagenes/Izquierda-receta.JPG', 10, 10, 40, 0, 'JPG');

            $center_x = (float)($this->GetPageWidth() / 2 - 30); 
            $this->Image('../../imagenes/Imagen-c.jpg', $center_x, 10, 60, 0, 'JPG');
            $this->Ln(40);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', '', 8);
            $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        }
    }

    // Generate PDF
    $pdf = new PDF('P');
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, utf8_decode('SOLICITUD DE ESTUDIOS DE LABORATORIO'), 0, 1, 'C');
    $pdf->Ln(2);

    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, utf8_decode("Paciente: $folio - $pac_papell $pac_sapell $pac_nom_pac"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Signos vitales:"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Presión arterial: $p_sistolica/$p_diastolica mmHG   Frecuencia respiratoria: $f_resp Resp/min   Temperatura: $temp °C   Saturación oxígeno: $sat_oxigeno %"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Edad: $edad   Sexo: $pac_sexo   Fecha de ingreso: $pac_fecing"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Fecha de solicitud: $fecha_actual   Fecha y hora de solicitud: $fecha_actual"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Médico tratante: Cédula Prof."), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Solicitante: $medico"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Estudio(s) solicitado(s):"), 0, 1, 'L');
    $pdf->MultiCell(0, 5, utf8_decode($studies_list), 0, 'L');
    $pdf->Cell(0, 5, utf8_decode("Detalle de estudio:"), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode("Diagnóstico probable: Consulta"), 0, 1, 'L');
    $pdf->Cell(0, 10, utf8_decode("Solicita: Dr. $medico"), 0, 1, 'C');
    $pdf->Ln(20);
    
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 5, utf8_decode("_____________________"), 0, 1, 'C');
    $pdf->Cell(0, 5, utf8_decode("Página 1/1"), 0, 1, 'C');

    // Clean output buffer and send PDF
    ob_end_clean();
    $pdf->Output('D', "solicitud_estudios_$folio" . date('Ymd_His') . ".pdf");

    $stmt->close();
    $conexion->close();
    exit();
} else {
    header("Location: examenes_lab.php?error=" . urlencode($conexion->error));
    exit();
}
?>