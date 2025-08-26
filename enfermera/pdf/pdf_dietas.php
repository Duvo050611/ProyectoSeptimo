<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = @$_GET['id_atencion'];

mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
  function Header()
  {
     $id = @$_GET['id'];;
include '../../conexionbd.php';
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 10, 15, 40, 25);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],97,15, 100, 25);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 248, 18, 50, 20);
}
    $this->SetFont('Arial', 'B', 15);
    $this->SetTextColor(43, 45, 127);
 
    $this->Ln(10);
  
 
    $this->Ln(4);
   
    $this->Ln(4);
   
    $this->Ln(4);

    $this->Ln(10);
    
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('CMSI-039'), 0, 1, 'R');
  }
}

$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTextColor(43, 45, 127);
  $pdf->SetDrawColor(43, 45, 180);

$pdf->SetFont('Arial', 'B', 10);
$pdf->setX(36);
$pdf->Cell(227, 10, utf8_decode('RELACIÓN DE DIETAS'), 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 7);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y H:i");
$pdf->Cell(30, 5, ' FECHA: ' . $fecha_actual, 0, 1, 'R');

$pdf->Ln(5);

$pdf->Cell(10, 10, utf8_decode('Hab.'), 1, 0, 'C');
$pdf->Cell(17, 10, utf8_decode('F.ingreso'), 1, 0, 'C');
$pdf->Cell(70, 10, utf8_decode('Paciente'), 1, 0, 'C');
$pdf->Cell(17, 10, utf8_decode('Fec.nac.'), 1, 0, 'C');
$pdf->Cell(15, 10, utf8_decode('Edad'), 1, 0, 'C');
$pdf->Cell(17, 10, utf8_decode('Indicada'), 1, 0, 'C');
$pdf->Cell(15, 10, utf8_decode('Hora'), 1, 0, 'C');
$pdf->Cell(31, 10, utf8_decode('Alergias'), 1, 0, 'C');
$pdf->Cell(28, 10, utf8_decode('Tipo de dieta'), 1, 0, 'C');
$pdf->Cell(64, 10, utf8_decode('Observaciones'), 1, 0, 'C');

$pdf->Ln(10);

$pdf->SetFont('Arial', '', 7);
$sql = "SELECT * from cat_camas where TIPO ='HOSPITALIZACION' ORDER BY num_cama ASC ";
$result = $conexion->query($sql);
while ($row = $result->fetch_assoc()) {
  $id_at_cam = $row['id_atencion'];
  $sql_tabla = "SELECT p.edad, p.fecnac,p.Id_exp, p.papell, p.sapell,p.nom_pac, di.fecha, di.motivo_atn,di.edo_salud,di.alergias,ru.pre, ru.nombre as nom_doc, ru.papell as ape_doc,  do.dieta, do.det_dieta, do.fecha_ord, do.hora_ord from dat_ingreso di, paciente p, reg_usuarios ru,dat_ordenes_med do WHERE p.Id_exp = di.Id_exp  and do.id_atencion=di.id_atencion and di.id_usua = ru.id_usua and di.id_atencion = $id_at_cam ORDER BY do.id_ord_med DESC LIMIT 1";



  $result_tabla = $conexion->query($sql_tabla);
  $rowcount = mysqli_num_rows($result_tabla);
  if ($rowcount != 0) {
    while ($row_tabla = $result_tabla->fetch_assoc()) {
      $pdf->Cell(10, 11, utf8_decode($row['num_cama']), 1, 0, 'C');
   
      $fecha_ing=date_create($row_tabla['fecha']);
      $pdf->Cell(17, 11, utf8_decode(date_format($fecha_ing,"d-m-Y")), 1, 0, 'C');
      
      $pdf->Cell(70, 11, utf8_decode($row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' . $row_tabla['nom_pac']), 1, 'C');
      $fecha_naci=date_create($row_tabla['fecnac']);
 $pdf->Cell(17, 11, utf8_decode(date_format($fecha_naci,"d-m-Y")), 1, 0, 'C');


      
  $pdf->Cell(15, 11, utf8_decode($row_tabla['edad']),1, 'C');
  
      $fecha_ord=date_create($row_tabla['fecha_ord']);


      $pdf->Cell(17, 11, date_format($fecha_ord,"d-m-Y") , 1, 0, 'L');
      $pdf->SetFont('Arial', '', 6);
      $pdf->Cell(15, 11, $row_tabla['hora_ord'], 1, 0, 'L');
      $pdf->SetFont('Arial', '', 5);
      $pdf->Cell(31, 11, utf8_decode($row_tabla['alergias'] ), 1, 0, 'L');
      $pdf->SetFont('Arial', '', 6);
      $pdf->Cell(28, 11, utf8_decode($row_tabla['dieta']), 1, 0, 'L');
      $pdf->Cell(64, 11, utf8_decode($row_tabla['det_dieta']), 1, 0, 'L');
     $pdf->Ln(11);
    }
  } 
}

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetY(-30);
$pdf->SetX(110);
$pdf->Cell(80, 8, utf8_decode('NOMBRE Y FIRMA DE LA ENFERMERA'), 0, 0, 'C');

$pdf->Output();
