<?php

use PDF as GlobalPDF;

require '../../fpdf/fpdf.php';
include '../../conexionbd.php';

mysqli_set_charset($conexion, "utf8");
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

class PDF extends FPDF
{
  function Header()
  {

    include '../../conexionbd.php';


       include '../../conexionbd.php';
    $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
    while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 7, 8, 55, 25);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],95,15, 109, 24);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 237, 16, 50, 20);
}

$this->Ln(33);
    $this->SetTextColor(43, 45, 127);
 
    $this->SetDrawColor(43, 45, 180);
   
   

    $this->SetFont('Arial', 'B', 10);
    $this->SetX(80);
    $this->Cell(145, 10, utf8_decode('EXISTENCIAS DE FARMACIA (DETALLE)'), 1, 0, 'C');
    $this->SetFont('Arial', '', 10);
    $this->SetX(250);
    $fecha_actual = date("Y-m-d H:i");
    $date1=date_create($fecha_actual);

    $this->Cell(35, 10, 'FECHA: ' . date_format($date1,"d/m/Y H:i"), 0, 1, 'R');
    $this->Ln(6);
  }
  function Footer()
  {
    $this->SetFont('Arial', 'B', 8);
    $this->SetY(-25);

    $this->Ln(8);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('CMSI-37'), 0, 1, 'R');
  }
}

$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTextColor(43, 45, 127);
$pdf->SetDrawColor(43, 45, 180);

$sql = "SELECT * FROM item, stock, item_type where item_type.item_type_id=item.item_type_id and item.item_id = stock.item_id";
$result = $conexion->query($sql);
while ($row = $result->fetch_assoc()) {
  $pdf->SetTextColor(43, 45, 127);
  $item_name = $row['item_name'];
  $item_grams = $row['item_grams'];
  $stock_final = $row['stock_qty'];
  $item_id = $row['item_id'];
  $item_code = $row['item_code'];
  $pdf->SetFont('Arial', 'B', 8);

  $pdf->Cell(123, 5, utf8_decode($item_code .'-' .$item_name .', '. $item_grams), 1, 0, 'L');
  $pdf->Cell(29, 5, utf8_decode('Inicial: ' . $row['stock_inicial']), 1, 0, 'L'); 
  $pdf->Cell(31, 5, utf8_decode('Entradas: ' . $row['stock_entradas']), 1, 0, 'L');
  $pdf->Cell(31, 5, utf8_decode('Salidas: ' . $row['stock_salidas']), 1, 0, 'L');
  $pdf->Cell(32, 5, utf8_decode('Devoluciones: ' . $row['stock_devoluciones']), 1, 0, 'L');
  $pdf->Cell(35, 5, utf8_decode('Existencias: ' . $row['stock_qty']), 1, 0, 'L');
  $pdf->Ln(5);
  $pdf->SetFont('Arial', '', 8);

  $pdf->Cell(25, 5, utf8_decode('Fecha'), 1, 0, 'C');
  $pdf->Cell(37, 5, utf8_decode('Movimiento'), 1, 0, 'C');
  $pdf->Cell(23, 5, utf8_decode('Cantidad'), 1, 0, '');
  $pdf->Cell(196, 5,utf8_decode('Motivo de devolución'), 1, 0, 'C');
 
  $pdf->Ln(5);

  $sql2 = " SELECT * FROM entradas_farmacia where item_id = $item_id ORDER BY entrada_added ASC";
  $result2 = $conexion->query($sql2);
  while ($row2 = $result2->fetch_assoc()) {
    $fecent=date_create($row2['entrada_added']);
    $pdf->Cell(25, 5, date_format($fecent,"d/m/Y H:i"), 1, 0, 'L');
    $pdf->SetTextColor(32, 92, 61);
    $pdf->Cell(37, 5, utf8_decode('ENTRADA'), 1, 0, 'L');
    $pdf->SetTextColor(43, 45, 127);
    $pdf->Cell(23, 5, utf8_decode($row2['entrada_qty']), 1, 0, 'R');
    $pdf->Ln(5);
  }
 
  $sql3 = "SELECT * FROM sales where item_id = '$item_id' ORDER BY date_sold ASC";
  // $pdf->Cell(60, 8, utf8_decode('Fecha: ' . $sql3), 1, 0, 'L');

  $result3 = $conexion->query($sql3);
  while ($row3 = $result3->fetch_assoc()) {
    $fecsal=date_create($row3['date_sold']);
    $pdf->Cell(25, 5, date_format($fecsal,"d/m/Y H:i") , 1, 0, 'L');
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(37, 5, utf8_decode('SALIDA'), 1, 0, 'L');
    $pdf->SetTextColor(43, 45, 127);
    $pdf->Cell(23, 5, utf8_decode($row3['qty']), 1, 0, 'R');
    $pdf->Ln(5);
  }

  $pdf->SetTextColor(43, 45, 127);  

  $sql4 = "SELECT * FROM devolucion df where df.dev_item = $item_id and df.dev_estatus = 'NO' 
  and df.cant_inv != 0 ORDER BY fecha ASC";
    $result4 = $conexion->query($sql4);
  while ($row4 = $result4->fetch_assoc()) {
    $fecdev=date_create($row3['fecha']);
    $pdf->Cell(25, 5, date_format($fecdev,"d/m/Y H:i"), 1, 0, 'L');
    $pdf->SetTextColor(30, 19, 245);
    $pdf->Cell(37, 5, utf8_decode('DEVOLUCIÓN'), 1, 0, 'L');
    $pdf->SetTextColor(43, 45, 127);  
    $pdf->Cell(23, 5, utf8_decode($row4['cant_inv']), 1, 0, 'R');
     $pdf->Cell(196, 5, utf8_decode('Paciente:' .' '. $row4['paciente'].', '. $row4['motivoi'] ), 1, 0, 'L');
    $pdf->Ln(5);
  }  
  $pdf->Ln(5);
}


$pdf->Output();
