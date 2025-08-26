<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = @$_GET['id_atencion'];

mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
  function Header()
  {
    $id = @$_GET['id'];
    $id_med = @$_GET['id_med'];
    include '../../conexionbd.php';

    $id = @$_GET['id'];
$this->sety(2);
    $this->Image('../../imagenes/SI.PNG', 5, 4, 65, 21);
    $this->SetFont('Arial', 'B', 15);
    $this->SetTextColor(43, 45, 127);
    $this->Cell(277, 9, utf8_decode(' Médica San Isidro'), 0, 0, 'C');
    $this->Ln(10);
    $this->SetDrawColor(43, 45, 180);
    $this->Line(80, 18, 230, 18);
    $this->SetFont('Arial', '', 8);
    $this->Cell(277, 8, utf8_decode('CALLE JOSEFA ORTIZ DE DOMÍNGUEZ #444'), 0, 0, 'C');
    $this->Ln(4);
    $this->Cell(277, 8, utf8_decode('BARRIO COAXUSTENCO, METEPEC, ESTADO DE MÉXICO'), 0, 0, 'C');
    $this->Ln(4);
    $this->Cell(277, 8, 'TEL: (01722) 235-01-75 / 235-02-12 / 902-03-90 / C.P. 52140', 0, 0, 'C');
    $this->Ln(4);
    $this->Cell(277, 8, 'https://medicasanisidro.com/', 0, 0, 'C');
    $this->Ln(10);
    $this->Image('../../imagenes/en.png', 247, 11, 45, 15);
  
    $this->SetDrawColor(0, 0, 0);
   
  }
   function Footer()
  {
    $this->Ln(1);
$this->SetY(184.5);
$this->SetX(30);
$this->SetFont('Arial', 'B', 7);
$this->Cell(80, 5, utf8_decode('TURNO MATUTINO'), 0, 0, 'C');
$this->Cell(80, 5, utf8_decode('TURNO VESPERTINO'), 0, 0, 'C');
$this->Cell(80, 5, utf8_decode('TURNO NOCTURNO'), 0, 0, 'C');
$this->SetX(30);
$this->Cell(80, 9, utf8_decode(''), 1, 0, 'L');
$this->Cell(80, 9, utf8_decode(''), 1, 0, 'L');
$this->Cell(80, 9, utf8_decode(''), 1, 0, 'L');
$this->Ln(10);
$this->SetFont('Arial', 'B', 7);
$this->SetX(30);
$this->Cell(36, 5, utf8_decode('AVALÓ DIRECTOR:'), 0, 0, 'C');
$this->Cell(80, 5, utf8_decode(''), 'B', 'L');
$this->Cell(40, 5, utf8_decode('MÉDICO DE GUARDIA:'), 0, 0, 'C');
$this->Cell(80, 5, utf8_decode(''), 'B', 'L');


    $this->SetFont('Arial', 'B', 8);
    $this->SetY(-18);

    $this->Ln(6);
    $this->Cell(0, 6, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    $this->Cell(0, 6, utf8_decode('SIMA-014'), 0, 1, 'R');
  }
}


function calculaedad($fechanacimiento)
{
  list($ano, $mes, $dia) = explode("-", $fechanacimiento);
  $ano_diferencia  = date("Y") - $ano;
  $mes_diferencia = date("m") - $mes;
  $dia_diferencia   = date("d") - $dia;
  if ($ano_diferencia > 0 )
      return ($ano_diferencia . ' AÑOS');
  else if ($mes_diferencia > 0 || $ano_diferencia < 0)
      return ($mes_diferencia . ' MESES');
   else if ($dia_diferencia > 0 || $mes_diferencia < 0 || $ano_diferencia < 0)
      return ($dia_diferencia . ' DÍAS');
}

$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTextColor(43, 45, 127);
  $pdf->SetDrawColor(43, 45, 180);
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d/m/Y");

$pdf->SetFont('Arial', 'B', 10);
$pdf->sety(34);
$pdf->setX(15);
$pdf->Cell(260, 6, utf8_decode('CENSO DIARIO Y REPORTE DE VIGILANCIA EPIDEMIOLÓGICA - HOSPITALIZACIÓN'), 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18, 6, 'FECHA: ' . date('d/m/Y'), 0, 1, 'R');
$pdf->SetFont('Arial', 'B', 5.5);

$pdf->Cell(8, 6, utf8_decode('HAB.'), 1, 0, 'C');
$pdf->Cell(16, 6, utf8_decode('F.INGRESO'), 1, 0, 'C');
$pdf->Cell(56, 6, utf8_decode('NOMBRE DEL PACIENTE'), 1, 0, 'C');
$pdf->Cell(16, 6, utf8_decode('F.Nacimiento'), 1, 0, 'L');
$pdf->Cell(16, 6, utf8_decode('EDAD'), 1, 0, 'C');
$pdf->Cell(50, 6, utf8_decode('DIAGNOSTICO'), 1, 0, 'C');
$pdf->Cell(26, 6, utf8_decode('ESTADO DE SALUD'), 1, 0, 'C');
$pdf->Cell(6, 6, utf8_decode('EXP.'), 1, 0, 'C');
$pdf->Cell(40, 6, utf8_decode('MÉDICO TRATANTE'), 1, 0, 'C');
$pdf->Cell(20, 6, utf8_decode('ASEGURADORA'), 1, 0, 'C');
$pdf->MultiCell(30, 3, utf8_decode('RECIBE INFORMACIÓN DE ESTADO DE SALUD'), 1, 'C');

$pdf->SetFont('Arial', '', 5.5);
$sql = "SELECT * from cat_camas where TIPO ='HOSPITALIZACION' ORDER BY num_cama ASC ";
$result = $conexion->query($sql);
while ($row = $result->fetch_assoc()) {
  $id_at_cam = $row['id_atencion'];
  if ($row['id_atencion'] <> 0){ 
  $sql_tabla = "SELECT p.fecnac,p.Id_exp, p.papell, p.sapell,p.nom_pac, di.fecha, di.motivo_atn,di.edo_salud,ru.pre, ru.nombre as nom_doc,ru.papell as papell_doc,ru.sapell as sapell_doc from dat_ingreso di, paciente p, reg_usuarios ru WHERE p.Id_exp = di.Id_exp and di.id_usua = ru.id_usua and di.id_atencion = $id_at_cam LIMIT 1";
  $result_tabla = $conexion->query($sql_tabla);
  $rowcount = mysqli_num_rows($result_tabla);

    while ($row_tabla = $result_tabla->fetch_assoc()) {
      $pdf->SetFont('Arial', 'B', 5.5);
      $pdf->Cell(8, 6, utf8_decode($row['num_cama']), 1, 0, 'L');
      $pdf->SetFont('Arial', '', 5.5);
      $fecnac=date_create($row_tabla['fecnac']);
      $fecha_ing=date_create($row_tabla['fecha']);
      $pdf->Cell(16, 6, utf8_decode(date_format($fecha_ing,"d-m-Y")), 1, 0, 'L');
      
      $pdf->Cell(56, 6, utf8_decode($row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' . $row_tabla['nom_pac']), 1, 'L');
      $pdf->Cell(16, 6, utf8_decode(date_format($fecnac,"d-m-Y")), 1, 0, 'L');
      $pdf->Cell(16, 6, utf8_decode(calculaedad($row_tabla['fecnac'])), 1, 0, 'L');
      $pdf->Cell(50, 6, utf8_decode($row_tabla['motivo_atn']), 1, 0, 'L');
      $pdf->Cell(26, 6, utf8_decode($row_tabla['edo_salud']), 1, 0, 'L');
      $pdf->Cell(6, 6, $row_tabla['Id_exp'], 1, 0, 'L');
      $pdf->Cell(40, 6, utf8_decode($row_tabla['pre'] . ' ' . $row_tabla['nom_doc'].' '.$row_tabla['papell_doc'].' '.$row_tabla['sapell_doc']), 1, 0, 'L');
      $aseguradora = " ";
      $sql_dat_fin = "SELECT * from dat_financieros where id_atencion = $id_at_cam ORDER BY id_atencion ASC LIMIT 1 ";
      $result_dat_fin = $conexion->query($sql_dat_fin);
      while ($row_dat_fin = $result_dat_fin->fetch_assoc()) {
      $aseguradora = $row_dat_fin['aseg'];
      }
      $pdf->Cell(20, 6, utf8_decode($aseguradora), 1, 0, 'L');
      $pdf->Cell(30, 6, utf8_decode(''), 1, 0, 'L');
      $pdf->Ln(6);
    }
  } else {
    $pdf->SetFont('Arial', 'B', 5.5);
    $pdf->Cell(8, 6, utf8_decode($row['num_cama']), 1, 0, 'L');
    $pdf->SetFont('Arial', '', 5.5);
    $pdf->Cell(16, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Cell(56, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Cell(16, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Cell(16, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Cell(50, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Cell(26, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Cell(6, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Cell(40, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Cell(20, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Cell(30, 6, utf8_decode(''), 1, 0, 'L');
    $pdf->Ln(6);
  }
}



$pdf->Output();
