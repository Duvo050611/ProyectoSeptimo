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
  
    $this->SetFont('Arial', 'B', 10);
    $this->SetX(98);
    $this->Cell(145, 10, utf8_decode('EXISTENCIAS DE ALMACÉN CENTRAL (GLOBAL)'), 1, 0, 'C');
    $this->SetFont('Arial', '', 10);
    $this->SetX(300);
    $fecha_actual = date("Y-m-d H:i");
    $date1=date_create($fecha_actual);

    $this->Cell(35, 10, 'FECHA: ' . date_format($date1,"d/m/Y H:i"), 0, 1, 'R');
    $this->Ln(6);
   
  }
     function Footer()
  {
    $this->Ln(8);
    $this->SetFont('Arial', '', 7);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('CMSI-31'), 0, 1, 'R');
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

$pdf->SetFont('Arial', 'B', 10);

$fecha_actual = date("Y-m-d H:i");
$date1=date_create($fecha_actual);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(12, 8, utf8_decode('Código'), 1, 0, 'C');
$pdf->Cell(115, 8, utf8_decode('Descripción'), 1, 0, 'C');
$pdf->Cell(20, 8, utf8_decode('Presentación'), 1, 0, 'C');
$pdf->Cell(30, 8, utf8_decode('Lote'), 1, 0, 'C');
$pdf->Cell(17, 8, utf8_decode('Caducidad'), 1, 0, 'C');
$pdf->Cell(22, 8, utf8_decode('Entradas'), 1, 0, 'C');
$pdf->Cell(22, 8, utf8_decode('Salidas'), 1, 0, 'C');
$pdf->Cell(22, 8, utf8_decode('Devoluciones'), 1, 0, 'C');
$pdf->Cell(22, 8, utf8_decode('Existencias'), 1, 0, 'C');
$pdf->Cell(14, 8, utf8_decode('Mínimo'), 1, 0, 'C');
$pdf->Cell(14, 8, utf8_decode('Máximo'), 1, 0, 'C');
$pdf->Cell(20, 8, utf8_decode('Actualización'), 1, 0, 'C');
$pdf->Cell(10, 8, utf8_decode('Activo'), 1, 0, 'C');
$pdf->Ln(8);

$resultado2 = $conexion_almacen->query("SELECT * FROM item_almacen, stock_almacen, item_type where item_type.item_type_id=item_almacen.item_type_id and item_almacen.item_id = stock_almacen.item_id  order by item_almacen.item_id") or die($conexion->error);
/*
$sql = "SELECT s.stock_id, i.item_id, i.item_name, i.item_price, s.stock_inicial, s.stock_entradas, s.stock_salidas, s.stock_qty as qty FROM stock_almacen s INNER JOIN item_almacen i ON s.item_id = i.item_id GROUP BY s.item_id ORDER BY i.item_name ASC ";
*/
 while ($row_tabla = $resultado2->fetch_assoc()) {
      $caduca=date_create($row_tabla['stock_expiry']); 
      $actualiza=date_create($row_tabla['stock_added']); 
      $existencias=$row_tabla['stock_qty'];
      $minimo=$row_tabla['item_min'];
      $maximo=$row_tabla['item_max'];  
      
      $pdf->SetFont('Arial', '', 8);
  
      $pdf->Cell(12, 6, utf8_decode($row_tabla['item_code']), 1, 0, 'L');
      $pdf->Cell(115, 6, utf8_decode($row_tabla['item_name'].', '.$row_tabla['item_grams']), 1, 0, 'L');
      $pdf->Cell(20, 6, utf8_decode($row_tabla['item_type_desc']), 1, 0, 'L');
      $pdf->Cell(30, 6, utf8_decode($row_tabla['stock_lote']), 1, 0, 'L');

     $contador = $date1 -> diff($caduca);
     if ($contador->days.'days' < 90)
        $pdf->SetTextColor(255, 0, 2);
      else  $pdf->SetTextColor(43, 45, 127); 
      
    /* $pdf->Cell(25, 6, $contador->days.'days', 1, 0, 'L'); */

      $pdf->Cell(17, 6, date_format($caduca,"d/m/Y"), 1, 0, 'L');
      $pdf->SetTextColor(43, 45, 127); 

      $pdf->Cell(22, 6, utf8_decode($row_tabla['stock_entradas']), 1, 0, 'R');
      $pdf->Cell(22, 6, utf8_decode($row_tabla['stock_salidas']), 1, 0, 'R');
      $pdf->Cell(22, 6, utf8_decode($row_tabla['stock_devoluciones']), 1, 0, 'R');
     

      if ($existencias <= $minimo or $existencias >= $maximo) $pdf->SetTextColor(255, 0, 2);
      else  $pdf->SetTextColor(43, 45, 127); 

      $pdf->Cell(22, 6, utf8_decode($row_tabla['stock_qty']), 1, 0, 'R');
       $pdf->SetTextColor(43, 45, 127);  

      $pdf->Cell(14, 6, utf8_decode($row_tabla['item_min']), 1, 0, 'L'); 
      $pdf->Cell(14, 6, utf8_decode($row_tabla['item_max']), 1, 0, 'L');
   
      $pdf->SetTextColor(43, 45, 127);  
      $pdf->Cell(20, 6, date_format($actualiza,"d/m/Y"), 1, 0, 'L');
      $pdf->Cell(10, 6, utf8_decode($row_tabla['activo']), 1, 0, 'C');
      $pdf->Ln(6);
    }
  

$pdf->Ln(8);
$pdf->SetX(30);



$pdf->Output();
