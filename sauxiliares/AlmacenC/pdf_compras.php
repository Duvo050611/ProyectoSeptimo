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
        $this->Cell(140, 10, utf8_decode('COMPRAS PENDIENTES DE ALMACÉN CENTRAL'), 1, 0, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetX(251);
        $fecha_actual = date("Y-m-d H:i");
        $date1=date_create($fecha_actual);
        $this->Cell(35, 10, 'FECHA: ' . date_format($date1,"d/m/Y H:i"), 0, 1, 'R');  
        $this->Ln(4);  
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(12, 5, utf8_decode('Código'), 1, 0, 'C');
        $this->Cell(120, 5, utf8_decode('Descripción'), 1, 0, 'C');
        $this->Cell(20, 5, utf8_decode('Presentación'), 1, 0, 'C');
        $this->Cell(20, 5, utf8_decode('Cantidad'), 1, 0, 'C');
        $this->Cell(20, 5, utf8_decode('Almacen'), 1, 0, 'C');
        $this->Cell(20, 5, utf8_decode('Fecha'), 1, 0, 'C');
        $this->Cell(70, 5, utf8_decode('Registró'), 1, 0, 'C');
        $this->Ln(5);
    }
    function Footer()
    {
        $this->SetFont('Arial', 'B', 7);
        $this->SetY(-15);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('CMSI-34'), 0, 1, 'R');
    }
}

$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(43, 45, 127);
$pdf->SetDrawColor(43, 45, 180);

$sql = "SELECT * FROM item_almacen, compras, item_type where item_type.item_type_id=item_almacen.item_type_id and item_almacen.item_id = compras.item_id and compras.comprado = 'NO'";
$result = $conexion_almacen->query($sql);
//while ($row = $result->fetch_assoc()) {

$rowcount = mysqli_num_rows($result);
if ($rowcount != 0) {
    while ($row_tabla = $result->fetch_assoc()) {
        $added=date_create($row_tabla['fecha_sol']);
        $id_usua = $row_tabla['id_usua'];
        if ($row_tabla['almacen']=="CEYE") {
            $almacen = "QUIRÓFANO";}
        else {
               $almacen = $row_tabla['almacen'];} 

        $sql = $conexion->query("SELECT * FROM reg_usuarios r where r.id_usua = $id_usua") or die($conexion->error);
        while ($row_usu = $sql->fetch_assoc()) {
            $nombre_usu = $row_usu['papell']; 
        }
        $pdf->Cell(12, 5, utf8_decode($row_tabla['item_code']), 1, 0, 'L');
        $pdf->Cell(120, 5, utf8_decode($row_tabla['item_name'].', '.$row_tabla['item_grams']), 1, 0, 'L');
        $pdf->Cell(20, 5, utf8_decode($row_tabla['item_type_desc']), 1, 0, 'L');
        $pdf->Cell(20, 5, utf8_decode($row_tabla['compra_qty']), 1, 0, 'L');
        $pdf->Cell(20, 5, utf8_decode($almacen), 1, 0, 'L');
        $pdf->Cell(20, 5, date_format($added,"d/m/Y"), 1, 0, 'L');
        $pdf->Cell(70, 5, utf8_decode($nombre_usu), 1, 0, 'L');
        $pdf->Ln(5);
    }
} else {


}
//}
$pdf->Ln(5);
$pdf->SetX(30);



$pdf->Output();
