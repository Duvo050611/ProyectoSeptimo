<?php
use PDF as GlobalPDF;

require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_in=@$_GET['id'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

$sql_tras = "SELECT * from ninobebe where id_exp=$id_exp";
$result_tras = $conexion->query($sql_tras);

while ($row_tras = $result_tras->fetch_assoc()) {
  $id = $row_tras['id'];
}

if(isset($id)){
    $id = $id;
  }else{
    $id ='sin doc';
  }

if($id=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE NOTA PARA ESTE PACIENTE", 
                            type: "error",
                            confirmButtonText: "ACEPTAR"
                        }, function(isConfirm) { 
                            if (isConfirm) {
                                window.close();
                            }
                        });
                    });
                </script>';
}else{

mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
  function Header()
  {
  include '../../conexionbd.php';
    $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
    while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];

    $this->Image("../../configuracion/admin/img2/".$bas, 7, 9, 40, 25);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],58,15, 109, 24);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 168, 16, 38, 14);
}

   $this->Ln(32);
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
     $this->Cell(0, 10, utf8_decode('INEO-000'), 0, 1, 'R');
  }
}
$sql_pac = "SELECT p.papell, p.nom_pac,p.sapell,p.edad,p.sexo,p.Id_exp,p.folio,p.dir,p.id_edo,p.id_mun,p.tel,p.ocup,p.resp,p.paren,p.tel_resp, p.fecnac FROM paciente p where p.Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $papell = $row_pac['papell'];
  $nom_pac = $row_pac['nom_pac'];
  $sapell = $row_pac['sapell'];
  $edad = $row_pac['edad'];
  $sexo = $row_pac['sexo'];
  $Id_exp = $row_pac['Id_exp'];
  $folio = $row_pac['folio'];
  $dir = $row_pac['dir'];
  $id_edo = $row_pac['id_edo'];
  $id_mun = $row_pac['id_mun'];
  $tel = $row_pac['tel'];
  $ocup = $row_pac['ocup'];
  $resp = $row_pac['resp'];
  $paren = $row_pac['paren'];
  $tel_resp = $row_pac['tel_resp'];
  $fecnac = $row_pac['fecnac'];
}



$sql_preop = "SELECT * FROM dat_ingreso where id_atencion = $id_atencion";
$result_preop = $conexion->query($sql_preop);

while ($row_preop = $result_preop->fetch_assoc()) {
 
    $tipo_a = $row_preop['tipo_a'];
    $fecha_ing = $row_preop['fecha'];
    $id_usua = $row_preop['id_usua'];
}

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(8, 10, 10);
#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true,32);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(55);
$pdf->SetTextColor(43, 45, 127);
$pdf->Cell(110, 9, utf8_decode('NOTA NIÑO BEBÉ'), 1, 0, 'C');
$pdf->SetX(160);
$pdf->SetFont('Arial', '', 6);
date_default_timezone_set('America/Mexico_City');
$date1 = date_create(date("Y-m-d H:i:s"));
$pdf->Cell(35, 9, 'FECHA: ' . date_format($date1,"d/m/Y H:i"), 0, 1, 'R');
$pdf->Ln(5);

$pdf->SetDrawColor(43, 45, 127);
$pdf->Line(8, 53, 207, 53);
$pdf->Line(8, 53, 8, 80);
$pdf->Line(207, 53, 207, 80);
$pdf->Line(8, 80, 207, 80);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, 'Servicio: ',0 , 0, 'L');  
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(120, 5, utf8_decode($tipo_a) , 'B',0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$date=date_create($fecha_ing);
$pdf->Cell(28, 5, utf8_decode('Fecha de Ingreso:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(32, 5, date_format($date,'d/m/Y H:i:s'), 'B', 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, utf8_decode('Paciente: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(180, 5, utf8_decode($folio. ' - '. $papell . ' ' . $sapell . ' ' . $nom_pac),'B' , 0, 'L');
$pdf->SetFont('Arial', 'B', 8);

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$date1=date_create($fecnac);
$pdf->Cell(31, 5, 'Fecha de nacimiento: ', 0 , 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5, date_format($date1,"d/m/Y"),'B' , 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(11, 5, ' Edad: ', 0 , 0, 'L');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(13, 5, utf8_decode($edad), 'B' , 0, 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(19, 5, utf8_decode(' Ocupación: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(36, 5,  utf8_decode($ocup), 'B', 0,'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(16, 5, utf8_decode(' Teléfono: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5,  utf8_decode($tel), 'B',0, 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 5, utf8_decode('Género: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(14, 5,  $sexo, 'B', 0,'L');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 5, utf8_decode(' Domicilio: '), 0, 0,'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(125, 5, utf8_decode($dir), 'B', 0,'L');

$pdf->Ln(11);

$sql_tras = "SELECT * from ninobebe where id=$id_in and id_exp=$id_exp";
$result_tras = $conexion->query($sql_tras);

while ($row_tras = $result_tras->fetch_assoc()) {
  $reflejo_od = $row_tras['reflejo_od'];
  $eje_visual_od = $row_tras['eje_visual_od'];
  $fijacion_od = $row_tras['fijacion_od'];
  $esquiascopia_od = $row_tras['esquiascopia_od'];
  $posicion_od = $row_tras['posicion_od'];
  $reflejo_oi = $row_tras['reflejo_oi'];
  $eje_visual_oi = $row_tras['eje_visual_oi'];
  $fijacion_oi = $row_tras['fijacion_oi'];
  $esquiascopia_oi = $row_tras['esquiascopia_oi'];
  $posicion_oi = $row_tras['posicion_oi'];
  $fecha_registro = $row_tras['fecha_registro'];
}

// Estilo común
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 6, utf8_decode('Exploración Ojo Derecho (OD)'), 1, 1, 'C'); // Título

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(38, 6, utf8_decode('Reflejo OD'), 1, 0, 'L');
$pdf->Cell(57, 6, utf8_decode($reflejo_od), 1, 0, 'L');
$pdf->Cell(38, 6, utf8_decode('Eje Visual OD'), 1, 0, 'L');
$pdf->Cell(57, 6, utf8_decode($eje_visual_od), 1, 1, 'L');

$pdf->Cell(38, 6, utf8_decode('Fijación OD'), 1, 0, 'L');
$pdf->Cell(57, 6, utf8_decode($fijacion_od), 1, 0, 'L');
$pdf->Cell(38, 6, utf8_decode('Esquiascopia OD'), 1, 0, 'L');
$pdf->Cell(57, 6, utf8_decode($esquiascopia_od), 1, 1, 'L');

$pdf->Cell(38, 6, utf8_decode('Posición OD'), 1, 0, 'L');
$pdf->Cell(152, 6, utf8_decode($posicion_od), 1, 1, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 6, utf8_decode('Exploración Ojo Izquierdo (OI)'), 1, 1, 'C'); // Título

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(38, 6, utf8_decode('Reflejo OI'), 1, 0, 'L');
$pdf->Cell(57, 6, utf8_decode($reflejo_oi), 1, 0, 'L');
$pdf->Cell(38, 6, utf8_decode('Eje Visual OI'), 1, 0, 'L');
$pdf->Cell(57, 6, utf8_decode($eje_visual_oi), 1, 1, 'L');

$pdf->Cell(38, 6, utf8_decode('Fijación OI'), 1, 0, 'L');
$pdf->Cell(57, 6, utf8_decode($fijacion_oi), 1, 0, 'L');
$pdf->Cell(38, 6, utf8_decode('Esquiascopia OI'), 1, 0, 'L');
$pdf->Cell(57, 6, utf8_decode($esquiascopia_oi), 1, 1, 'L');

$pdf->Cell(38, 6, utf8_decode('Posición OI'), 1, 0, 'L');
$pdf->Cell(152, 6, utf8_decode($posicion_oi), 1, 1, 'L');




$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 7);
$pdf->Cell(60, 5, utf8_decode('Fecha de Registro: ' . date('d/m/Y H:i', strtotime($fecha_registro))), 0, 1);


/*$sql_med_id = "SELECT id_usua FROM ninobebe WHERE id_atencion = $id_atencion ORDER by id DESC LIMIT 1";
    $result_med_id = $conexion->query($sql_med_id);

    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_med = $row_med_id['id_usua'];
    }
    
    */
    $sql_med = "SELECT * FROM reg_usuarios WHERE id_usua = $id_usua";
    $result_med = $conexion->query($sql_med);

    while ($row_med = $result_med->fetch_assoc()) {
      $nom = $row_med['nombre'];
      $app = $row_med['papell'];
      $apm = $row_med['sapell'];
      $pre = $row_med['pre'];
      $firma = $row_med['firma'];
      $ced_p = $row_med['cedp'];
 $cargp = $row_med['cargp'];
}
     
// --- Firma del médico (centrada y válida)
$pdf->Ln(20);
$pdf->SetFont('Arial', 'B', 7);

// Verifica si hay firma cargada
$firma_path = (!empty($firma) && file_exists('../../imgfirma/' . $firma))
    ? '../../imgfirma/' . $firma
    : '../../imgfirma/FIRMA.jpg';

$pdf->Image($firma_path, 90, 240, 30);

// Texto debajo de la firma
$pdf->SetY(253);
$pdf->SetFont('Arial', 'B', 7);

// Usamos ancho 210 para que esté centrado horizontalmente en la hoja
$pdf->Cell(194, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm), 0, 1, 'C');
$pdf->Cell(194, 4, utf8_decode($cargp . ' - Céd. Prof. ' . $ced_p), 0, 1, 'C');
$pdf->Cell(194, 4, utf8_decode('Nombre y firma del médico'), 0, 1, 'C');



$pdf->Output();
}