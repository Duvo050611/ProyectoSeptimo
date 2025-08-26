<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';

mysqli_set_charset($conexion, "utf8");
date_default_timezone_set('America/Guatemala');

class PDF extends FPDF
{
function Header()
  {

    include '../../conexionbd.php';
    $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
    while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 7, 8, 59, 30);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],100,15, 109, 24);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 235, 16, 50, 20);
}

$this->Ln(33);
    $this->SetTextColor(43, 45, 127);
    
    $this->SetDrawColor(43, 45, 180);
 
   
  
  
    $this->SetFont('Arial', 'B', 10);
    $this->SetX(80);
    $this->Cell(145, 10, utf8_decode('REPORTE DE EXISTENCIAS QUIRÓFANO (GLOBAL)'), 1, 0, 'C');
    $this->SetFont('Arial', '', 10);
    $this->SetX(251);
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
    $this->Cell(0, 10, utf8_decode('CMSI-39'), 0, 1, 'R');
  }
}

$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTextColor(43, 45, 127);
$pdf->SetDrawColor(43, 45, 180);

$fecha_actual = date("Y-m-d H:i");
$date1=date_create($fecha_actual);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(12, 6, utf8_decode('Código'), 1, 0, 'C');
$pdf->Cell(120, 6, utf8_decode('Descripción'), 1, 0, 'C');
$pdf->Cell(20, 6, utf8_decode('Presentación'), 1, 0, 'C');
$pdf->Cell(20, 6, utf8_decode('Inicial'), 1, 0, 'C');
$pdf->Cell(20, 6, utf8_decode('Entradas'), 1, 0, 'C');
$pdf->Cell(20, 6, utf8_decode('Salidas'), 1, 0, 'C');
$pdf->Cell(20, 6, utf8_decode('Devoluciones'), 1, 0, 'C');
$pdf->Cell(20, 6, utf8_decode('Existencias'), 1, 0, 'C');
$pdf->Cell(25, 6, utf8_decode('Actualización'), 1, 0, 'C');
$pdf->Ln(6);

$resultado2 = $conexion->query("SELECT * FROM material_ceye m, stock_ceye s, item_type i 
  where i.item_type_id=m.material_tipo and m.material_id = s.item_id  order by m.material_id") or die($conexion->error);

 while ($row_tabla = $resultado2->fetch_assoc()) {
      $actualiza=date_create($row_tabla['stock_added']); 
      $existencias=$row_tabla['stock_qty'];
            
      $pdf->SetFont('Arial', '', 8);
  
      $pdf->Cell(12, 6, utf8_decode($row_tabla['material_codigo']), 1, 0, 'L');
      $pdf->Cell(120, 6, utf8_decode($row_tabla['material_nombre'].', '.$row_tabla['material_contenido']), 1, 0, 'L');
      $pdf->Cell(20, 6, utf8_decode($row_tabla['item_type_desc']), 1, 0, 'L');
      $pdf->Cell(20, 6, utf8_decode($row_tabla['stock_inicial']), 1, 0, 'R');
      $pdf->Cell(20, 6, utf8_decode($row_tabla['stock_entradas']), 1, 0, 'R');
      $pdf->Cell(20, 6, utf8_decode($row_tabla['stock_salidas']), 1, 0, 'R');
      $pdf->Cell(20, 6, utf8_decode($row_tabla['stock_devoluciones']), 1, 0, 'R');
      $pdf->Cell(20, 6, utf8_decode($row_tabla['stock_qty']), 1, 0, 'R');
      $pdf->Cell(25, 6, date_format($actualiza,"d/m/Y H:i"), 1, 0, 'C');
      $pdf->Ln(6);
    }
  

$pdf->Ln(8);
$pdf->SetX(30);


$pdf->Output();

