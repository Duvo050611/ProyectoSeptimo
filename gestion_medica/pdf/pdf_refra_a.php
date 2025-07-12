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
$fecnac = $row_pac['fecnac'];

$sql_preop = "SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion";
$result_preop = $conexion->query($sql_preop);
$row_preop = $result_preop->fetch_assoc();

$tipo_a = $row_preop['tipo_a'] ?? '';
$fecha_ing = $row_preop['fecha'] ?? '';
$id_usua = $row_preop['id_usua'] ?? '';
$sql_refra = "SELECT * FROM refraccion_antigua WHERE id = $id AND id_atencion = $id_atencion LIMIT 1";
$result_refra = $conexion->query($sql_refra);
$row_refra = $result_refra->fetch_assoc();

if (!$row_refra) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "NO EXISTE REFRACCION ANTIGUA PARA ESTE PACIENTE", 
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

$tipo_derecho = $row_refra['tipo_derecho'] ?? '';
$av_lejana_derecho = $row_refra['av_lejana_derecho'] ?? '';
$av_lejana_lentes_derecho = $row_refra['av_lejana_lentes_derecho'] ?? '';
$esf_lejana_derecho = $row_refra['esf_lejana_derecho'] ?? '';
$cil_lejana_derecho = $row_refra['cil_lejana_derecho'] ?? '';
$eje_lejana_derecho = $row_refra['eje_lejana_derecho'] ?? '';
$add_lejana_derecho = $row_refra['add_lejana_derecho'] ?? '';
$prisma_lejana_derecho = $row_refra['prisma_lejana_derecho'] ?? 0;
$av_cercana_derecho = $row_refra['av_cercana_derecho'] ?? '';
$esf_cercana_derecho = $row_refra['esf_cercana_derecho'] ?? '';
$cil_cercana_derecho = $row_refra['cil_cercana_derecho'] ?? '';
$eje_cercana_derecho = $row_refra['eje_cercana_derecho'] ?? '';
$add_cercana_derecho = $row_refra['add_cercana_derecho'] ?? '';
$prisma_cercana_derecho = $row_refra['prisma_cercana_derecho'] ?? 0;
$detalle_refra_ojo_dere = $row_refra['detalle_refra_ojo_dere'] ?? '';

$tipo_izquierdo = $row_refra['tipo_izquierdo'] ?? '';
$av_lejana_izquierdo = $row_refra['av_lejana_izquierdo'] ?? '';
$av_lejana_lentes_izquierdo = $row_refra['av_lejana_lentes_izquierdo'] ?? '';
$esf_lejana_izquierdo = $row_refra['esf_lejana_izquierdo'] ?? '';
$cil_lejana_izquierdo = $row_refra['cil_lejana_izquierdo'] ?? '';
$eje_lejana_izquierdo = $row_refra['eje_lejana_izquierdo'] ?? '';
$add_lejana_izquierdo = $row_refra['add_lejana_izquierdo'] ?? '';
$prisma_lejana_izquierdo = $row_refra['prisma_lejana_izquierdo'] ?? 0;
$av_cercana_izquierdo = $row_refra['av_cercana_izquierdo'] ?? '';
$esf_cercana_izquierdo = $row_refra['esf_cercana_izquierdo'] ?? '';
$cil_cercana_izquierdo = $row_refra['cil_cercana_izquierdo'] ?? '';
$eje_cercana_izquierdo = $row_refra['eje_cercana_izquierdo'] ?? '';
$add_cercana_izquierdo = $row_refra['add_cercana_izquierdo'] ?? '';
$prisma_cercana_izquierdo = $row_refra['prisma_cercana_izquierdo'] ?? 0;
$detalle_refra_ojo_izq = $row_refra['detalle_refra_ojo_izq'] ?? '';
$fecha_re_a = $row_refra['fecha'] ?? date('Y-m-d H:i:s');
$GLOBALS['fecha_re_a'] = $fecha_re_a;
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
        $this->Cell(0, 12, utf8_decode('NOTA DE REFRACCION ANTIGUA'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i', strtotime($GLOBALS['fecha_re_a'])), 0, 1, 'R');
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
$pdf->SetMargins(15,15,15);
#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true,32); 

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 240, 255);
$pdf->Cell(0, 6, 'Datos del Paciente:', 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 8);
$pdf->SetFillColor(255,255,255);
$pdf->Cell(35, 5, 'Servicio:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($tipo_a), 0, 0, 'L');
$pdf->Cell(35, 5, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 5, date('d/m/Y H:i', strtotime($fecha_ing)), 0, 1, 'L');
$pdf->Cell(35, 5, 'Paciente:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($folio . ' - ' . $papell . ' ' . $sapell . ' ' . $nom_pac), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($tel), 0, 1, 'L');

$pdf->Cell(35, 5, utf8_decode('Fecha de nacimiento:'), 0, 0, 'L');
$pdf->Cell(30, 5, date('d/m/Y', strtotime($fecnac)), 0, 0, 'L');
$pdf->Cell(10, 5, utf8_decode('Edad:'), 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(15, 5, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($sexo), 0, 0, 'L');
$pdf->Ln(5);

$pdf->Cell(20, 5, utf8_decode('Domicilio:'), 0, 0, 'L');
$pdf->Cell(0, 5, utf8_decode($dir), 0, 1, 'L');

$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 7, utf8_decode('Refracción Antigua'), 0, 1, 'C', true);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(40, 8, '', 1, 0, 'C', true);
$pdf->Cell(70, 8, utf8_decode('Ojo Derecho'), 1, 0, 'C', true);
$pdf->Cell(70, 8, utf8_decode('Ojo Izquierdo'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 8, utf8_decode('Tipo'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($tipo_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($tipo_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('AV Lejana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($av_lejana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($av_lejana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('AV Lejana Lentes'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($av_lejana_lentes_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($av_lejana_lentes_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('Esf. Lejana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($esf_lejana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($esf_lejana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('Cil. Lejana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($cil_lejana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($cil_lejana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('Eje Lejana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($eje_lejana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($eje_lejana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('ADD Lejana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($add_lejana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($add_lejana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('Prisma Lejana'), 1, 0, 'L');
$pdf->Cell(70, 8, $prisma_lejana_derecho ? utf8_decode('Sí') : utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(70, 8, $prisma_lejana_izquierdo ? utf8_decode('Sí') : utf8_decode('No'), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('AV Cercana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($av_cercana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($av_cercana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('Esf. Cercana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($esf_cercana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($esf_cercana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('Cil. Cercana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($cil_cercana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($cil_cercana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('Eje Cercana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($eje_cercana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($eje_cercana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('ADD Cercana'), 1, 0, 'L');
$pdf->Cell(70, 8, utf8_decode($add_cercana_derecho), 1, 0, 'C');
$pdf->Cell(70, 8, utf8_decode($add_cercana_izquierdo), 1, 1, 'C');

$pdf->Cell(40, 8, utf8_decode('Prisma Cercana'), 1, 0, 'L');
$pdf->Cell(70, 8, $prisma_cercana_derecho ? utf8_decode('Sí') : utf8_decode('No'), 1, 0, 'C');
$pdf->Cell(70, 8, $prisma_cercana_izquierdo ? utf8_decode('Sí') : utf8_decode('No'), 1, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(95, 8, utf8_decode('Ojo Derecho'), 1, 0, 'C', true);
$pdf->Cell(95, 8, utf8_decode('Ojo Izquierdo'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(255,255,255);
$pdf->MultiCell(95, 40, utf8_decode($detalle_refra_ojo_dere), 1, 'J', false);
$x = $pdf->GetX();
$y = $pdf->GetY() - 40; // Regresa a la línea superior
$pdf->SetXY($x + 95, $y);
$pdf->MultiCell(95, 40, utf8_decode($detalle_refra_ojo_izq), 1, 'J', false);

$pdf->SetY(-62);
if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 30;
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(12);
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 3, utf8_decode(trim($pre_med . ' ' . $app_med . ' ' . $apm_med . ' ' . $nom_med)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 3, utf8_decode($cargp), 0, 1, 'C');
$pdf->Cell(0, 3, utf8_decode('Céd. Prof. ' . $ced_p), 0, 1, 'C');
$pdf->Cell(0, 3, utf8_decode('Nombre y firma del médico'), 0, 1, 'C');

$pdf->Output();