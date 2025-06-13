<?php


use PDF as GlobalPDF;

require '../../fpdf/fpdf.php';
include '../../conexionbd.php';


$id = @$_GET['id'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

$sql_pac = "SELECT p.papell, p.nom_pac, p.sapell, p.edad, p.sexo, p.Id_exp, p.folio, p.dir, p.id_edo, p.id_mun, p.tel, p.ocup, p.resp, p.paren, p.tel_resp, p.fecnac 
            FROM paciente p WHERE p.Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);
$row_pac = $result_pac->fetch_assoc();

$papell = $row_pac['papell'];
$nom_pac = $row_pac['nom_pac'];
$sapell = $row_pac['sapell'];
$edad = $row_pac['edad'];
$sexo = $row_pac['sexo'];
$Id_exp = $row_pac['Id_exp'];
$folio = $row_pac['folio'];
$dir = $row_pac['dir'];
$tel = $row_pac['tel'];
$ocup = $row_pac['ocup'];
$fecnac = $row_pac['fecnac'];

$sql_preop = "SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion";
$result_preop = $conexion->query($sql_preop);
$row_preop = $result_preop->fetch_assoc();

$tipo_a = $row_preop['tipo_a'] ?? '';
$fecha_ing = $row_preop['fecha'] ?? '';
$id_usua = $row_preop['id_usua'] ?? '';

$id_receta = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_receta > 0) {
    $sql_receta = "SELECT * FROM receta_lentes_contacto WHERE id = $id_receta LIMIT 1";
} else {
    $sql_receta = "SELECT * FROM receta_lentes_contacto WHERE id_atencion = $id_atencion ORDER BY id DESC LIMIT 1";
}

$result_receta = $conexion->query($sql_receta);
$row_receta = $result_receta->fetch_assoc();

if (!$row_receta) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "NO EXISTE LENTES DE CONTACTO PARA ESTE PACIENTE", 
                type: "error",
                confirmButtonText: "ACEPTAR"
            }, function(isConfirm) { 
                if (isConfirm) {
                    window.close();
                }
            });
        });
    </script>';
    exit;
}

$esf_lejana_od = $row_receta['esf_lejana_od'] ?? '';
$cil_lejana_od = $row_receta['cil_lejana_od'] ?? '';
$eje_lejana_od = $row_receta['eje_lejana_od'] ?? '';
$add_lejana_od = $row_receta['add_lejana_od'] ?? '';
$dip_lejana_od = $row_receta['dip_lejana_od'] ?? '';
$prisma_lejana_od = !empty($row_receta['prisma_lejana_od']) ? utf8_decode('Sí') : 'No';

$esf_lejana_oi = $row_receta['esf_lejana_oi'] ?? '';
$cil_lejana_oi = $row_receta['cil_lejana_oi'] ?? '';
$eje_lejana_oi = $row_receta['eje_lejana_oi'] ?? '';
$add_lejana_oi = $row_receta['add_lejana_oi'] ?? '';
$dip_lejana_oi = $row_receta['dip_lejana_oi'] ?? '';
$prisma_lejana_oi = !empty($row_receta['prisma_lejana_oi']) ? utf8_decode('Sí') : 'No';

$esf_intermedia_od = $row_receta['esf_intermedia_od'] ?? '';
$cil_intermedia_od = $row_receta['cil_intermedia_od'] ?? '';
$eje_intermedia_od = $row_receta['eje_intermedia_od'] ?? '';
$dip_intermedia_od = $row_receta['dip_intermedia_od'] ?? '';

$esf_intermedia_oi = $row_receta['esf_intermedia_oi'] ?? '';
$cil_intermedia_oi = $row_receta['cil_intermedia_oi'] ?? '';
$eje_intermedia_oi = $row_receta['eje_intermedia_oi'] ?? '';
$dip_intermedia_oi = $row_receta['dip_intermedia_oi'] ?? '';

$esf_cercana_od = $row_receta['esf_cercana_od'] ?? '';
$cil_cercana_od = $row_receta['cil_cercana_od'] ?? '';
$eje_cercana_od = $row_receta['eje_cercana_od'] ?? '';
$dip_cercana_od = $row_receta['dip_cercana_od'] ?? '';
$prisma_cercana_od = !empty($row_receta['prisma_cercana_od']) ? utf8_decode('Sí') : 'No';

$esf_cercana_oi = $row_receta['esf_cercana_oi'] ?? '';
$cil_cercana_oi = $row_receta['cil_cercana_oi'] ?? '';
$eje_cercana_oi = $row_receta['eje_cercana_oi'] ?? '';
$dip_cercana_oi = $row_receta['dip_cercana_oi'] ?? '';
$prisma_cercana_oi = !empty($row_receta['prisma_cercana_oi']) ? utf8_decode('Sí') : 'No';

$tipo_lente_od = $row_receta['tipo_lente_od'] ?? '';
$tipo_lente_oi = $row_receta['tipo_lente_oi'] ?? '';
$observaciones = $row_receta['observaciones'] ?? '';
$fecha_anteojo = $row_hist['fecha'] ?? date('Y-m-d H:i:s');
$GLOBALS['fecha_anteojo'] = $fecha_anteojo;

$sql_med = "SELECT * FROM reg_usuarios WHERE id_usua = $id_usua";
$result_med = $conexion->query($sql_med);
$row_med = $result_med->fetch_assoc();
$nom_med = $row_med['nombre'] ?? '';
$app_med = $row_med['papell'] ?? '';
$apm_med = $row_med['sapell'] ?? '';
$pre_med = $row_med['pre'] ?? '';
$firma = $row_med['firma'] ?? '';
$ced_p = $row_med['cedp'] ?? '';
$cargp = $row_med['cargp'] ?? '';

class PDF extends FPDF
{
    function Header()
    {
        include '../../conexionbd.php';
        $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
        while ($f = mysqli_fetch_array($resultado)) {
            $this->Image("../../configuracion/admin/img2/" . $f['img_ipdf'], 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/" . $f['img_cpdf'], 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/" . $f['img_dpdf'], 168, 16, 38, 14);

        }
        $this->SetY(40);
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(0, 12, utf8_decode('NOTA DE LENTES DE CONTACTO'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i', strtotime($GLOBALS['fecha_anteojo'])), 0, 1, 'R');
        $this->Ln(5);
    }
    function Footer()
    {
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

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 8, 'Datos del Paciente:', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(35, 7, 'Servicio:', 0, 0, 'L');
$pdf->Cell(55, 7, utf8_decode($tipo_a), 0, 0, 'L');
$pdf->Cell(35, 7, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 7, date('d/m/Y H:i', strtotime($fecha_ing)), 0, 1, 'L');
$pdf->Cell(35, 7, 'Paciente:', 0, 0, 'L');
$pdf->Cell(55, 7, utf8_decode($folio . ' - ' . $papell . ' ' . $sapell . ' ' . $nom_pac), 0, 0, 'L');
$pdf->Cell(35, 7, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->Cell(0, 7, utf8_decode($tel), 0, 1, 'L');

$pdf->Cell(35, 7, utf8_decode('Fecha de nacimiento:'), 0, 0, 'L');
$pdf->Cell(30, 7, date('d/m/Y', strtotime($fecnac)), 0, 0, 'L');
$pdf->Cell(10, 7, utf8_decode('Edad:'), 0, 0, 'L');
$pdf->Cell(15, 7, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(15, 7, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(20, 7, utf8_decode($sexo), 0, 0, 'L');
$pdf->Cell(20, 7, utf8_decode('Ocupación:'), 0, 0, 'L');
$pdf->Cell(0, 7, utf8_decode($ocup), 0, 1, 'L');

$pdf->Cell(20, 7, utf8_decode('Domicilio:'), 0, 0, 'L');
$pdf->Cell(0, 7, utf8_decode($dir), 0, 1, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 13);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 12, utf8_decode('RECETA DE LENTES DE CONTACTO'), 0, 1, 'C', true);
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Lentes de Contacto Suaves'), 0, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(230, 240, 255);
$pdf->SetX(25);
$pdf->Cell(35, 7, '', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Esfera', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Cilindro', 1, 0, 'C', true);
$pdf->Cell(25, 7, 'Curva Base', 1, 0, 'C', true);
$pdf->Cell(25, 7, utf8_decode('Diámetro'), 1, 1, 'C', true);

$pdf->SetX(25);
$pdf->Cell(35, 7, 'OD', 1, 0, 'C');
$pdf->Cell(30, 7, $row_receta['av_suaves_der_esf'], 1, 0, 'C');
$pdf->Cell(30, 7, $row_receta['av_suaves_der_cil'], 1, 0, 'C');
$pdf->Cell(25, 7, $row_receta['av_suaves_der_cb'], 1, 0, 'C');
$pdf->Cell(25, 7, $row_receta['av_suaves_der_diam'], 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(35, 7, 'OI', 1, 0, 'C');
$pdf->Cell(30, 7, $row_receta['av_suaves_izq_esf'], 1, 0, 'C');
$pdf->Cell(30, 7, $row_receta['av_suaves_izq_cil'], 1, 0, 'C');
$pdf->Cell(25, 7, $row_receta['av_suaves_izq_cb'], 1, 0, 'C');
$pdf->Cell(25, 7, $row_receta['av_suaves_izq_diam'], 1, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, 'Tipo OD: ' . utf8_decode($row_receta['tipo_suaves_der']) . '   |   Tipo OI: ' . utf8_decode($row_receta['tipo_suaves_izq']), 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Lentes de Contacto Duros'), 0, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(230, 240, 255);
$pdf->SetX(25);
$pdf->Cell(35, 7, '', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Esfera', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Cilindro', 1, 0, 'C', true);
$pdf->Cell(25, 7, 'Curva Base', 1, 0, 'C', true);
$pdf->Cell(25, 7, utf8_decode('Diámetro'), 1, 1, 'C', true);

$pdf->SetX(25);
$pdf->Cell(35, 7, 'OD', 1, 0, 'C');
$pdf->Cell(30, 7, $row_receta['av_duros_der_esf'], 1, 0, 'C');
$pdf->Cell(30, 7, $row_receta['av_duros_der_cil'], 1, 0, 'C');
$pdf->Cell(25, 7, $row_receta['av_duros_der_cb'], 1, 0, 'C');
$pdf->Cell(25, 7, $row_receta['av_duros_der_diam'], 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(35, 7, 'OI', 1, 0, 'C');
$pdf->Cell(30, 7, $row_receta['av_duros_izq_esf'], 1, 0, 'C');
$pdf->Cell(30, 7, $row_receta['av_duros_izq_cil'], 1, 0, 'C');
$pdf->Cell(25, 7, $row_receta['av_duros_izq_cb'], 1, 0, 'C');
$pdf->Cell(25, 7, $row_receta['av_duros_izq_diam'], 1, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, 'Tangente OD: ' . $row_receta['receta_duros_der_tangente'] . ' | Altura OD: ' . $row_receta['receta_duros_der_altura'] . ' | EL OD: ' . $row_receta['receta_duros_der_el'] . ' | OR OD: ' . $row_receta['receta_duros_der_or'], 0, 1, 'C');
$pdf->Cell(0, 7, 'Tangente OI: ' . $row_receta['receta_duros_izq_tangente'] . ' | Altura OI: ' . $row_receta['receta_duros_izq_altura'] . ' | EL OI: ' . $row_receta['receta_duros_izq_el'] . ' | OR OI: ' . $row_receta['receta_duros_izq_or'], 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('Lentes de Contacto Híbridos'), 0, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(230, 240, 255);
$pdf->SetX(45);
$pdf->Cell(60, 7, utf8_decode('AV Híbrido OD'), 1, 0, 'C', true);
$pdf->Cell(60, 7, utf8_decode('AV Híbrido OI'), 1, 1, 'C', true);

$pdf->SetX(45);
$pdf->Cell(60, 7, $row_receta['av_con_hibrido_der'], 1, 0, 'C');
$pdf->Cell(60, 7, $row_receta['av_con_hibrido_izq'], 1, 1, 'C');

$pdf->Ln(25);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 8, utf8_decode('Opciones y Marca'), 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetX(25);
$pdf->Cell(45, 7, 'Tipo de Lente', 1, 0, 'C', true);
$pdf->Cell(60, 7, 'OD', 1, 0, 'C', true);
$pdf->Cell(60, 7, 'OI', 1, 1, 'C', true);

$pdf->SetX(25);
$pdf->Cell(45, 7, 'Opciones', 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['opciones_od']), 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['opciones_oi']), 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(45, 7, 'Marca', 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['marca_od']), 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['marca_oi']), 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(45, 7, 'DK', 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['dk_od']), 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['dk_oi']), 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(45, 7, 'AV', 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['av_od']), 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['av_oi']), 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(45, 7, 'Tangente', 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['receta_duros_der_tangente']), 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['receta_duros_izq_tangente']), 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(45, 7, 'Altura', 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['receta_duros_der_altura']), 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['receta_duros_izq_altura']), 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(45, 7, 'EL', 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['receta_duros_der_el']), 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['receta_duros_izq_el']), 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(45, 7, 'OR', 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['receta_duros_der_or']), 1, 0, 'C');
$pdf->Cell(60, 7, utf8_decode($row_receta['receta_duros_izq_or']), 1, 1, 'C');

$pdf->SetX(25);
$pdf->Cell(45, 7, '', 0, 0, 'C');
$pdf->Cell(60, 7, 'Tipo OD: ' . utf8_decode($row_receta['tipo_suaves_der']), 1, 0, 'C');
$pdf->Cell(60, 7, 'Tipo OI: ' . utf8_decode($row_receta['tipo_suaves_izq']), 1, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 8, utf8_decode('Detalle de Contactología'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 7, utf8_decode($row_receta['detalle_contacto']), 1, 'J', false);


$pdf->Ln(12);
if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 40;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(22);
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, utf8_decode($cargp), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Céd. Prof. ' . $ced_p), 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode('Nombre y firma del médico'), 0, 1, 'C');

$pdf->Output();