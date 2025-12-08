<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
$id_atencion = @$_GET['id_atencion'];
$id = @$_GET['id_atencion'];
$id_ord = @$_GET['id_ord'];
$id_med = @$_GET['id_med'];
$id_exp = @$_GET['id_exp'];
mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
    private $conexion;

    // Constructor para recibir la conexión
    function __construct($orientation = 'P', $unit = 'mm', $size = 'A4', $conexion = null)
    {
        parent::__construct($orientation, $unit, $size);
        $this->conexion = $conexion;
    }

    function Header()
    {
        $id_med = @$_GET['id_med'];
        $id = @$_GET['id_atencion'];
        $id_ord = @$_GET['id_ord'];

        // Usar la conexión de la clase
        $resultado = $this->conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC") or die($this->conexion->error);
        while ($f = mysqli_fetch_array($resultado)) {
            $bas = $f['img_ipdf'];

            $this->Image("../../configuracion/admin/img2/" . $bas, 7, 8, 48, 25);
            $this->Image("../../configuracion/admin/img3/" . $f['img_cpdf'], 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/" . $f['img_dpdf'], 168, 16, 38, 14);
        }

        $this->Ln(32);
    }

    function Footer()
    {
        $this->Ln(8);
        $this->SetY(-15);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('MAC-8.01'), 0, 1, 'R');
    }
}

$sql_pac = "SELECT p.papell, p.nom_pac, p.fecnac, p.sapell, p.edad, p.sexo, p.Id_exp, p.dir, p.id_edo, p.id_mun, p.tel, p.ocup, p.resp, p.paren, p.tel_resp, p.religion, p.folio 
            FROM paciente p, dat_ingreso di 
            WHERE di.id_atencion = $id_atencion AND di.Id_exp = p.Id_exp";
$result_pac = $conexion->query($sql_pac);

$folio = $papell = $nom_pac = $fecnac = $sapell = $edad = $sexo = $Id_exp = $dir = $id_edo = $id_mun = $tel = $ocup = $resp = $paren = $tel_resp = $religion = '';

while ($row_pac = $result_pac->fetch_assoc()) {
    $folio = $row_pac['folio'];
    $papell = $row_pac['papell'];
    $nom_pac = $row_pac['nom_pac'];
    $fecnac = $row_pac['fecnac'];
    $sapell = $row_pac['sapell'];
    $edad = $row_pac['edad'];
    $sexo = $row_pac['sexo'];
    $Id_exp = $row_pac['Id_exp'];
    $dir = $row_pac['dir'];
    $id_edo = $row_pac['id_edo'];
    $id_mun = $row_pac['id_mun'];
    $tel = $row_pac['tel'];
    $ocup = $row_pac['ocup'];
    $resp = $row_pac['resp'];
    $paren = $row_pac['paren'];
    $tel_resp = $row_pac['tel_resp'];
    $religion = $row_pac['religion'];
}

$sql_dati = "SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion";
$result_dati = $conexion->query($sql_dati);

$motivo_atn = $tipo_a = $fecha_ing = '';

while ($row_dati = $result_dati->fetch_assoc()) {
    $motivo_atn = $row_dati['motivo_atn'];
    $tipo_a = $row_dati['tipo_a'];
    $fecha_ing = $row_dati['fecha'];
}

// Consulta ordenes
$sql_ormed = "SELECT * FROM dat_ordenes_med WHERE id_ord_med = $id_ord";
$result_ormed = $conexion->query($sql_ormed);

// Inicializar todas las variables
$fecha_ord = $hora_ord = $dieta = $det_dieta = $signos = $monitoreo = $diuresis = $dex = $semif = $vigilar = $oxigeno = $nebulizacion = $bar = $baño = $foley = $ej = $datsan = '';
$detsignos = $detmonitoreo = $detdiuresis = $detdex = $detsemif = $detvigilar = $detoxigeno = $detnebu = $detbar = $detbaño = $detfoley = $detej = $detsan = '';
$dialisis = $fisio = $reha = $cuid_gen = $med_med = $soluciones = $perfillab = $detalle_lab = $sol_estudios = $det_imagen = $solicitud_sang = $sol_pato = $det_pato = $det_sang = $observ_be = $gca = $gcat = $son = $sont = '';

while ($row_ormed = $result_ormed->fetch_assoc()) {
    $fecha_ord = $row_ormed['fecha_ord'];
    $hora_ord = $row_ormed['hora_ord'];
    $dieta = $row_ormed['dieta'];
    $det_dieta = $row_ormed['det_dieta'];
    $signos = $row_ormed['signos'];
    $monitoreo = $row_ormed['monitoreo'];
    $diuresis = $row_ormed['diuresis'];
    $dex = $row_ormed['dex'];
    $semif = $row_ormed['semif'];
    $vigilar = $row_ormed['vigilar'];
    $oxigeno = $row_ormed['oxigeno'];
    $nebulizacion = $row_ormed['nebulizacion'];
    $bar = $row_ormed['bar'];
    $baño = $row_ormed['baño'];
    $foley = $row_ormed['foley'];
    $ej = $row_ormed['ej'];
    $datsan = $row_ormed['datsan'];

    $detsignos = $row_ormed['detsignos'];
    $detmonitoreo = $row_ormed['detmonitoreo'];
    $detdiuresis = $row_ormed['detdiuresis'];
    $detdex = $row_ormed['detdex'];
    $detsemif = $row_ormed['detsemif'];
    $detvigilar = $row_ormed['detvigilar'];
    $detoxigeno = $row_ormed['detoxigeno'];
    $detnebu = $row_ormed['detnebu'];
    $detbar = $row_ormed['detbar'];
    $detbaño = $row_ormed['detbaño'];
    $detfoley = $row_ormed['detfoley'];
    $detej = $row_ormed['detej'];
    $detsan = $row_ormed['detsan'];

    $dialisis = $row_ormed['dialisis'];
    $fisio = $row_ormed['fisio'];
    $reha = $row_ormed['reha'];

    $cuid_gen = $row_ormed['cuid_gen'];
    $med_med = $row_ormed['med_med'];
    $soluciones = $row_ormed['soluciones'];
    $perfillab = $row_ormed['perfillab'];
    $detalle_lab = $row_ormed['detalle_lab'];
    $sol_estudios = $row_ormed['sol_estudios'];
    $det_imagen = $row_ormed['det_imagen'];
    $solicitud_sang = $row_ormed['solicitud_sang'];
    $sol_pato = $row_ormed['sol_pato'];
    $det_pato = $row_ormed['det_pato'];
    $det_sang = $row_ormed['det_sang'];
    $observ_be = $row_ormed['observ_be'];
    $gca = $row_ormed['gca'];
    $gcat = $row_ormed['gcat'];
    $son = $row_ormed['son'];
    $sont = $row_ormed['sont'];
}

// Crear PDF pasando la conexión
$pdf = new PDF('P', 'mm', 'A4', $conexion);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(8, 10, 10);
$pdf->SetAutoPageBreak(true, 22);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(30);
$pdf->SetTextColor(43, 45, 127);
$pdf->MultiCell(165, 9.5, utf8_decode('INDICACIONES MÉDICAS'), 0, 'C');

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(48, 52, 172, 52);
$pdf->Line(48, 41, 48, 52);
$pdf->Line(172, 41, 172, 52);
$pdf->Line(48, 41, 172, 41);

$pdf->SetX(175);
$pdf->SetFont('Arial', '', 8);

$fecha_ord_format = date("d/m/Y");
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, -2, utf8_decode('Fecha: ' . $fecha_ord_format . ' ' . $hora_ord), 0, 1, 'L');

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(8, 53, 207, 53);
$pdf->Line(8, 53, 8, 280);
$pdf->Line(207, 53, 205, 280);
$pdf->Line(8, 280, 205, 280);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, 'Servicio: ', 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(120, 5, utf8_decode($tipo_a), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$date = date_create($fecha_ing);
$pdf->Cell(28, 5, utf8_decode(' Fecha de Ingreso:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(32, 5, date_format($date, 'd/m/Y H:i:s'), 'B', 0, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18, 5, utf8_decode('Expediente: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(15, 5, utf8_decode($folio), 'B', 0, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(16, 5, ' Paciente: ', 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(146, 5, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$date1 = date_create($fecnac);
$pdf->Cell(31, 5, 'Fecha de nacimiento: ', 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5, date_format($date1, "d/m/Y"), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(11, 5, ' Edad: ', 0, 'L');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(13, 5, utf8_decode($edad), 'B', 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(19, 5, utf8_decode(' Ocupación: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(36, 5, utf8_decode($ocup), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(16, 5, utf8_decode(' Teléfono: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5, utf8_decode($tel), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 5, utf8_decode('Género: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(14, 5, $sexo, 'B', 'L');
$pdf->Ln(5);

$pesoh = "";
$tallah = "";
$sql_sig = "SELECT * FROM dat_hclinica WHERE Id_exp = $Id_exp ORDER BY id_hc DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);
while ($row_hc = $result_sig->fetch_assoc()) {
    $pesoh = $row_hc['peso'];
    $tallah = $row_hc['talla'];
}

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 5, utf8_decode('Peso: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(14, 5, utf8_decode($pesoh . ' Kilos'), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(11, 5, utf8_decode(' Talla: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(18, 5, utf8_decode($tallah . ' Metros'), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 5, utf8_decode(' Domicilio: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(125, 5, utf8_decode($dir), 'B', 'L');

$pdf->Ln(5);

$d = "";
$sql_motd = "SELECT diagprob_i FROM dat_not_ingreso WHERE id_atencion = $id_atencion ORDER BY id_not_ingreso DESC LIMIT 1";
$result_motd = $conexion->query($sql_motd);
while ($row_motd = $result_motd->fetch_assoc()) {
    $d = $row_motd['diagprob_i'];
}

$sql_motd2 = "SELECT diagprob_i FROM dat_nevol WHERE id_atencion = $id_atencion ORDER BY diagprob_i DESC LIMIT 1";
$result_motd2 = $conexion->query($sql_motd2);
while ($row_motd2 = $result_motd2->fetch_assoc()) {
    $d = $row_motd2['diagprob_i'];
}

$m = "";
$sql_mot = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = $id_atencion ORDER BY motivo_atn ASC LIMIT 1";
$result_mot = $conexion->query($sql_mot);
while ($row_mot = $result_mot->fetch_assoc()) {
    $m = $row_mot['motivo_atn'];
}

if ($d != null && $d != '') {
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(20, 5, utf8_decode('Diagnóstico:'), 0, 'C');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(175, 5, utf8_decode($d), 'B', 'C');
} else {
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(29, 5, utf8_decode('Motivo de atención:'), 0, 'C');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(166, 5, utf8_decode($m), 'B', 'C');
}

$p_sistolica = $p_diastolica = $f_card = $f_resp = $temp = $sat_oxigeno = $peso = $talla = $niv_dolor = '';

$sql_sig = "SELECT * FROM signos_vitales WHERE id_atencion = $id_atencion ORDER BY id_sig DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);
while ($row_sig = $result_sig->fetch_assoc()) {
    $p_sistolica = $row_sig['p_sistol'];
    $p_diastolica = $row_sig['p_diastol'];
    $f_card = $row_sig['fcard'];
    $f_resp = $row_sig['fresp'];
    $temp = $row_sig['temper'];
    $sat_oxigeno = $row_sig['satoxi'];
    $peso = $row_sig['peso'];
    $talla = $row_sig['talla'];
    $niv_dolor = $row_sig['niv_dolor'];
}

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(200, 4, utf8_decode('SIGNOS VITALES: '), 0, 0, 'L');
$pdf->Ln(4);

$pdf->SetFont('Arial', '', 7.2);
$pdf->Cell(50, 3, utf8_decode('Presión arterial: ' . $p_sistolica . '/' . $p_diastolica . ' mmHG/mmHG'), 1, 'L');
$pdf->Cell(40, 3, utf8_decode('Frecuencia cardiaca: ' . $f_card . ' Lat/min'), 1, 'L');
$pdf->Cell(45, 3, utf8_decode('Frecuencia respiratoria: ' . $f_resp . ' Resp/min'), 1, 'L');
$pdf->Cell(30, 3, utf8_decode('Temperatura: ' . $temp . '°C'), 1, 'L');
$pdf->Cell(32, 3, utf8_decode('Saturación oxígeno: ' . $sat_oxigeno . '%'), 1, 'L');

$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('1. Dieta: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($dieta . ' ' . $det_dieta), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(52, 3, utf8_decode('2. Cuidados Generales: '), 0, 'L');
$pdf->Cell(10, 3, utf8_decode('SI / NO'), 1, 'C');
$pdf->Cell(135, 3, utf8_decode('Descripción'), 1, 'C');
$pdf->Ln(3);

$pdf->SetX(12);
$pdf->SetFont('Arial', '', 7);

if ($signos == 'SI') {
    $pdf->Cell(48, 3, utf8_decode('Signos vitales por turno'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($signos), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detsignos), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($monitoreo == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Monitoreo constante'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($monitoreo), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detmonitoreo), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($diuresis == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Diuresis por turno'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($diuresis), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detdiuresis), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($dex == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Dextrostix por turno'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($dex), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detdex), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($semif == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Posición semifolwer'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($semif), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detsemif), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($vigilar == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Vigilar datos del paciente neurológico'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($vigilar), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detvigilar), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($oxigeno == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Oxígeno'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($oxigeno), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detoxigeno), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($nebulizacion == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Nebulizaciones'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($nebulizacion), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detnebu), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($bar == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Barandales en alto'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($bar), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detbar), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($baño == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Baño diario y deambulación asistida'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($baño), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detbaño), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($foley == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Cuidados sonda Foley'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($foley), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detfoley), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($ej == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Ejercicios respiratorios'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($ej), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detej), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($datsan == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Vigilar datos de sangrado'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($datsan), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($detsan), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($gca == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Glucometría capilar, reportar <80 o >180'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($gca), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($gcat), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if ($son == 'SI') {
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(48, 3, utf8_decode('Sonda nasogástrica a derivación'), 1, 'L');
    $pdf->Cell(10, 3, utf8_decode($son), 1, 0, 'C');
    $pdf->Cell(135, 3, utf8_decode($sont), 1, 'L');
    $pdf->Ln(3);
    $pdf->SetX(12);
}

if (isset($observ_be) && $observ_be != '') {
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(48, 3, utf8_decode('Otros cuidados'), 1, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(145, 3, utf8_decode($observ_be), 1, 'L');
}

$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('3. Medicamentos: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($med_med), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('4. Soluciones: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($soluciones), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('5. Estudios Laboratorio: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($perfillab . ' Detalle: ' . $detalle_lab), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('6. Estudios Imagenología: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($sol_estudios . ' Detalle: ' . $det_imagen), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('7. Estudios Patología: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($sol_pato . ' Detalle: ' . $det_pato), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(36, 3, utf8_decode('8. Solicitud Banco de Sangre: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($solicitud_sang . ' Detalle: ' . $det_sang), 1, 'L');
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('PROCEDIMIENTOS EN MEDICINA DE TRATAMIENTO: '), 0, 'L');
$pdf->Ln(3.5);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('Diálisis: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($dialisis), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('Fisioterapía: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($fisio), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('Inhaloterapia: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($cuid_gen), 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 3, utf8_decode('Rehabilitación: '), 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(161, 3, utf8_decode($reha), 1, 'L');
$pdf->Ln(1);

$pdf->Ln(30);

$sql_med_id = "SELECT id_usua FROM dat_ordenes_med WHERE id_ord_med = $id_ord";
$result_med_id = $conexion->query($sql_med_id);

$id_med_usuario = 0;
while ($row_med_id = $result_med_id->fetch_assoc()) {
    $id_med_usuario = $row_med_id['id_usua'];
}

$nom = $app = $apm = $pre = $firma = $ced_p = $cargp = '';

if ($id_med_usuario > 0) {
    $sql_med = "SELECT * FROM reg_usuarios WHERE id_usua = $id_med_usuario";
    $result_med = $conexion->query($sql_med);

    while ($row_med = $result_med->fetch_assoc()) {
        $nom = $row_med['nombre'];
        $app = $row_med['papell'];
        $apm = $row_med['sapell'];
        $pre = $row_med['pre'];
        $firma = $row_med['firma'];
        $ced_p = $row_med['cedp'];
        $cargp = $row_med['cargp'];
    }
}

$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 7);
if ($firma == null || $firma == '') {
    $pdf->Image('../../imgfirma/FIRMA.jpg', 94, 253, 24);
} else {
    $pdf->Image('../../imgfirma/' . $firma, 94, 253, 24);
}

$pdf->SetY(266);
$pdf->Cell(195, 3, utf8_decode($pre . '. ' . $app), 0, 0, 'C');
$pdf->Ln(3);

$pdf->Cell(195, 3, utf8_decode($cargp . ' ' . 'Cédula Profesional: ' . $ced_p), 0, 0, 'C');
$pdf->Ln(3);
$pdf->Cell(195, 3, utf8_decode('Nombre y Firma del Médico'), 0, 0, 'C');

$pdf->Output();
?>