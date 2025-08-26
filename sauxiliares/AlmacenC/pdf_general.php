<?php

use PDF as GlobalPDF;

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
    $this->Cell(145, 10, utf8_decode('EXISTENCIAS DE ALMACÉN CENTRAL (DETALLE)'), 1, 0, 'C');
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
    $this->Cell(0, 10, utf8_decode('CMSI-32'), 0, 1, 'R');
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


$sql = " SELECT * FROM item_almacen, stock_almacen, item_type where 
    item_type.item_type_id=item_almacen.item_type_id and 
    item_almacen.item_id = stock_almacen.item_id";
$result = $conexion_almacen->query($sql);
while ($row = $result->fetch_assoc()) {
  $item_id = $row['item_id'];
  $item_name = $row['item_name'];
  $item_code = $row['item_code'];
  $lote = $row['stock_lote'];

  $caduca=date_create($row['stock_expiry']); 
  $actualiza=date_create($row['stock_added']); 
  $existencias=$row['stock_qty'];
  $minimo=$row['item_min'];
  $maximo=$row['item_max'];  

  $pdf->SetFont('Arial', 'B', 8);
  $pdf->Cell(12, 5, utf8_decode('Código'), 1, 0, 'C');
  $pdf->Cell(115, 5, utf8_decode('Descripción'), 1, 0, 'C');
  $pdf->Cell(20, 5, utf8_decode('Presentación'), 1, 0, 'C');
  $pdf->Cell(30, 5, utf8_decode('Lote'), 1, 0, 'C');
  $pdf->Cell(17, 5, utf8_decode('Caducidad'), 1, 0, 'C');
  $pdf->Cell(22, 5, utf8_decode('Entradas'), 1, 0, 'C');
  $pdf->Cell(22, 5, utf8_decode('Salidas'), 1, 0, 'C');
  $pdf->Cell(22, 5, utf8_decode('Devoluciones'), 1, 0, 'C');
  $pdf->Cell(22, 5, utf8_decode('Existencias'), 1, 0, 'C');
  $pdf->Cell(14, 5, utf8_decode('Mínimo'), 1, 0, 'C');
  $pdf->Cell(14, 5, utf8_decode('Máximo'), 1, 0, 'C');
  $pdf->Cell(20, 5, utf8_decode('Actualización'), 1, 0, 'C');
  $pdf->Ln(5);
  
  $pdf->Cell(12, 5, utf8_decode($row['item_code']), 1, 0, 'L');
  $pdf->Cell(115, 5, utf8_decode($row['item_name'].', '.$row['item_grams']), 1, 0, 'L');
  $pdf->Cell(20, 5, utf8_decode($row['item_type_desc']), 1, 0, 'L');
  $pdf->Cell(30, 5, utf8_decode($lote), 1, 0, 'L');
  
  $fecha_actual = date("Y-m-d H:i");
  $date1=date_create($fecha_actual);

  $contador = $date1 -> diff($caduca);
  if ($contador->days.'days' < 90)
      $pdf->SetTextColor(255, 0, 2);
  else  $pdf->SetTextColor(43, 45, 127); 

  $pdf->Cell(17, 5, date_format($caduca,"d/m/Y"), 1, 0, 'L');

  $pdf->SetTextColor(43, 45, 127); 

  $pdf->Cell(22, 5, utf8_decode($row['stock_entradas']), 1, 0, 'R');
  $pdf->Cell(22, 5, utf8_decode($row['stock_salidas']), 1, 0, 'R');
  $pdf->Cell(22, 5, utf8_decode($row['stock_devoluciones']), 1, 0, 'R');   

  if ($existencias <= $minimo or $existencias >= $maximo) $pdf->SetTextColor(255, 0, 2);
  else  $pdf->SetTextColor(43, 45, 127); 

  $pdf->Cell(22, 5, utf8_decode($row['stock_qty']), 1, 0, 'R');

  $pdf->SetTextColor(43, 45, 127);  
  $pdf->Cell(14, 5, utf8_decode($row['item_min']), 1, 0, 'R'); 
  $pdf->Cell(14, 5, utf8_decode($row['item_max']), 1, 0, 'R');
   
  $pdf->SetTextColor(43, 45, 127);  
  $pdf->Cell(20, 5, date_format($actualiza,"d/m/Y"), 1, 0, 'C');
  $pdf->Ln(7);

  $pdf->SetFont('Arial', 'B', 8);
  $pdf->Cell(25, 5, utf8_decode('Fecha'), 1, 0, 'C');
  $pdf->Cell(37, 5, utf8_decode('Movimiento'), 1, 0, 'C');
  $pdf->Cell(23, 5, utf8_decode('Cantidad'), 1, 0, 'C');
  $pdf->Cell(20, 5, utf8_decode('Caducidad'), 1, 0, 'C');  
  $pdf->Cell(30, 5, utf8_decode('Factura'), 1, 0, 'C');
  $pdf->Cell(22, 5, utf8_decode('Fecha factura'), 1, 0, 'C');
  $pdf->Cell(173, 5,utf8_decode('Motivo de devolución'), 1, 0, 'C');
  $pdf->Ln(5);

  $pdf->SetFont('Arial', '', 8);

  $sql2 = $conexion_almacen->query("SELECT * FROM entradas where entradas.item_id=$item_id and entradas.entrada_lote like '%$lote%' ORDER BY entradas.entrada_added DESC") 
  or die('<p>Error al encontrar entradas</p><br>' . mysqli_error($conexion));
     
        while ($row2 = $sql2->fetch_assoc()) {
        
     $fecha=date_create($row2['entrada_added']); 
     $caduca=date_create($row2['entrada_expiry']); 
     $compra=date_create($row2['entrada_purchased']); 
     $id_usua=$row2['id_usua'];
     $resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$id_usua") or die($conexion->error);
        while($row3 = mysqli_fetch_array($resultado_usua)){

    $pdf->Cell(25, 5, date_format($fecha,"d/m/Y H:i"), 1, 0, 'L');
    $pdf->SetTextColor(32, 92, 61);
    $pdf->Cell(37, 5, utf8_decode('ENTRADA'), 1, 0, 'L');
    $pdf->SetTextColor(43, 45, 127);  
    $pdf->Cell(23, 5, $row2['entrada_qty'], 1, 0, 'R');
    $pdf->Cell(20, 5, date_format($caduca,"d/m/Y"), 1, 0, 'C'); 
    $pdf->Cell(30, 5, utf8_decode($row2['entrada_factura']), 1, 0, 'L');
    $pdf->Cell(22, 5, date_format($compra,"d/m/Y"), 1, 0, 'L');
    $pdf->Ln(5);
  }}
  

  $pdf->SetTextColor(43, 45, 127);

  $sql4 = $conexion_almacen->query("SELECT * FROM devolucion_almacen d where 
    d.item_id=$item_id and d.stock_lote like '%$lote%' ORDER BY d.stock_added ASC") 
  or die('<p>Error al encontrar devoluciones</p><br>' . mysqli_error($conexion));
   
  while ($row4 = mysqli_fetch_array($sql4)){
    $fecdev=date_create($row4['stock_added']);
    $caduca=date_create($row2['entrada_expiry']); 
    $pdf->Cell(25, 5, date_format($fecdev,"d/m/Y H:i"), 1, 0, 'L');
    $pdf->SetTextColor(30, 19, 245);
    $pdf->Cell(37, 5, utf8_decode('DEVOLUCIÓN '. $row4['almacen']), 1, 0, 'L');
    $pdf->SetTextColor(43, 45, 127);  
    $pdf->Cell(23, 5, $row4['stock_qty'], 1, 0, 'R');
    $pdf->Cell(20, 5, date_format($caduca,"d/m/Y"), 1, 0, 'C'); 
    $pdf->Cell(30, 5, utf8_decode(' '), 1, 0, 'C');
    $pdf->Cell(22, 5, utf8_decode(' '), 1, 0, 'C');
    $pdf->Cell(173, 5, utf8_decode($row4['motivo']), 1, 0, 'L');
    $pdf->Ln(5);
  }


   $pdf->SetTextColor(43, 45, 127);  
  $sql3 = $conexion_almacen->query("SELECT * FROM sales_almacen sa where sa.item_id=$item_id and sa.stock_lote like '%$lote%' ORDER BY sa.date_sold DESC") 
  or die('<p>Error al encontrar sales_almacen</p><br>' . mysqli_error($conexion));

  while ($row3 = mysqli_fetch_array($sql3)) {
    $fecsal=date_create($row3['date_sold']);
    $pdf->Cell(25, 5, date_format($fecsal,"d/m/Y H:i"), 1, 0, 'L');
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(37, 5, utf8_decode('SALIDA ' . $row3['almacen'] ), 1, 0, 'L');
    $pdf->SetTextColor(43, 45, 127);  
    $pdf->Cell(23, 5, $row3['qty'], 1, 0, 'R');
    $pdf->Ln(5);
  }

  
  $pdf->Ln(5);
}


$pdf->Output();
