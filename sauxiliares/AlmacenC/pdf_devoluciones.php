<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
include '../../conn_almacen/Connection.php';
mysqli_set_charset($conexion_almacen, "utf8");

date_default_timezone_set('America/Guatemala');

class PDF extends FPDF
{
    function Header()
    {
    $id = @$_GET['id'];
    $id_med = @$_GET['id_med'];
    include '../../conexionbd.php';

    $id = @$_GET['id'];
include '../../conexionbd.php';
    $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
    while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 7, 8, 59, 30);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],120,15, 109, 24);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 290, 16, 50, 20);
}

$this->Ln(33);
    $this->SetDrawColor(43, 45, 180);
   
   

     $this->SetFont('Arial', 'B', 10);
    $this->SetX(98);
    $this->Cell(145, 10, utf8_decode('DEVOLUCIONES DE ALMACÉN CENTRAL'), 1, 0, 'C');
    $this->SetFont('Arial', '', 10);
    $this->SetX(300);
    $fecha_actual = date("Y-m-d H:i");
    $date1=date_create($fecha_actual);

    $this->Cell(35, 10, 'FECHA: ' . date_format($date1,"d/m/Y H:i"), 0, 1, 'R');
   
        $this->Ln(4);  
        $this->SetFont('Arial', '', 8);
        $this->Cell(12, 5, utf8_decode('Código'), 1, 0, 'C');
        $this->Cell(125, 5, utf8_decode('Descripción'), 1, 0, 'C');
        $this->Cell(20, 5, utf8_decode('Presentación'), 1, 0, 'C');
        $this->Cell(17, 5, utf8_decode('Fecha'), 1, 0, 'C');
        $this->Cell(15, 5, utf8_decode('Cantidad'), 1, 0, 'C');
        $this->Cell(17, 5, utf8_decode('Almacen'), 1, 0, 'C');
        $this->Cell(25, 5, utf8_decode('Lote'), 1, 0, 'C');
        $this->Cell(17, 5, utf8_decode('Caducidad'), 1, 0, 'C');
        $this->Cell(80, 5, utf8_decode('Motivo'), 1, 0, 'C');
        $this->Ln(5);
    }
    function Footer()
    {
        $this->Ln(8);
        $this->SetFont('Arial', '', 7);
        $this->SetY(-15);
      
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('CMSI-33'), 0, 1, 'R');
    }
}

$pdf = new PDF('L','mm','legal');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetMargins(10, 10, 10);
#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true,20); 

$pdf->SetTextColor(43, 45, 127);
$pdf->SetDrawColor(43, 45, 180);

$sql = "SELECT * FROM item_almacen, devolucion_almacen, item_type where item_type.item_type_id=item_almacen.item_type_id and item_almacen.item_id = devolucion_almacen.item_id and devolucion_almacen.stock_qty != 0";
$result = $conexion_almacen->query($sql);
//while ($row = $result->fetch_assoc()) {

$rowcount = mysqli_num_rows($result);
if ($rowcount != 0) {
    while ($row_tabla = $result->fetch_assoc()) {
        $added=date_create($row_tabla['stock_added']);

        $pdf->Cell(12, 5, utf8_decode($row_tabla['item_code']), 1, 0, 'L');
        $pdf->Cell(125, 5, utf8_decode($row_tabla['item_name'].', '.$row_tabla['item_grams']), 1, 0, 'L');
        $pdf->Cell(20, 5, utf8_decode($row_tabla['item_type_desc']), 1, 0, 'L');
        $pdf->Cell(17, 5, date_format($added,"d/m/Y"), 1, 0, 'L');
        $pdf->Cell(15, 5, utf8_decode($row_tabla['stock_qty']), 1, 0, 'L');
        $pdf->Cell(17, 5, utf8_decode($row_tabla['almacen']), 1, 0, 'L');
        $pdf->Cell(25, 5, utf8_decode($row_tabla['stock_lote']), 1, 0, 'L');
        $pdf->Cell(17, 5, utf8_decode($row_tabla['stock_expiry']), 1, 0, 'L');
        $pdf->Cell(80, 5, utf8_decode($row_tabla['motivo']), 1, 0, 'L');
        $pdf->Ln(5);
    }
} else {


}
//}
$pdf->Ln(5);
$pdf->SetX(30);



$pdf->Output();
