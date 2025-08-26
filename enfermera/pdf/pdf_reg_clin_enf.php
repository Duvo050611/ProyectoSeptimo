<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];
$fechar = @$_GET['fechar'];
$hora_mat = @$_GET['hora_mat'];

$sql_clin = "SELECT * FROM enf_reg_clin  where id_atencion = $id_atencion";
$result_clin = $conexion->query($sql_clin);
while ($row_clinreg = $result_clin->fetch_assoc()) {
  $id_clinreg = $row_clinreg['id_clinreg'];
}
if(isset($id_clinreg)){
    $id_clinreg = $id_clinreg;
  
  }else{
    $id_clinreg ='sin doc';
  }
if($id_clinreg=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE REGISTRO CLINICO DE ENFERMERIA PARA ESTE PACIENTE", 
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
    $id = @$_GET['id'];
    $id_med = @$_GET['id_med'];
    $id_exp= @$_GET['id_exp'];
    $id_atencion = @$_GET['id_atencion'];
    $fechar = @$_GET['fechar'];
    $hora_mat = @$_GET['hora_mat'];
    include '../../conexionbd.php';

$sql_pac = "SELECT * FROM paciente  where Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $papell = $row_pac['papell'];
  $nom_pac = $row_pac['nom_pac'];
  $sapell = $row_pac['sapell'];
  $Id_exp = $row_pac['Id_exp'];
}


$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $bas=$f['img_ipdf'];
        
    
    $this->Image("../../configuracion/admin/img2/".$bas, 8, 4, 49, 25);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],57,4, 100, 25);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 156, 7, 50, 20);
}
  
    

    $this->Ln(10);

    $this->Ln(6);
  $this->SetFont('Arial', 'B', 7.5);
    $this->SetTextColor(43, 45, 127);
        $this->Cell(158, 5, utf8_decode($Id_exp . '-' . $papell . ' ' . $sapell . ' ' . $nom_pac), 0, 'L');
        $sql_q = "SELECT * from enf_reg_clin where fecha_mat='$fechar' and hora_mat=$hora_mat and id_atencion=$id_atencion";
$result_q = $conexion->query($sql_q); 
while ($row_q = $result_q->fetch_assoc()) {
  $fecha_mat = $row_q['fecha_mat'];
}

$date2 = date_create($fecha_mat);
$fecregistro = date_format($date2, "Y-m-d H:i a");
$this->SetFont('Arial', 'B', 7.5);
$this->Cell(45, 5, utf8_decode('Fecha de registro: '.date_format($date2, "d-m-Y")),0, 'C'); 
$this->Ln(4);
  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
  
   
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('SIMA-15.01'), 0, 1, 'R');
  }
}

$sql_pac = "SELECT * FROM paciente  where Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);

while ($row_pac = $result_pac->fetch_assoc()) {
  $papell = $row_pac['papell'];
  $nom_pac = $row_pac['nom_pac'];
  $sapell = $row_pac['sapell'];
  $edad = $row_pac['edad'];
  $sexo = $row_pac['sexo'];
  $Id_exp = $row_pac['Id_exp'];
  $dir = $row_pac['dir'];
  $id_edo = $row_pac['id_edo'];
  $id_mun = $row_pac['id_mun'];
  $tel = $row_pac['tel'];
  $ocup = $row_pac['ocup'];
  $resp = $row_pac['resp'];
  $paren = $row_pac['paren'];
  $tel_resp = $row_pac['tel_resp'];
  $fecnac = $row_pac['fecnac'];
  $tip_san = $row_pac['tip_san'];
  $folio = $row_pac['folio'];
}


$sql_ing = "SELECT * FROM dat_ingreso  where Id_exp = $id_exp";
$result_ing = $conexion->query($sql_ing);
while ($row_ing = $result_ing->fetch_assoc()) {
$especialidad = $row_ing['especialidad'];
  $fecha = $row_ing['fecha'];
  $fechai = $row_ing['fecha'];
  $area= $row_ing['area'];
  $tipo_a= $row_ing['tipo_a'];
  $tratante = $row_ing['id_usua'];
}

$sql_med = "SELECT * FROM reg_usuarios  where id_usua = $tratante";
$result_med = $conexion->query($sql_med);
while ($row_med = $result_med->fetch_assoc()) {
  $medico = $row_med['papell'];
  $prem = $row_med['pre'];
}

$sql_f = "SELECT enf_fecha FROM enf_reg_clin  where id_atencion = $id_atencion";
$result_f = $conexion->query($sql_f);
while ($row_f = $result_f->fetch_assoc()) {
$enf_fecha = $row_f['enf_fecha'];
}

$sql_camas = "SELECT * FROM cat_camas  where id_atencion = $id_atencion";
$result_camas = $conexion->query($sql_camas);
while ($row_camas = $result_camas->fetch_assoc()) {

  $num_cama = $row_camas['num_cama'];
}
      $sql_est = "SELECT DATEDIFF(fecha_mat, '$fechai') as estancia FROM enf_reg_clin where id_atencion = $id_atencion and fecha_mat='$fechar'";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
              $estancia = $row_est['estancia'];
      }
if ($estancia < 0) $estancia = 0;
$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Ln(3);
    $pdf->SetTextColor(43, 45, 127);

  $pdf->SetDrawColor(43, 45, 180);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(55);
$pdf->Cell(107, 5, utf8_decode('REGISTRO CLÍNICO DE ENFERMERÍA DE HOSPITALIZACIÓN'), 0, 0, 'C');
$sql_q = "SELECT * from enf_reg_clin where fecha_mat='$fechar' and hora_mat=$hora_mat and id_atencion=$id_atencion";
$result_q = $conexion->query($sql_q); 
while ($row_q = $result_q->fetch_assoc()) {
  $fecha_mat = $row_q['fecha_mat'];
    
}
$pdf->Cell(17,3, utf8_decode(''),0,'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(18,3, utf8_decode('Habitación: '),0,'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(7,3, utf8_decode($num_cama),'B','L');

//date_default_timezone_set('America/Guatemala');
$fecha_quir = date("d/m/Y H:i a");
$pdf->SetFont('Arial', '', 6.5);
//$pdf->Cell(25, 5, utf8_decode('Fecha de impresión: '.$fecha_quir), 0, 1, 'L');

$pdf->SetFont('Arial', '', 6.5);
$pdf->Ln(-1);
//$date = date_create($fecha);
//$pdf->Cell(110, 5, utf8_decode('Fecha de ingreso al hospital: '.date_format($date, "d-m-Y H:i:s")),0, 'L');


$pdf->Ln(9);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, 'Servicio: ', 0, 'L');  
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(78, 5, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$date2=date_create($fecha);
$pdf->Cell(28, 5, utf8_decode(' Fecha de Ingreso:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(32, 5, date_format($date2,'d/m/Y H:i a'), 'B', 0, 'C');
/*$pdf->SetFont('Arial', 'B', 8);
$date2 = date_create($fecha_mat);
$fecregistro = date_format($date2, "Y-m-d H:i a");
$pdf->Cell(50, 5, utf8_decode('Fecha de registro: '.date_format($date2, "d-m-Y")),0, 'C'); */
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18, 5, utf8_decode('Expediente: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(15, 5, utf8_decode($folio), 'B', 0, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(16, 5, ' Paciente: ', 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(146, 5, utf8_decode($papell . ' ' . $sapell . ' ' . $nom_pac), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$date1=date_create($fecnac);
$pdf->Cell(31, 5, 'Fecha de nacimiento: ', 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5, date_format($date1,"d/m/Y"), 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(11, 5, ' Edad: ', 0, 'L');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(13, 5, utf8_decode($edad),'B', 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(19, 5, utf8_decode(' Ocupación: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(36, 5,  utf8_decode($ocup), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(16, 5, utf8_decode(' Teléfono: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(21, 5,  utf8_decode($tel), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(13, 5, utf8_decode('Género: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(14, 5,  $sexo, 'B', 'L');
$pdf->Ln(5);
$pesoh="";
$tallah="";
$sql_sig ="select * from dat_hclinica WHERE Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_sig = $conexion->query($sql_sig);
while ($row_hc = $result_sig->fetch_assoc()) {
$pesoh=$row_hc['peso'];
$tallah=$row_hc['talla'];
}

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 5, utf8_decode('Peso: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(14, 5,  utf8_decode($pesoh.' Kilos'), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(11, 5, utf8_decode(' Talla: '), 0, 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(18, 5,  utf8_decode($tallah.' Metros'), 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 5, utf8_decode(' Domicilio: '), 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(125, 5, utf8_decode($dir), 'B', 'L');

$pdf->Ln(5);

$d="";
    $sql_motd = "SELECT diagprob_i from dat_not_ingreso where id_atencion=$id_atencion ORDER by id_not_ingreso DESC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } 
    $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i DESC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } ?>
    <?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
    $result_mot = $conexion->query($sql_mot);
    while ($row_mot = $result_mot->fetch_assoc()) {
    $m=$row_mot['motivo_atn'];
    } 
if ($d!=null) {
    $pdf->SetFont('Arial', 'B', 8);
  $pdf->Cell(20, 5, utf8_decode('Diagnóstico:') , 0, 'C');
  $pdf->SetFont('Arial', '', 9);
     $pdf->Cell(175, 5, utf8_decode($d) , 'B', 'C');
    } else{
        $pdf->SetFont('Arial', 'B',8);
         $pdf->Cell(29, 5, utf8_decode('Motivo de atención:') , 0, 'C');
         $pdf->SetFont('Arial', '', 9);
         $pdf->Cell(166, 5, utf8_decode($m) , 'B', 'C');
    }

$pdf->Ln(6);

$sql_edo = "SELECT edo_salud,alergias from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud DESC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  $edo_salud=$row_edo['edo_salud'];
  $alergias=$row_edo['alergias'];
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25,3, utf8_decode('Grupo sanguineo:'),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30,3, utf8_decode($tip_san),'B','L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 3, 'Tiempo estancia: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 3, $estancia . ' dias', 'B', 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25,3, utf8_decode('Estado de salud: '),0,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60,3, utf8_decode($edo_salud),'B','L');
$pdf->Ln(3);


$sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
  $result_aseg = $conexion->query($sql_aseg);
  while ($row_aseg = $result_aseg->fetch_assoc()) {
 $aseg= $row_aseg['aseg'];
}                      
$pdf->SetFont('Arial', 'B', 8);                                               
$pdf->Cell(20,5, utf8_decode('Aseguradora: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60,4, utf8_decode($aseg),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15,5, utf8_decode('Alergias: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(100,4, utf8_decode($alergias),'B','L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25,5, utf8_decode('Médico tratante: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(170,4, utf8_decode($prem . '. ' . $medico),'B','L');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 6);
$pdf->SetX(40);
$pdf->Cell(49,5, 'MATUTINO',1,0,'C');
$pdf->Cell(42,5, 'VESPERTINO',1,0,'C');
$pdf->Cell(77,5, 'NOCTURNO ',1,0,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(30,6, utf8_decode('Sigos vitales / Hora'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(7,6, utf8_decode('8'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('9'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('10'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('11'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('12'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('13'),1,0,'C');
//ves
$pdf->Cell(7,6, utf8_decode('14'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('15'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('16'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('17'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('18'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('19'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('20'),1,0,'C');
//noc
$pdf->Cell(7,6, utf8_decode('21'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('22'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('23'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('24'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('1'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('2'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('3'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('4'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('5'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('6'),1,0,'C');
$pdf->Cell(7,6, utf8_decode('7'),1,0,'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->SetY(90);
$pdf->SetX(10);
$pdf->Cell(30,3, utf8_decode('T/A (Sistólica/Diastólica)'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 5.8);
$pdf->Cell(30,3, utf8_decode('TAM (Tensión arterial media)'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(30,6, utf8_decode('Temperatura'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,6, utf8_decode('Frecuencia cardiaca'),1,0,'L');
$pdf->Ln(6);
$pdf->Cell(30,3, utf8_decode('Frecuencia respiratoria'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(30,3, utf8_decode('Frec. cardiaca fetal'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(30,3, utf8_decode('Saturación oxigeno'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(30,3, utf8_decode('Nivel de dolor (Escala EVA)'),1,0,'L');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Presión venosa central'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Perímetro abdominal'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Glicemia capilar'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,3, utf8_decode('Insulina'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(30,3, utf8_decode('Dextrosa al 50%'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Oxigenoterapía'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,6, utf8_decode('Micronebulizaciones'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,3, utf8_decode('Glasgow'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,3, utf8_decode('Enema'),1,0,'L');


$pdf->Ln(4);
$pdf->Cell(5,6, utf8_decode('I'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Tipo de dieta'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, utf8_decode(''),0,'C');
$pdf->Cell(25,3, utf8_decode('Infusión 2'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('N'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Solución base'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, utf8_decode(''),0,'C');
$pdf->Cell(25,3, utf8_decode('Infusión 3'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('G'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Medicamentos'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, utf8_decode(''),0,'C');
$pdf->Cell(25,3, utf8_decode('Infusión 4'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('R'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Vía oral'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, utf8_decode(''),0,'C');
$pdf->Cell(25,3, utf8_decode('Infusión 5'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('E'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Aminas'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, utf8_decode(''),0,0,'C');
$pdf->Cell(25,3, utf8_decode('Cargas'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Infusion 1'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode(''),0,'C');
$pdf->Cell(25,3, utf8_decode('Nutrición enteral'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode('O'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(25,3, utf8_decode('Hemoderivados'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, utf8_decode(''),0,0,'C');
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(25,3, utf8_decode('Nutrición parenteral'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Ingreso parcial total'),1,0,'L');
$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode(''),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Orina'),1,0,'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode(''),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Saratoga'),1,0,'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode(''),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Ileostomias'),1,0,'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode('E'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Vomito'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('G'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Sangrado'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, utf8_decode(''),0,'C');
$pdf->Cell(25,3, utf8_decode('Sonda endopleural'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('R'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Sonda nasogástrica'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,3, utf8_decode(''),0,'C');
$pdf->Cell(25,3, utf8_decode('VAC'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('E'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Sonda. T'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,6, utf8_decode('Evacuaciones'),1,0,'L');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('O'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Colostomia'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode(''),0,'C');
$pdf->Cell(25,3, utf8_decode('Penrose derecho'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,6, utf8_decode('S'),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Biovac izquiedo'),1,0,'L');
$pdf->Ln(3);
$pdf->Cell(5,6, utf8_decode(''),0,'C');
$pdf->Cell(25,3, utf8_decode('Biovac derecho'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,4, utf8_decode(''),1,0,'C');
$pdf->Cell(25,4, utf8_decode('Drenovac'),1,0,'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,4, utf8_decode(''),1,0,'C');
$pdf->Cell(25,4, utf8_decode('Penrose izquierdo'),1,0,'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(5,3, utf8_decode(''),1,0,'C');
$pdf->Cell(25,3, utf8_decode('Egreso parcial total'),1,0,'L');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 6.5);
$pdf->Cell(30,3, utf8_decode('Balance parcial'),1,0,'C');
$pdf->Ln(8);

//T/A SISTOLICA Y DIASTOLICA SIGNOS VITALES TA TA TA SIGNOS VITALES TA TA SIGNOS VITALES TA SIGNOS VITALES TA
$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $psnueveo=$resp_r['p_sistol'];
  $pdnueveo=$resp_r['p_diastol'];
  $tamo=($psnueveo+$pdnueveo+$pdnueveo)/3;
}
if($psnueveo>0){
$pdf->SetY(90);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($psnueveo . ' / ' .$pdnueveo),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(40);
$pdf->Cell(7,3, number_format($tamo,1),1,0,'C');

}else{
  $pdf->SetY(90);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $psnueve=$resp_r['p_sistol'];
  $pdnueve=$resp_r['p_diastol'];
  $tamn=($psnueve+$pdnueve+$pdnueve)/3;
}
if($psnueve>0){
$pdf->SetY(90);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($psnueve . ' / ' .$pdnueve),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(47);
$pdf->Cell(7,3, number_format($tamn,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='10'  and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $psdiez=$resp_r9['p_sistol'];
  $pddiez=$resp_r9['p_diastol'];
  $tamd=($psdiez+$pdnueve+$pddiez)/3;
}
if($psdiez>0){
$pdf->SetY(90);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($psdiez. ' / ' .$pddiez),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(54);
$pdf->Cell(7,3, number_format($tamd,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
 $pdf->SetY(93);
$pdf->SetX(54);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='11' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_sonce=$resp_r['p_sistol'];
   $p_donce=$resp_r['p_diastol'];
   $tamonc=($p_sonce+$p_donce+$p_donce)/3;
}
if($p_sonce>0){
$pdf->SetY(90);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($p_sonce. ' / ' .$p_donce),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(61);
$pdf->Cell(7,3, number_format($tamonc,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(61);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='12' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_sdocee=$resp_r['p_sistol'];
    $p_ddoce=$resp_r['p_diastol'];
     $tamdoc=($p_sdocee+$p_ddoce+$p_ddoce)/3;
}
if($p_sdocee>0){
$pdf->SetY(90);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($p_sdocee. ' / ' .$p_ddoce),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(68);
$pdf->Cell(7,3, number_format($tamdoc,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(68);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='13' and neonato!='Si' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_strece=$resp_r['p_sistol'];
  $p_dtreces=$resp_r['p_diastol'];
  $tamtr=($p_strece+$p_dtreces+$p_dtreces)/3;
}
if($p_strece>0){
$pdf->SetY(90);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($p_strece . ' / ' . $p_dtreces),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(75);
$pdf->Cell(7,3, number_format($tamtr,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
      $pdf->SetY(93);
$pdf->SetX(75);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='14' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_scat=$resp_r['p_sistol'];
   $p_dcats=$resp_r['p_diastol'];
   $tamcat=($p_scat+$p_dcats+$p_dcats)/3;
}
if($p_scat>0){
$pdf->SetY(90);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($p_scat. ' / ' .$p_dcats),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(82);
$pdf->Cell(7,3, number_format($tamcat,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
    $pdf->SetY(93);
$pdf->SetX(82);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='15' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_squince=$resp_r['p_sistol'];
     $p_dquince=$resp_r['p_diastol'];
       $tamqu=($p_squince+$p_dquince+$p_dquince)/3;
}
if($p_squince>0){
$pdf->SetY(90);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($p_squince. ' / ' .$p_dquince),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(89);
$pdf->Cell(7,3, number_format($tamqu,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(89);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='16' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_sdseis=$resp_r['p_sistol'];
    $p_dseiss=$resp_r['p_diastol'];
     $tamds=($p_sdseis+$p_dseiss+$p_dseiss)/3;
}
if($p_sdseis>0){
$pdf->SetY(90);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($p_sdseis. ' / ' .$p_dseiss),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(96);
$pdf->Cell(7,3, number_format($tamds,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
    $pdf->SetY(93);
$pdf->SetX(96);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='17' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_sdsiete=$resp_r['p_sistol'];
   $p_dsietee=$resp_r['p_diastol'];
   $tamdsie=($p_sdsiete+$p_dsietee+$p_dsietee)/3;
}
if($p_sdsiete>0){
$pdf->SetY(90);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($p_sdsiete. ' / ' .$p_dsietee),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(103);
$pdf->Cell(7,3, number_format($tamdsie,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(103);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='18' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_sdocho=$resp_r['p_sistol'];
  $p_ddocho=$resp_r['p_diastol'];
  $tamdoch=($p_sdocho+$p_ddocho+$p_ddocho)/3;
}
if($p_sdocho>0){
$pdf->SetY(90);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($p_sdocho. ' / ' .$p_ddocho),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(110);
$pdf->Cell(7,3, number_format($tamdoch,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(110);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='19' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_sdnu=$resp_r['p_sistol'];
  $p_ddn=$resp_r['p_diastol'];
  $tamdnueve=($p_sdnu+$p_ddn+$p_ddn)/3;
}
if($p_sdnu>0){
$pdf->SetY(90);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($p_sdnu. ' / ' .$p_ddn),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(117);
$pdf->Cell(7,3, number_format($tamdnueve,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(117);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='20' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_sveinte=$resp_r['p_sistol'];
  $p_dveinte=$resp_r['p_diastol'];
  $tamveinte=($p_sveinte+$p_dveinte+$p_dveinte)/3;
}
if($p_sveinte>0){
$pdf->SetY(90);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($p_sveinte. ' / ' .$p_dveinte),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(124);
$pdf->Cell(7,3, number_format($tamveinte,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(124);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='21' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_svuno=$resp_r['p_sistol'];
    $p_dvuno=$resp_r['p_diastol'];
     $tamveun=($p_svuno+$p_dvuno+$p_dvuno)/3;
}
if($p_svuno>0){
$pdf->SetY(90);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($p_svuno. ' / ' .$p_dvuno),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(131);
$pdf->Cell(7,3, number_format($tamveun,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(131);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='22' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_svds=$resp_r['p_sistol'];
  $p_dvdos=$resp_r['p_diastol'];
  $tamvedos=($p_svds+$p_dvdos+$p_dvdos)/3;
}
if($p_svds>0){
$pdf->SetY(90);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($p_svds. ' / ' .$p_dvdos),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(138);
$pdf->Cell(7,3, number_format($tamvedos,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(138);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='23' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_svt=$resp_r['p_sistol'];
    $p_dvt=$resp_r['p_diastol'];
    $tamvetres=($p_svt+$p_dvt+$p_dvt)/3;
}
if($p_svt>0){
$pdf->SetY(90);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($p_svt. ' / ' .$p_dvt),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(145);
$pdf->Cell(7,3, number_format($tamvetres,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(145);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='24' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_svc=$resp_r['p_sistol'];
   $p_dvc=$resp_r['p_diastol'];
   $tamvecuatro=($p_svc+$p_dvc+$p_dvc)/3;
}
if($p_svc>0){
$pdf->SetY(90);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($p_svc. ' / ' .$p_dvc),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(152);
$pdf->Cell(7,3, number_format($tamvecuatro,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
   $pdf->SetY(93);
$pdf->SetX(152);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='1' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_suno=$resp_r['p_sistol'];
  $p_duno=$resp_r['p_diastol'];
  $tamunoo=($p_suno+$p_duno+$p_duno)/3;
}
if($p_suno>0){
$pdf->SetY(90);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($p_suno. ' / ' .$p_duno),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(159);
$pdf->Cell(7,3, number_format($tamunoo,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
   $pdf->SetY(93);
$pdf->SetX(159);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='2' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_sdos=$resp_r['p_sistol'];
    $p_ddos=$resp_r['p_diastol'];
    $tamdoss=($p_sdos+$p_ddos+$p_ddos)/3;
}
if($p_sdos>0){
$pdf->SetY(90);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($p_sdos. ' / ' .$p_ddos),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(166);
$pdf->Cell(7,3, number_format($tamdoss,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(166);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='3' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_stres=$resp_r['p_sistol'];
  $p_dtres=$resp_r['p_diastol'];
  $tamtress=($p_stres+$p_dtres+$p_dtres)/3;
}
if($p_stres>0){
$pdf->SetY(90);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($p_stres. ' / ' .$p_dtres),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(173);
$pdf->Cell(7,3, number_format($tamtress,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(173);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='4' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_scu=$resp_r['p_sistol'];
  $p_dcu=$resp_r['p_diastol'];
  $tamcuatroo=($p_scu+$p_dcu+$p_dcu)/3;
}
if($p_scu>0){
$pdf->SetY(90);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($p_scu. ' / ' .$p_dcu),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(180);
$pdf->Cell(7,3, number_format($tamcuatroo,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(180);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='5' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_sc=$resp_r['p_sistol'];
  $p_dci=$resp_r['p_diastol'];
  $tamcincoo=($p_sc+$p_dci+$p_dci)/3;
}
if($p_sc>0){
$pdf->SetY(90);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($p_sc. ' / ' .$p_dci),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(187);
$pdf->Cell(7,3, number_format($tamcincoo,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
   $pdf->SetY(93);
$pdf->SetX(187);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='6' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_seis=$resp_r['p_sistol'];
  $p_dseis=$resp_r['p_diastol'];
  $tamseiss=($p_seis+$p_dseis+$p_dseis)/3;
}
if($p_seis>0){
$pdf->SetY(90);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($p_seis. ' / ' .$p_dseis),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(194);
$pdf->Cell(7,3, number_format($tamseiss,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
  $pdf->SetY(93);
$pdf->SetX(194);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='7' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 4.4);
  $p_siete=$resp_r['p_sistol'];
  $p_dsiete=$resp_r['p_diastol'];
  $tamsietee=($p_siete+$p_dsiete+$p_dsiete)/3;
}
if($p_siete>0){
$pdf->SetY(90);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($p_siete. ' / ' .$p_dsiete),1,0,'C');
$pdf->SetFont('Arial', '', 6);
$pdf->SetY(93);
$pdf->SetX(201);
$pdf->Cell(7,3, number_format($tamsietee,1),1,0,'C');
}else{
  $pdf->SetY(90);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
$pdf->SetY(93);
$pdf->SetX(201);
$pdf->Cell(7,3, number_format(''),1,0,'C');
}


//TEMPERATURA TEMPERATURA TEMPERATURA TEMPERATURA TEMPERATURA TEMPERATURA TEMPERATURA TEMPERATURA

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='8' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temnueveeo=$resp_r['temper'];
}
if(isset($temnueveeo)){
$pdf->SetY(96);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($temnueveeo),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='9' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temnuevee=$resp_r['temper'];
}
if(isset($temnuevee)){
$pdf->SetY(96);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($temnuevee),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar'  and  hora='10' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temdiez=$resp_r9['temper'];
}
if(isset($temdiez)){
$pdf->SetY(96);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($temdiez),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and  hora='11' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temoncee=$resp_r['temper'];
}
if(isset($temoncee)){
$pdf->SetY(96);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($temoncee),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and  hora='12' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temdocee=$resp_r['temper'];
}
if(isset($temdocee)){
$pdf->SetY(96);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($temdocee),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and   fecha='$fechar' and  hora='13' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temtrece=$resp_r['temper'];
}
if(isset($temtrece)){
$pdf->SetY(96);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($temtrece),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and  hora='14' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temcatt=$resp_r['temper'];
}
if(isset($temcatt)){
$pdf->SetY(96);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($temcatt),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and  hora='15' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temquince=$resp_r['temper'];
}
if(isset($temquince)){
$pdf->SetY(96);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($temquince),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and  hora='16' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temdseiss=$resp_r['temper'];
}
if(isset($temdseiss)){
$pdf->SetY(96);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($temdseiss),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and  hora='17' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temdsiete=$resp_r['temper'];
}
if(isset($temdsiete)){
$pdf->SetY(96);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($temdsiete),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='18' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tdoc=$resp_r['temper'];
}
if(isset($tdoc)){
$pdf->SetY(96);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($tdoc),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and  hora='19' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temdnuv=$resp_r['temper'];
}
if(isset($temdnuv)){
$pdf->SetY(96);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($temdnuv),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and  hora='20' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temveinte=$resp_r['temper'];
}
if(isset($temveinte)){
$pdf->SetY(96);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($temveinte),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and  hora='21' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temvunod=$resp_r['temper'];
}
if(isset($temvunod)){
$pdf->SetY(96);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($temvunod),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='22' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temvdos=$resp_r['temper'];
}
if(isset($temvdos)){
$pdf->SetY(96);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($temvdos),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='23' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temvtres=$resp_r['temper'];
}
if(isset($temvtres)){
$pdf->SetY(96);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($temvtres),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='24' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temvcuat=$resp_r['temper'];
}
if(isset($temvcuat)){
$pdf->SetY(96);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($temvcuat),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='1' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temuno=$resp_r['temper'];
}
if(isset($temuno)){
$pdf->SetY(96);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($temuno),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='2' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temdos=$resp_r['temper'];
}
if(isset($temdos)){
$pdf->SetY(96);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($temdos),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='3' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temtres=$resp_r['temper'];
}
if(isset($temtres)){
$pdf->SetY(96);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($temtres),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='4' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temcuat=$resp_r['temper'];
}
if(isset($temcuat)){
$pdf->SetY(96);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($temcuat),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='5' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temcinco=$resp_r['temper'];
}
if(isset($temcinco)){
$pdf->SetY(96);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($temcinco),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='6' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temseis=$resp_r['temper'];
}
if(isset($temseis)){
$pdf->SetY(96);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($temseis),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='7' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $temsiete=$resp_r['temper'];
}
if(isset($temsiete)){
$pdf->SetY(96);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($temsiete),1,0,'C');
}else{
  $pdf->SetY(96);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//FRECUENCIA CARDIACA FRECUENCIA CARDIACA FRECUENCIA CARDIACA FRECUENCIA CARDIACA FRECUENCIA CARDIACA FREC CARD

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='8' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fnuevecoc=$resp_r['fcard'];
}
if(isset($fnuevecoc)){
$pdf->SetY(102);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($fnuevecoc),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='9' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fnuevec=$resp_r['fcard'];
}
if(isset($fnuevec)){
$pdf->SetY(102);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($fnuevec),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='10' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcdiez=$resp_r9['fcard'];
}
if(isset($fcdiez)){
$pdf->SetY(102);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($fcdiez),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='11' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fconce=$resp_r['fcard'];
}
if(isset($fconce)){
$pdf->SetY(102);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($fconce),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='12' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcdocee=$resp_r['fcard'];
}
if(isset($fcdocee)){
$pdf->SetY(102);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($fcdocee),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='13' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fctrece=$resp_r['fcard'];
}
if(isset($fctrece)){
$pdf->SetY(102);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($fctrece),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='14' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fccatt=$resp_r['fcard'];
}
if(isset($fccatt)){
$pdf->SetY(102);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($fccatt),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='15' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcdquince=$resp_r['fcard'];
}
if(isset($fcdquince)){
$pdf->SetY(102);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($fcdquince),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='16' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcdsieiss=$resp_r['fcard'];
}
if(isset($fcdsieiss)){
$pdf->SetY(102);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($fcdsieiss),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='17' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcdsiete=$resp_r['fcard'];
}
if(isset($fcdsiete)){
$pdf->SetY(102);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($fcdsiete),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='18' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcdoc=$resp_r['fcard'];
}
if(isset($fcdoc)){
$pdf->SetY(102);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($fcdoc),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='19' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcdn=$resp_r['fcard'];
}
if(isset($fcdn)){
$pdf->SetY(102);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($fcdn),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='20' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcvcc=$resp_r['fcard'];
}
if(isset($fcvcc)){
$pdf->SetY(102);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($fcvcc),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='21' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcvu=$resp_r['fcard'];
}
if(isset($fcvu)){
$pdf->SetY(102);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($fcvu),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='22' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcvd=$resp_r['fcard'];
}
if(isset($fcvd)){
$pdf->SetY(102);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($fcvd),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='23' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcvt=$resp_r['fcard'];
}
if(isset($fcvt)){
$pdf->SetY(102);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($fcvt),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='24' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcvc=$resp_r['fcard'];
}
if(isset($fcvc)){
$pdf->SetY(102);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($fcvc),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='1' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcuno=$resp_r['fcard'];
}
if(isset($fcuno)){
$pdf->SetY(102);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($fcuno),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='2' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcdos=$resp_r['fcard'];
}
if(isset($fcdos)){
$pdf->SetY(102);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($fcdos),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='3' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcrtres=$resp_r['fcard'];
}
if(isset($fcrtres)){
$pdf->SetY(102);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($fcrtres),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='4' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fccuat=$resp_r['fcard'];
}
if(isset($fccuat)){
$pdf->SetY(102);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($fccuat),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='5' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fccinco=$resp_r['fcard'];
}
if(isset($fccinco)){
$pdf->SetY(102);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($fccinco),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='6' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcseis=$resp_r['fcard'];
}
if(isset($fcseis)){
$pdf->SetY(102);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($fcseis),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='7' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcs=$resp_r['fcard'];
}
if(isset($fcs)){
$pdf->SetY(102);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($fcs),1,0,'C');
}else{
  $pdf->SetY(102);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//FRECUENCIA RESPIRATORIA FRECUENCIA RESPIRATORIA FRECUENCIA RESPIRATORIA FRECUENCIA RESPIRATORIA FREC RESP

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='8' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rnuevesoc=$resp_r['fresp'];
}
if(isset($rnuevesoc)){
$pdf->SetY(108);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($rnuevesoc),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='9' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rnueves=$resp_r['fresp'];
}
if(isset($rnueves)){
$pdf->SetY(108);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($rnueves),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='10' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rdiez=$resp_r9['fresp'];
}
if(isset($rdiez)){
$pdf->SetY(108);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($rdiez),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='11' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $roncee=$resp_r['fresp'];
}
if(isset($roncee)){
$pdf->SetY(108);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($roncee),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='12' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rdocee=$resp_r['fresp'];
}
if(isset($rdocee)){
$pdf->SetY(108);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($rdocee),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='13' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rtrecee=$resp_r['fresp'];
}
if(isset($rtrecee)){
$pdf->SetY(108);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($rtrecee),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='14' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rcatorce=$resp_r['fresp'];
}
if(isset($rcatorce)){
$pdf->SetY(108);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($rcatorce),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='15' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rquincee=$resp_r['fresp'];
}
if(isset($rquincee)){
$pdf->SetY(108);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($rquincee),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='16' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rdsesisss=$resp_r['fresp'];
}
if(isset($rdsesisss)){
$pdf->SetY(108);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($rdsesisss),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='17' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rdsietee=$resp_r['fresp'];
}
if(isset($rdsietee)){
$pdf->SetY(108);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($rdsietee),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='18' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rdochoo=$resp_r['fresp'];
}
if(isset($rdochoo)){
$pdf->SetY(108);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($rdochoo),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='19' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rdnuevee=$resp_r['fresp'];
}
if(isset($rdnuevee)){
$pdf->SetY(108);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($rdnuevee),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='20' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rveintee=$resp_r['fresp'];
}
if(isset($rveintee)){
$pdf->SetY(108);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($rveintee),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='21' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rvunoo=$resp_r['fresp'];
}
if(isset($rvunoo)){
$pdf->SetY(108);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($rvunoo),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='22' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rvdoss=$resp_r['fresp'];
}
if(isset($rvdoss)){
$pdf->SetY(108);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($rvdoss),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='23' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rvtress=$resp_r['fresp'];
}
if(isset($rvtress)){
$pdf->SetY(108);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($rvtress),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='24' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rvcuatroo=$resp_r['fresp'];
}
if(isset($rvcuatroo)){
$pdf->SetY(108);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($rvcuatroo),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='1' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $runoo=$resp_r['fresp'];
}
if(isset($runoo)){
$pdf->SetY(108);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($runoo),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='2' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rdoss=$resp_r['fresp'];
}
if(isset($rdoss)){
$pdf->SetY(108);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($rdoss),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='3' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rtress=$resp_r['fresp'];
}
if(isset($rtress)){
$pdf->SetY(108);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($rtress),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='4' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rcautroo=$resp_r['fresp'];
}
if(isset($rcautroo)){
$pdf->SetY(108);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($rcautroo),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='5' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rcincoo=$resp_r['fresp'];
}
if(isset($rcincoo)){
$pdf->SetY(108);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($rcincoo),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='6' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rseiss=$resp_r['fresp'];
}
if(isset($rseiss)){
$pdf->SetY(108);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($rseiss),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='7' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rsietee=$resp_r['fresp'];
}
if(isset($rsietee)){
$pdf->SetY(108);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($rsietee),1,0,'C');
}else{
  $pdf->SetY(108);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//FRECUENCIA CARDIACA FETAL - FRECUENCIA CARDIACA FETAL - FRECUENCIA CARDIACA FETAL - FRECUENCIA CARDIACA FETAL

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='8' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fnuevecoc=$resp_r['fcardf'];
}
if(isset($fnuevecoc)){ 
$pdf->SetY(111);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($fnuevecoc),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='9' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fnuevec=$resp_r['fcardf'];
}
if(isset($fnuevec)){
$pdf->SetY(111);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($fnuevec),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='10' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfdiez=$resp_r9['fcardf'];
}
if(isset($fcfdiez)){
$pdf->SetY(111);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($fcfdiez),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='11' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfonce=$resp_r['fcardf'];
}
if(isset($fcfonce)){
$pdf->SetY(111);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($fcfonce),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='12' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfdocee=$resp_r['fcardf'];
}
if(isset($fcfdocee)){
$pdf->SetY(111);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($fcfdocee),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='13' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcftrece=$resp_r['fcardf'];
}
if(isset($fcftrece)){
$pdf->SetY(111);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($fcftrece),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='14' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfcatt=$resp_r['fcardf'];
}
if(isset($fcfcatt)){
$pdf->SetY(111);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($fcfcatt),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='15' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfdquince=$resp_r['fcardf'];
}
if(isset($fcfdquince)){
$pdf->SetY(111);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($fcfdquince),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='16' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfdsieiss=$resp_r['fcardf'];
}
if(isset($fcfdsieiss)){
$pdf->SetY(111);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($fcfdsieiss),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='17' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfdsiete=$resp_r['fcardf'];
}
if(isset($fcfdsiete)){
$pdf->SetY(111);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($fcfdsiete),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='18' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfdoc=$resp_r['fcardf'];
}
if(isset($fcfdoc)){
$pdf->SetY(111);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($fcfdoc),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='19' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfdn=$resp_r['fcardf'];
}
if(isset($fcfdn)){
$pdf->SetY(111);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($fcfdn),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='20' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfvcc=$resp_r['fcardf'];
}
if(isset($fcfvcc)){
$pdf->SetY(111);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($fcfvcc),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='21' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfvu=$resp_r['fcardf'];
}
if(isset($fcfvu)){
$pdf->SetY(111);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($fcfvu),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='22' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfvd=$resp_r['fcardf'];
}
if(isset($fcfvd)){
$pdf->SetY(111);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($fcfvd),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='23' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfvt=$resp_r['fcardf'];
}
if(isset($fcfvt)){
$pdf->SetY(111);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($fcfvt),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='24' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfvc=$resp_r['fcardf'];
}
if(isset($fcfvc)){
$pdf->SetY(111);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($fcfvc),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='1' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfuno=$resp_r['fcardf'];
}
if(isset($fcfuno)){
$pdf->SetY(111);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($fcfuno),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='2' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfdos=$resp_r['fcardf'];
}
if(isset($fcfdos)){
$pdf->SetY(111);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($fcfdos),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='3' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfrtres=$resp_r['fcardf'];
}
if(isset($fcfrtres)){
$pdf->SetY(111);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($fcfrtres),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='4' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfcuat=$resp_r['fcardf'];
}
if(isset($fcfcuat)){
$pdf->SetY(111);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($fcfcuat),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='5' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfcinco=$resp_r['fcardf'];
}
if(isset($fcfcinco)){
$pdf->SetY(111);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($fcfcinco),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='6' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfseis=$resp_r['fcardf'];
}
if(isset($fcfseis)){
$pdf->SetY(111);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($fcfseis),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='7' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $fcfs=$resp_r['fcardf'];
}
if(isset($fcfs)){
$pdf->SetY(111);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($fcfs),1,0,'C');
}else{
  $pdf->SetY(111);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//SATURACION OXIGENO SATURACION OXIGENO SATURACION OXIGENO SATURACION OXIGENO SATURACION OXIGENO SATURACION 

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='8' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gsoc=$resp_r['satoxi'];
}
if(isset($gsoc)){
$pdf->SetY(114);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($gsoc),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='9' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gs=$resp_r['satoxi'];
}
if(isset($gs)){
$pdf->SetY(114);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($gs),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='10' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdies=$resp_r9['satoxi'];
}
if(isset($gdies)){
$pdf->SetY(114);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($gdies),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='11' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gonce=$resp_r['satoxi'];
}
if(isset($gonce)){
$pdf->SetY(114);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($gonce),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='12' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdoce=$resp_r['satoxi'];
}
if(isset($gdoce)){
$pdf->SetY(114);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($gdoce),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='13' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtrece=$resp_r['satoxi'];
}
if(isset($gtrece)){
$pdf->SetY(114);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($gtrece),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='14' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcat=$resp_r['satoxi'];
}
if(isset($gcat)){
$pdf->SetY(114);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($gcat),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='15' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gquin=$resp_r['satoxi'];
}
if(isset($gquin)){
$pdf->SetY(114);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($gquin),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='16' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdseis=$resp_r['satoxi'];
}
if(isset($gdseis)){
$pdf->SetY(114);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($gdseis),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='17' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdsiete=$resp_r['satoxi'];
}
if(isset($gdsiete)){
$pdf->SetY(114);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($gdsiete),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='18' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdochco=$resp_r['satoxi'];
}
if(isset($gdochco)){
$pdf->SetY(114);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($gdochco),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='19' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdnu=$resp_r['satoxi'];
}
if(isset($gdnu)){
$pdf->SetY(114);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($gdnu),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='20' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gveinte=$resp_r['satoxi'];
}
if(isset($gveinte)){
$pdf->SetY(114);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($gveinte),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='21' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gvuno=$resp_r['satoxi'];
}
if(isset($gvuno)){
$pdf->SetY(114);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($gvuno),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='22' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gvdos=$resp_r['satoxi'];
}
if(isset($gvdos)){
$pdf->SetY(114);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($gvdos),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='23' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gvtres=$resp_r['satoxi'];
}
if(isset($gvtres)){
$pdf->SetY(114);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($gvtres),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='24' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gvcuatro=$resp_r['satoxi'];
}
if(isset($gvcuatro)){
$pdf->SetY(114);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($gvcuatro),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='1' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $guno=$resp_r['satoxi'];
}
if(isset($guno)){
$pdf->SetY(114);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($guno),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='2' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdoss=$resp_r['satoxi'];
}
if(isset($gdoss)){
$pdf->SetY(114);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($gdoss),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='3' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtresss=$resp_r['satoxi'];
}
if(isset($gtresss)){
$pdf->SetY(114);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($gtresss),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='4' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcuatroo=$resp_r['satoxi'];
}
if(isset($gcuatroo)){
$pdf->SetY(114);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($gcuatroo),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='5' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcincoo=$resp_r['satoxi'];
}
if(isset($gcincoo)){
$pdf->SetY(114);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($gcincoo),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='6' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gseisi=$resp_r['satoxi'];
}
if(isset($gseisi)){
$pdf->SetY(114);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($gseisi),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='7' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gsietee=$resp_r['satoxi'];
}
if(isset($gsietee)){
$pdf->SetY(114);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($gsietee),1,0,'C');
}else{
  $pdf->SetY(114);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//SATURACION OXIGENO SATURACION OXIGENO SATURACION OXIGENO SATURACION OXIGENO SATURACION OXIGENO SATURACION 

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='8' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gsoc=$resp_r['niv_dolor'];
}
if(isset($gsoc)){
$pdf->SetY(117);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($gsoc),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='9' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gs=$resp_r['niv_dolor'];
}
if(isset($gs)){
$pdf->SetY(117);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($gs),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='10' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdies=$resp_r9['niv_dolor'];
}
if(isset($gdies)){
$pdf->SetY(117);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($gdies),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='11' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gonce=$resp_r['niv_dolor'];
}
if(isset($gonce)){
$pdf->SetY(117);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($gonce),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='12' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdoce=$resp_r['niv_dolor'];
}
if(isset($gdoce)){
$pdf->SetY(117);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($gdoce),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='13' and neonato!='Si' AND id_atencion=$id_atencion ") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtrece=$resp_r['niv_dolor'];
}
if(isset($gtrece)){
$pdf->SetY(117);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($gtrece),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='14' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcat=$resp_r['niv_dolor'];
}
if(isset($gcat)){
$pdf->SetY(117);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($gcat),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='15' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gquin=$resp_r['niv_dolor'];
}
if(isset($gquin)){
$pdf->SetY(117);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($gquin),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='16' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdseis=$resp_r['niv_dolor'];
}
if(isset($gdseis)){
$pdf->SetY(117);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($gdseis),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='17' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdsiete=$resp_r['niv_dolor'];
}
if(isset($gdsiete)){
$pdf->SetY(117);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($gdsiete),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='18' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdochco=$resp_r['niv_dolor'];
}
if(isset($gdochco)){
$pdf->SetY(117);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($gdochco),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='19' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdnu=$resp_r['niv_dolor'];
}
if(isset($gdnu)){
$pdf->SetY(117);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($gdnu),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='20' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gveinte=$resp_r['niv_dolor'];
}
if(isset($gveinte)){
$pdf->SetY(117);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($gveinte),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='21' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gvuno=$resp_r['niv_dolor'];
}
if(isset($gvuno)){
$pdf->SetY(117);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($gvuno),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='22' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gvdos=$resp_r['niv_dolor'];
}
if(isset($gvdos)){
$pdf->SetY(117);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($gvdos),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='23' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gvtres=$resp_r['niv_dolor'];
}
if(isset($gvtres)){
$pdf->SetY(117);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($gvtres),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='24' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gvcuatro=$resp_r['niv_dolor'];
}
if(isset($gvcuatro)){
$pdf->SetY(117);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($gvcuatro),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='1' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $guno=$resp_r['niv_dolor'];
}
if(isset($guno)){
$pdf->SetY(117);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($guno),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='2' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdoss=$resp_r['niv_dolor'];
}
if(isset($gdoss)){
$pdf->SetY(117);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($gdoss),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='3' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtresss=$resp_r['niv_dolor'];
}
if(isset($gtresss)){
$pdf->SetY(117);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($gtresss),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='4' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcuatroo=$resp_r['niv_dolor'];
}
if(isset($gcuatroo)){
$pdf->SetY(117);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($gcuatroo),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='5' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcincoo=$resp_r['niv_dolor'];
}
if(isset($gcincoo)){
$pdf->SetY(117);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($gcincoo),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='6' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gseisi=$resp_r['niv_dolor'];
}
if(isset($gseisi)){
$pdf->SetY(117);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($gseisi),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from signos_vitales where tipo = 'HOSPITALIZACIÓN' and  fecha='$fechar' and hora='7' and neonato!='Si' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gsietee=$resp_r['niv_dolor'];
}
if(isset($gsietee)){
$pdf->SetY(117);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($gsietee),1,0,'C');
}else{
  $pdf->SetY(117);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

///PVC CM PVC CM PVC CM PVC CM PVC CM PVC CM PVC CM PVC CM PVC CM PCMV
$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $goct=$resp_r['cantidad'];
}
if(isset($goct)){
$pdf->SetY(121);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($goct),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g=$resp_r['cantidad'];
}
if(isset($g)){
$pdf->SetY(121);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($g),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g9=$resp_r9['cantidad'];
}
if(isset($g9)){
$pdf->SetY(121);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($g9),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g10=$resp_r['cantidad'];
}
if(isset($g10)){
$pdf->SetY(121);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($g10),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g11=$resp_r['cantidad'];
}
if(isset($g11)){
$pdf->SetY(121);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($g11),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g12=$resp_r['cantidad'];
}
if(isset($g12)){
$pdf->SetY(121);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($g12),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g13=$resp_r['cantidad'];
}
if(isset($g13)){
$pdf->SetY(121);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($g13),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g14=$resp_r['cantidad'];
}
if(isset($g14)){
$pdf->SetY(121);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($g14),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g15=$resp_r['cantidad'];
}
if(isset($g15)){
$pdf->SetY(121);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($g15),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g16=$resp_r['cantidad'];
}
if(isset($g16)){
$pdf->SetY(121);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($g16),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g17=$resp_r['cantidad'];
}
if(isset($g17)){
$pdf->SetY(121);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($g17),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g18=$resp_r['cantidad'];
}
if(isset($g18)){
$pdf->SetY(121);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($g18),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g19=$resp_r['cantidad'];
}
if(isset($g19)){
$pdf->SetY(121);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($g19),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g20=$resp_r['cantidad'];
}
if(isset($g20)){
$pdf->SetY(121);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($g20),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g21=$resp_r['cantidad'];
}
if(isset($g21)){
$pdf->SetY(121);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($g21),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g22=$resp_r['cantidad'];
}
if(isset($g22)){
$pdf->SetY(121);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($g22),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g23=$resp_r['cantidad'];
}
if(isset($g23)){
$pdf->SetY(121);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($g23),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g24=$resp_r['cantidad'];
}
if(isset($g24)){
$pdf->SetY(121);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($g24),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g01=$resp_r['cantidad'];
}
if(isset($g01)){
$pdf->SetY(121);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($g01),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g02=$resp_r['cantidad'];
}
if(isset($g02)){
$pdf->SetY(121);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($g02),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g03=$resp_r['cantidad'];
}
if(isset($g03)){
$pdf->SetY(121);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($g03),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g04=$resp_r['cantidad'];
}
if(isset($g04)){
$pdf->SetY(121);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($g04),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g05=$resp_r['cantidad'];
}
if(isset($g05)){
$pdf->SetY(121);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($g05),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Presion venosa central' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g06=$resp_r['cantidad'];
}
if(isset($g06)){
$pdf->SetY(121);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($g06),1,0,'C');
}else{
  $pdf->SetY(121);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//PERIMETROS PERIMETROS PERIMETROS PERIMETROS PERIMETROS PERIMETROS PERIMETROS

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $peroc=$resp_r['cantidad'];
}
if(isset($peroc)){
$pdf->SetY(127);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($peroc),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nuevp=$resp_r['cantidad'];
}
if(isset($nuevp)){
$pdf->SetY(127);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($nuevp),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diezper=$resp_r9['cantidad'];
}
if(isset($diezper)){
$pdf->SetY(127);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($diezper),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $paon=$resp_r['cantidad'];
}
if(isset($paon)){
$pdf->SetY(127);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($paon),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $padoc=$resp_r['cantidad'];
}
if(isset($padoc)){
$pdf->SetY(127);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($padoc),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $patre=$resp_r['cantidad'];
}
if(isset($patre)){
$pdf->SetY(127);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($patre),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $padatc=$resp_r['cantidad'];
}
if(isset($padatc)){
$pdf->SetY(127);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($padatc),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabquin=$resp_r['cantidad'];
}
if(isset($pabquin)){
$pdf->SetY(127);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($pabquin),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabdsei=$resp_r['cantidad'];
}
if(isset($pabdsei)){
$pdf->SetY(127);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($pabdsei),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabdsiete=$resp_r['cantidad'];
}
if(isset($pabdsiete)){
$pdf->SetY(127);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($pabdsiete),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabdoch=$resp_r['cantidad'];
}
if(isset($pabdoch)){
$pdf->SetY(127);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($pabdoch),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabnueveeeee=$resp_r['cantidad'];
}
if(isset($pabnueveeeee)){
$pdf->SetY(127);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($pabnueveeeee),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabvvvv=$resp_r['cantidad'];
}
if(isset($pabvvvv)){
$pdf->SetY(127);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($pabvvvv),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pavvvvun=$resp_r['cantidad'];
}
if(isset($pavvvvun)){
$pdf->SetY(127);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($pavvvvun),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabmila=$resp_r['cantidad'];
}
if(isset($pabmila)){
$pdf->SetY(127);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($pabmila),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabveintreee=$resp_r['cantidad'];
}
if(isset($pabveintreee)){
$pdf->SetY(127);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($pabveintreee),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pabvtr=$resp_r['cantidad'];
}
if(isset($pabvtr)){
$pdf->SetY(127);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($pabvtr),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $perabdl=$resp_r['cantidad'];
}
if(isset($perabdl)){
$pdf->SetY(127);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($perabdl),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $al1=$resp_r['cantidad'];
}
if(isset($al1)){
$pdf->SetY(127);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($al1),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $al2=$resp_r['cantidad'];
}
if(isset($al2)){
$pdf->SetY(127);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($al2),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $altres=$resp_r['cantidad'];
}
if(isset($altres)){
$pdf->SetY(127);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($altres),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $alcuatro=$resp_r['cantidad'];
}
if(isset($alcuatro)){
$pdf->SetY(127);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($alcuatro),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $persssss=$resp_r['cantidad'];
}
if(isset($persssss)){
$pdf->SetY(127);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($persssss),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Perimetro abdominal' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $pssssis=$resp_r['cantidad'];
}
if(isset($pssssis)){
$pdf->SetY(127);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($pssssis),1,0,'C');
}else{
  $pdf->SetY(127);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//GLICEMIA CAPILAR GLICEMIA CAPILAR GLICEMIA CAPÍLAR

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $glicoct=$resp_r['cantidad'];
}
if(isset($glicoct)){
$pdf->SetY(133);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($glicoct),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g91=$resp_r['cantidad'];
}
if(isset($g91)){
$pdf->SetY(133);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($g91),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g92=$resp_r9['cantidad'];
}
if(isset($g92)){
$pdf->SetY(133);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($g92),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g93=$resp_r['cantidad'];
}
if(isset($g93)){
$pdf->SetY(133);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($g93),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g194=$resp_r['cantidad'];
}
if(isset($g194)){
$pdf->SetY(133);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($g194),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g126=$resp_r['cantidad'];
}
if(isset($g126)){
$pdf->SetY(133);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($g126),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g134=$resp_r['cantidad'];
}
if(isset($g134)){
$pdf->SetY(133);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($g134),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g140d=$resp_r['cantidad'];
}
if(isset($g140d)){
$pdf->SetY(133);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($g140d),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $caglic=$resp_r['cantidad'];
}
if(isset($caglic)){
$pdf->SetY(133);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($caglic),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cg01=$resp_r['cantidad'];
}
if(isset($cg01)){
$pdf->SetY(133);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($cg01),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cg02=$resp_r['cantidad'];
}
if(isset($cg02)){
$pdf->SetY(133);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($cg02),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cgl03=$resp_r['cantidad'];
}
if(isset($cgl03)){
$pdf->SetY(133);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($cgl03),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ch04=$resp_r['cantidad'];
}
if(isset($ch04)){
$pdf->SetY(133);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($ch04),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cgl05=$resp_r['cantidad'];
}
if(isset($cgl05)){
$pdf->SetY(133);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($cgl05),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cgl06=$resp_r['cantidad'];
}
if(isset($cgl06)){
$pdf->SetY(133);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($cgl06),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $c94554=$resp_r['cantidad'];
}
if(isset($c94554)){
$pdf->SetY(133);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($c94554),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g_l=$resp_r['cantidad'];
}
if(isset($g_l)){
$pdf->SetY(133);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($g_l),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cgli777=$resp_r['cantidad'];
}
if(isset($cgli777)){
$pdf->SetY(133);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($cgli777),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ge01=$resp_r['cantidad'];
}
if(isset($ge01)){
$pdf->SetY(133);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($ge01),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gg02=$resp_r['cantidad'];
}
if(isset($gg02)){
$pdf->SetY(133);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($gg02),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gt03=$resp_r['cantidad'];
}
if(isset($gt03)){
$pdf->SetY(133);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($gt03),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g04cglcin=$resp_r['cantidad'];
}
if(isset($g04cglcin)){
$pdf->SetY(133);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($g04cglcin),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cantidikdfdg=$resp_r['cantidad'];
}
if(isset($cantidikdfdg)){
$pdf->SetY(133);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($cantidikdfdg),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Glicemia capilar' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rrrglc=$resp_r['cantidad'];
}
if(isset($rrrglc)){
$pdf->SetY(133);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($rrrglc),1,0,'C');
}else{
  $pdf->SetY(133);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//INSULINA INSULINA INSULINA INSULINAINSULINA INSULINA INSULINA INSULINA INSLINA

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins8=$resp_r['cantidad'];
}
if(isset($ins8)){
$pdf->SetY(139);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($ins8),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins9=$resp_r['cantidad'];
}
if(isset($ins9)){
$pdf->SetY(139);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($ins9),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins10=$resp_r9['cantidad'];
}
if(isset($ins10)){
$pdf->SetY(139);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ins10),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins11=$resp_r['cantidad'];
}
if(isset($ins11)){
$pdf->SetY(139);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($ins11),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins12=$resp_r['cantidad'];
}
if(isset($ins12)){
$pdf->SetY(139);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ins12),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins13=$resp_r['cantidad'];
}
if(isset($ins13)){
$pdf->SetY(139);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ins13),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins14=$resp_r['cantidad'];
}
if(isset($ins14)){
$pdf->SetY(139);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($ins14),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins15=$resp_r['cantidad'];
}
if(isset($ins15)){
$pdf->SetY(139);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($ins15),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins16=$resp_r['cantidad'];
}
if(isset($ins16)){
$pdf->SetY(139);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ins16),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins17=$resp_r['cantidad'];
}
if(isset($ins17)){
$pdf->SetY(139);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ins17),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins18=$resp_r['cantidad'];
}
if(isset($ins18)){
$pdf->SetY(139);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ins18),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins19=$resp_r['cantidad'];
}
if(isset($ins19)){
$pdf->SetY(139);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ins19),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins20=$resp_r['cantidad'];
}
if(isset($ins20)){
$pdf->SetY(139);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($ins20),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins21=$resp_r['cantidad'];
}
if(isset($ins21)){
$pdf->SetY(139);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($ins21),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins22=$resp_r['cantidad'];
}
if(isset($ins22)){
$pdf->SetY(139);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($ins22),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins23=$resp_r['cantidad'];
}
if(isset($ins23)){
$pdf->SetY(139);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($ins23),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins24=$resp_r['cantidad'];
}
if(isset($ins24)){
$pdf->SetY(139);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($ins24),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins1=$resp_r['cantidad'];
}
if(isset($ins1)){
$pdf->SetY(139);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($ins1),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins2=$resp_r['cantidad'];
}
if(isset($ins2)){
$pdf->SetY(139);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ins2),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins3=$resp_r['cantidad'];
}
if(isset($ins3)){
$pdf->SetY(139);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ins3),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins4=$resp_r['cantidad'];
}
if(isset($ins4)){
$pdf->SetY(139);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($ins4),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins5=$resp_r['cantidad'];
}
if(isset($ins5)){
$pdf->SetY(139);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($ins5),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins6=$resp_r['cantidad'];
}
if(isset($ins6)){
$pdf->SetY(139);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($ins6),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Insulina' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins7=$resp_r['cantidad'];
}
if(isset($ins7)){
$pdf->SetY(139);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($ins7),1,0,'C');
}else{
  $pdf->SetY(139);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//Dextrosa al 50% Dextrosa al 50% Dextrosa al 50% Dextrosa al 50% Dextrosa al 50% Dextrosa al 50% Dextrosa al 50% Dextrosa al 50% Dextrosa al 50%

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins8de=$resp_r['cantidad'];
}
if(isset($ins8de)){
$pdf->SetY(142);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($ins8de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins9de=$resp_r['cantidad'];
}
if(isset($ins9de)){
$pdf->SetY(142);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($ins9de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins10de=$resp_r9['cantidad'];
}
if(isset($ins10de)){
$pdf->SetY(142);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ins10de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins11de=$resp_r['cantidad'];
}
if(isset($ins11de)){
$pdf->SetY(142);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($ins11de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins12de=$resp_r['cantidad'];
}
if(isset($ins12de)){
$pdf->SetY(142);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ins12de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins13de=$resp_r['cantidad'];
}
if(isset($ins13de)){
$pdf->SetY(142);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ins13de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins14de=$resp_r['cantidad'];
}
if(isset($ins14de)){
$pdf->SetY(142);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($ins14de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins15de=$resp_r['cantidad'];
}
if(isset($ins15de)){
$pdf->SetY(142);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($ins15de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins16de=$resp_r['cantidad'];
}
if(isset($ins16de)){
$pdf->SetY(142);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ins16de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins17de=$resp_r['cantidad'];
}
if(isset($ins17de)){
$pdf->SetY(142);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ins17de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins18de=$resp_r['cantidad'];
}
if(isset($ins18de)){
$pdf->SetY(142);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ins18de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins19de=$resp_r['cantidad'];
}
if(isset($ins19de)){
$pdf->SetY(142);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ins19de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins20de=$resp_r['cantidad'];
}
if(isset($ins20de)){
$pdf->SetY(142);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($ins20de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins21de=$resp_r['cantidad'];
}
if(isset($ins21de)){
$pdf->SetY(142);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($ins21de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins22de=$resp_r['cantidad'];
}
if(isset($ins22de)){
$pdf->SetY(142);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($ins22de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins23de=$resp_r['cantidad'];
}
if(isset($ins23de)){
$pdf->SetY(142);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($ins23de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins24de=$resp_r['cantidad'];
}
if(isset($ins24de)){
$pdf->SetY(142);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($ins24de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins1de=$resp_r['cantidad'];
}
if(isset($ins1de)){
$pdf->SetY(142);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($ins1de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins2de=$resp_r['cantidad'];
}
if(isset($ins2de)){
$pdf->SetY(142);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ins2de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins3de=$resp_r['cantidad'];
}
if(isset($ins3de)){
$pdf->SetY(142);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ins3de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins4de=$resp_r['cantidad'];
}
if(isset($ins4de)){
$pdf->SetY(142);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($ins4de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins5de=$resp_r['cantidad'];
}
if(isset($ins5de)){
$pdf->SetY(142);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($ins5de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins6de=$resp_r['cantidad'];
}
if(isset($ins6de)){
$pdf->SetY(142);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($ins6de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Dextrosa' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ins7de=$resp_r['cantidad'];
}
if(isset($ins7de)){
$pdf->SetY(142);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($ins7de),1,0,'C');
}else{
  $pdf->SetY(142);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//OXIGEOTERAPIA OXIGENOTERAPIA OXIGENO TERAPIA OXIGENO TERAPIA OXIGENO TERAPIA

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox8=$resp_r['cantidad'];
}
if(isset($ox8)){
$pdf->SetY(145);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($ox8),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox9=$resp_r['cantidad'];
}
if(isset($ox9)){
$pdf->SetY(145);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($ox9),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox10=$resp_r9['cantidad'];
}
if(isset($ox10)){
$pdf->SetY(145);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($ox10),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox11=$resp_r['cantidad'];
}
if(isset($ox11)){
$pdf->SetY(145);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($ox11),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox12=$resp_r['cantidad'];
}
if(isset($ox12)){
$pdf->SetY(145);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($ox12),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox13=$resp_r['cantidad'];
}
if(isset($ox13)){
$pdf->SetY(145);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($ox13),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox14=$resp_r['cantidad'];
}
if(isset($ox14)){
$pdf->SetY(145);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($ox14),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox15=$resp_r['cantidad'];
}
if(isset($ox15)){
$pdf->SetY(145);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($ox15),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox16=$resp_r['cantidad'];
}
if(isset($ox16)){
$pdf->SetY(145);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($ox16),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox17=$resp_r['cantidad'];
}
if(isset($ox17)){
$pdf->SetY(145);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($ox17),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox18=$resp_r['cantidad'];
}
if(isset($ox18)){
$pdf->SetY(145);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($ox18),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox19=$resp_r['cantidad'];
}
if(isset($ox19)){
$pdf->SetY(145);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($ox19),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox20=$resp_r['cantidad'];
}
if(isset($ox20)){
$pdf->SetY(145);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($ox20),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox21=$resp_r['cantidad'];
}
if(isset($ox21)){
$pdf->SetY(145);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($ox21),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox22=$resp_r['cantidad'];
}
if(isset($ox22)){
$pdf->SetY(145);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($ox22),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox23=$resp_r['cantidad'];
}
if(isset($ox23)){
$pdf->SetY(145);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($ox23),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox24=$resp_r['cantidad'];
}
if(isset($ox24)){
$pdf->SetY(145);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($ox24),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox1=$resp_r['cantidad'];
}
if(isset($ox1)){
$pdf->SetY(145);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($ox1),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox2=$resp_r['cantidad'];
}
if(isset($ox2)){
$pdf->SetY(145);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($ox2),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox3=$resp_r['cantidad'];
}
if(isset($ox3)){
$pdf->SetY(145);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($ox3),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox4=$resp_r['cantidad'];
}
if(isset($ox4)){
$pdf->SetY(145);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($ox4),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox5=$resp_r['cantidad'];
}
if(isset($ox5)){
$pdf->SetY(145);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($ox5),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox6=$resp_r['cantidad'];
}
if(isset($ox6)){
$pdf->SetY(145);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($ox6),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Oxigenoterapia' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ox7=$resp_r['cantidad'];
}
if(isset($ox7)){
$pdf->SetY(145);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($ox7),1,0,'C');
}else{
  $pdf->SetY(145);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//micronebulizaciones micronebulizaciones micro nebulizaciones

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul8=$resp_r['cantidad'];
}
if(isset($bul8)){
$pdf->SetY(151);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($bul8),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul9=$resp_r['cantidad'];
}
if(isset($bul9)){
$pdf->SetY(151);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($bul9),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul10=$resp_r9['cantidad'];
}
if(isset($bul10)){
$pdf->SetY(151);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($bul10),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul11=$resp_r['cantidad'];
}
if(isset($bul11)){
$pdf->SetY(151);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($bul11),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul12=$resp_r['cantidad'];
}
if(isset($bul12)){
$pdf->SetY(151);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($bul12),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul13=$resp_r['cantidad'];
}
if(isset($bul13)){
$pdf->SetY(151);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($bul13),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul14=$resp_r['cantidad'];
}
if(isset($bul14)){
$pdf->SetY(151);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($bul14),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul15=$resp_r['cantidad'];
}
if(isset($bul15)){
$pdf->SetY(151);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($bul15),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul16=$resp_r['cantidad'];
}
if(isset($bul16)){
$pdf->SetY(151);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($bul16),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul17=$resp_r['cantidad'];
}
if(isset($bul17)){
$pdf->SetY(151);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($bul17),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul18=$resp_r['cantidad'];
}
if(isset($bul18)){
$pdf->SetY(151);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($bul18),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul19=$resp_r['cantidad'];
}
if(isset($bul19)){
$pdf->SetY(151);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($bul19),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul20=$resp_r['cantidad'];
}
if(isset($bul20)){
$pdf->SetY(151);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($bul20),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul21=$resp_r['cantidad'];
}
if(isset($bul21)){
$pdf->SetY(151);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($bul21),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul22=$resp_r['cantidad'];
}
if(isset($bul22)){
$pdf->SetY(151);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($bul22),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul23=$resp_r['cantidad'];
}
if(isset($bul23)){
$pdf->SetY(151);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($bul23),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul24=$resp_r['cantidad'];
}
if(isset($bul24)){
$pdf->SetY(151);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($bul24),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul1=$resp_r['cantidad'];
}
if(isset($bul1)){
$pdf->SetY(151);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($bul1),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul2=$resp_r['cantidad'];
}
if(isset($bul2)){
$pdf->SetY(151);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($bul2),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul3=$resp_r['cantidad'];
}
if(isset($bul3)){
$pdf->SetY(151);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($bul3),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul4=$resp_r['cantidad'];
}
if(isset($bul4)){
$pdf->SetY(151);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($bul4),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul5=$resp_r['cantidad'];
}
if(isset($bul5)){
$pdf->SetY(151);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($bul5),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul6=$resp_r['cantidad'];
}
if(isset($bul6)){
$pdf->SetY(151);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($bul6),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Micronebulizaciones' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bul7=$resp_r['cantidad'];
}
if(isset($bul7)){
$pdf->SetY(151);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($bul7),1,0,'C');
}else{
  $pdf->SetY(151);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

//neurlogico neurologico neurologico neurokogico neurlogico neurologico

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur8=$resp_r['cantidad'];
}
if(isset($neur8)){
$pdf->SetY(157);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($neur8),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu9=$resp_r['cantidad'];
}
if(isset($neu9)){
$pdf->SetY(157);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($neu9),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu10=$resp_r9['cantidad'];
}
if(isset($neu10)){
$pdf->SetY(157);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($neu10),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu11=$resp_r['cantidad'];
}
if(isset($neu11)){
$pdf->SetY(157);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($neu11),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu12=$resp_r['cantidad'];
}
if(isset($neu12)){
$pdf->SetY(157);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($neu12),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu13=$resp_r['cantidad'];
}
if(isset($neu13)){
$pdf->SetY(157);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($neu13),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu14=$resp_r['cantidad'];
}
if(isset($neu14)){
$pdf->SetY(157);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($neu14),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu15=$resp_r['cantidad'];
}
if(isset($neu15)){
$pdf->SetY(157);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($neu15),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu16=$resp_r['cantidad'];
}
if(isset($neu16)){
$pdf->SetY(157);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($neu16),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu17=$resp_r['cantidad'];
}
if(isset($neu17)){
$pdf->SetY(157);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($neu17),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu18=$resp_r['cantidad'];
}
if(isset($neu18)){
$pdf->SetY(157);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($neu18),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu19=$resp_r['cantidad'];
}
if(isset($neu19)){
$pdf->SetY(157);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($neu19),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu20=$resp_r['cantidad'];
}
if(isset($neu20)){
$pdf->SetY(157);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($neu20),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu21=$resp_r['cantidad'];
}
if(isset($neu21)){
$pdf->SetY(157);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($neu21),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu22=$resp_r['cantidad'];
}
if(isset($neu22)){
$pdf->SetY(157);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($neu22),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu23=$resp_r['cantidad'];
}
if(isset($neu23)){
$pdf->SetY(157);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($neu23),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu24=$resp_r['cantidad'];
}
if(isset($neu24)){
$pdf->SetY(157);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($neu24),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu1=$resp_r['cantidad'];
}
if(isset($neu1)){
$pdf->SetY(157);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($neu1),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu2=$resp_r['cantidad'];
}
if(isset($neu2)){
$pdf->SetY(157);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($neu2),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu3=$resp_r['cantidad'];
}
if(isset($neu3)){
$pdf->SetY(157);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($neu3),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu4=$resp_r['cantidad'];
}
if(isset($neu4)){
$pdf->SetY(157);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($neu4),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu5=$resp_r['cantidad'];
}
if(isset($neu5)){
$pdf->SetY(157);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($neu5),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu6=$resp_r['cantidad'];
}
if(isset($neu6)){
$pdf->SetY(157);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($neu6),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Neurologico' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu7=$resp_r['cantidad'];
}
if(isset($neu7)){
$pdf->SetY(157);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($neu7),1,0,'C');
}else{
  $pdf->SetY(157);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//ENEMA ENEMA ENEMA ENEMA ENEMA ENEMA ENEMA ENEMA ENEMA ENEMA

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='8' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neur8a=$resp_r['cantidad'];
}
if(isset($neur8a)){
$pdf->SetY(160);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($neur8a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='9' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu9a=$resp_r['cantidad'];
}
if(isset($neu9a)){
$pdf->SetY(160);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($neu9a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='10' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu10a=$resp_r9['cantidad'];
}
if(isset($neu10a)){
$pdf->SetY(160);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($neu10a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='11' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu11a=$resp_r['cantidad'];
}
if(isset($neu11a)){
$pdf->SetY(160);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($neu11a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='12' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu12a=$resp_r['cantidad'];
}
if(isset($neu12a)){
$pdf->SetY(160);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($neu12a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='13' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu13a=$resp_r['cantidad'];
}
if(isset($neu13a)){
$pdf->SetY(160);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($neu13a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='14' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu14a=$resp_r['cantidad'];
}
if(isset($neu14a)){
$pdf->SetY(160);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($neu14a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='15' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu15a=$resp_r['cantidad'];
}
if(isset($neu15a)){
$pdf->SetY(160);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($neu15a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='16' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu16a=$resp_r['cantidad'];
}
if(isset($neu16a)){
$pdf->SetY(160);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($neu16a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='17' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu17a=$resp_r['cantidad'];
}
if(isset($neu17a)){
$pdf->SetY(160);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($neu17a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='18' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu18a=$resp_r['cantidad'];
}
if(isset($neu18a)){
$pdf->SetY(160);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($neu18a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='19' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu19a=$resp_r['cantidad'];
}
if(isset($neu19a)){
$pdf->SetY(160);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($neu19a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='20' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu20a=$resp_r['cantidad'];
}
if(isset($neu20a)){
$pdf->SetY(160);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($neu20a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='21' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu21a=$resp_r['cantidad'];
}
if(isset($neu21a)){
$pdf->SetY(160);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($neu21a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='22' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu22a=$resp_r['cantidad'];
}
if(isset($neu22a)){
$pdf->SetY(160);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($neu22a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='23' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu23a=$resp_r['cantidad'];
}
if(isset($neu23a)){
$pdf->SetY(160);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($neu23a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='24' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu24a=$resp_r['cantidad'];
}
if(isset($neu24a)){
$pdf->SetY(160);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($neu24a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='1' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu1a=$resp_r['cantidad'];
}
if(isset($neu1a)){
$pdf->SetY(160);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($neu1a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='2' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu2a=$resp_r['cantidad'];
}
if(isset($neu2a)){
$pdf->SetY(160);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($neu2a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='3' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu3a=$resp_r['cantidad'];
}
if(isset($neu3a)){
$pdf->SetY(160);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($neu3a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='4' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu4a=$resp_r['cantidad'];
}
if(isset($neu4a)){
$pdf->SetY(160);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($neu4a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='5' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu5a=$resp_r['cantidad'];
}
if(isset($neu5a)){
$pdf->SetY(160);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($neu5a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='6' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu6a=$resp_r['cantidad'];
}
if(isset($neu6a)){
$pdf->SetY(160);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($neu6a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from monitoreo_sust where tipo='Enema' and fecha='$fechar' and hora='7' AND id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $neu7a=$resp_r['cantidad'];
}
if(isset($neu7a)){
$pdf->SetY(160);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($neu7a),1,0,'C');
}else{
  $pdf->SetY(160);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//tipo de dieta TIPO DE DIETA TIPO DE DIETA TIPO DE DIETA TIPO DE DIETA
$tipodieta_mat=" ";

$pdf->SetY(164);
$pdf->SetX(40);
$sat = $conexion->query("select * from enf_reg_clin where fecha_mat='$fechar' and turno='MATUTINO' AND id_atencion=$id_atencion ORDER by tipodieta_mat DESC LIMIT 1") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
    $tipodieta_mat=$sat_r['tipodieta_mat'];
   


}
$pdf->Cell(49,3, utf8_decode('MATUTINO: '.$tipodieta_mat),1,0,'C');
$pdf->SetY(164);
$pdf->SetX(89);
$sat = $conexion->query("select * from enf_reg_clin where fecha_mat='$fechar' and turno='VESPERTINO' AND id_atencion=$id_atencion ORDER by tipodieta_mat DESC LIMIT 1") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {
  $tipodieta_mat=$sat_r['tipodieta_mat'];
  $pdf->SetFont('Arial', '', 6);
}
$pdf->Cell(42,3, utf8_decode('VESPERTINO: '.$tipodieta_mat),1,0,'C');

$pdf->SetY(164);
$pdf->SetX(131);
$sat = $conexion->query("select * from enf_reg_clin where fecha_mat='$fechar'and turno='NOCTURNO' AND id_atencion=$id_atencion ORDER by tipodieta_mat DESC LIMIT 1") or die($conexion->error);
while ($sat_r = $sat->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
 $tipodieta_mat=$sat_r['tipodieta_mat'];

}
$pdf->Cell(77,3, utf8_decode('NOCTURNO: '.$tipodieta_mat),1,0,'C');


//infusion  infusion 2 infusion 2infusion  infusion 2 infusion 2 infusion  infusion 2 infusion 2 infusion  infusion 2 infusion 2 infusion  infusion 2 infusion 2

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gocti=$resp_r['cantidad'];
}
if(isset($gocti)){
$pdf->SetY(167);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($gocti),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gi=$resp_r['cantidad'];
}
if(isset($gi)){
$pdf->SetY(167);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($gi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ggi=$resp_r9['cantidad'];
}
if(isset($ggi)){
$pdf->SetY(167);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ggi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gggi=$resp_r['cantidad'];
}
if(isset($gggi)){
$pdf->SetY(167);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($gggi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdi=$resp_r['cantidad'];
}
if(isset($gdi)){
$pdf->SetY(167);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($gdi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtri=$resp_r['cantidad'];
}
if(isset($gtri)){
$pdf->SetY(167);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($gtri),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcai=$resp_r['cantidad'];
}
if(isset($gcai)){
$pdf->SetY(167);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($gcai),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $qui=$resp_r['cantidad'];
}
if(isset($qui)){
$pdf->SetY(167);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($qui),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diessi=$resp_r['cantidad'];
}
if(isset($diessi)){
$pdf->SetY(167);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($diessi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsi=$resp_r['cantidad'];
}
if(isset($dsi)){
$pdf->SetY(167);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($dsi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dci=$resp_r['cantidad'];
}
if(isset($dci)){
$pdf->SetY(167);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($dci),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dni=$resp_r['cantidad'];
}
if(isset($dni)){
$pdf->SetY(167);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($dni),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vei=$resp_r['cantidad'];
}
if(isset($vei)){
$pdf->SetY(167);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vei),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vui=$resp_r['cantidad'];
}
if(isset($vui)){
$pdf->SetY(167);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vui),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdi=$resp_r['cantidad'];
}
if(isset($vdi)){
$pdf->SetY(167);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vdi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vti=$resp_r['cantidad'];
}
if(isset($vti)){
$pdf->SetY(167);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vti),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vci=$resp_r['cantidad'];
}
if(isset($vci)){
$pdf->SetY(167);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vci),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $uni=$resp_r['cantidad'];
}
if(isset($uni)){
$pdf->SetY(167);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($uni),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dosi=$resp_r['cantidad'];
}
if(isset($dosi)){
$pdf->SetY(167);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($dosi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tresi=$resp_r['cantidad'];
}
if(isset($tresi)){
$pdf->SetY(167);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($tresi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='4' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cuati=$resp_r['cantidad'];
}
if(isset($cuati)){
$pdf->SetY(167);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($cuati),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cini=$resp_r['cantidad'];
}
if(isset($cini)){
$pdf->SetY(167);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($cini),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $seisi=$resp_r['cantidad'];
}
if(isset($seisi)){
$pdf->SetY(167);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($seisi),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 2' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sieti=$resp_r['cantidad'];
}
if(isset($sieti)){
$pdf->SetY(167);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sieti),1,0,'C');
}else{
  $pdf->SetY(167);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//via ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL VIA ORAL
$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $goct=$resp_r['cantidad'];
}
if(isset($goct)){
$pdf->SetY(170);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($goct),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $g=$resp_r['cantidad'];
}
if(isset($g)){
$pdf->SetY(170);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($g),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gg=$resp_r9['cantidad'];
}
if(isset($gg)){
$pdf->SetY(170);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($gg),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ggg=$resp_r['cantidad'];
}
if(isset($ggg)){
$pdf->SetY(170);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($ggg),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gd=$resp_r['cantidad'];
}
if(isset($gd)){
$pdf->SetY(170);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($gd),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtr=$resp_r['cantidad'];
}
if(isset($gtr)){
$pdf->SetY(170);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($gtr),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gca=$resp_r['cantidad'];
}
if(isset($gca)){
$pdf->SetY(170);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($gca),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $qu=$resp_r['cantidad'];
}
if(isset($qu)){
$pdf->SetY(170);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($qu),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diess=$resp_r['cantidad'];
}
if(isset($diess)){
$pdf->SetY(170);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($diess),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ds=$resp_r['cantidad'];
}
if(isset($ds)){
$pdf->SetY(170);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ds),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dc=$resp_r['cantidad'];
}
if(isset($dc)){
$pdf->SetY(170);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($dc),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dn=$resp_r['cantidad'];
}
if(isset($dn)){
$pdf->SetY(170);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($dn),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ve=$resp_r['cantidad'];
}
if(isset($ve)){
$pdf->SetY(170);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($ve),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vu=$resp_r['cantidad'];
}
if(isset($vu)){
$pdf->SetY(170);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vu),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vd=$resp_r['cantidad'];
}
if(isset($vd)){
$pdf->SetY(170);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vd),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vt=$resp_r['cantidad'];
}
if(isset($vt)){
$pdf->SetY(170);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vt),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vc=$resp_r['cantidad'];
}
if(isset($vc)){
$pdf->SetY(170);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vc),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $un=$resp_r['cantidad'];
}
if(isset($un)){
$pdf->SetY(170);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($un),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dos=$resp_r['cantidad'];
}
if(isset($dos)){
$pdf->SetY(170);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($dos),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tres=$resp_r['cantidad'];
}
if(isset($tres)){
$pdf->SetY(170);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($tres),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cuat=$resp_r['cantidad'];
}
if(isset($cuat)){
$pdf->SetY(170);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($cuat),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cin=$resp_r['cantidad'];
}
if(isset($cin)){
$pdf->SetY(170);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($cin),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $seis=$resp_r['cantidad'];
}
if(isset($seis)){
$pdf->SetY(170);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($seis),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $siet=$resp_r['cantidad'];
}
if(isset($siet)){
$pdf->SetY(170);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($siet),1,0,'C');
}else{
  $pdf->SetY(170);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}



// infusion 3 infusion  infusion 3 infusion 3 infusion 3 infusion  infusion 3 infusion 3

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gocti3=$resp_r['cantidad'];
}
if(isset($gocti3)){
$pdf->SetY(173);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($gocti3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gi3=$resp_r['cantidad'];
}
if(isset($gi3)){
$pdf->SetY(173);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($gi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ggi3=$resp_r9['cantidad'];
}
if(isset($ggi3)){
$pdf->SetY(173);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ggi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gggi3=$resp_r['cantidad'];
}
if(isset($gggi3)){
$pdf->SetY(173);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($gggi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdi3=$resp_r['cantidad'];
}
if(isset($gdi3)){
$pdf->SetY(173);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($gdi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtri3=$resp_r['cantidad'];
}
if(isset($gtri3)){
$pdf->SetY(173);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($gtri3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcai3=$resp_r['cantidad'];
}
if(isset($gcai3)){
$pdf->SetY(173);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($gcai3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $qui3=$resp_r['cantidad'];
}
if(isset($qui3)){
$pdf->SetY(173);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($qui3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diessi3=$resp_r['cantidad'];
}
if(isset($diessi3)){
$pdf->SetY(173);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($diessi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsi3=$resp_r['cantidad'];
}
if(isset($dsi3)){
$pdf->SetY(173);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($dsi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dci3=$resp_r['cantidad'];
}
if(isset($dci3)){
$pdf->SetY(173);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($dci3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dni3=$resp_r['cantidad'];
}
if(isset($dni3)){
$pdf->SetY(173);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($dni3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vei3=$resp_r['cantidad'];
}
if(isset($vei3)){
$pdf->SetY(173);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vei3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vui3=$resp_r['cantidad'];
}
if(isset($vui3)){
$pdf->SetY(173);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vui3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdi3=$resp_r['cantidad'];
}
if(isset($vdi3)){
$pdf->SetY(173);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vdi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vti3=$resp_r['cantidad'];
}
if(isset($vti3)){
$pdf->SetY(173);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vti3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vci3=$resp_r['cantidad'];
}
if(isset($vci3)){
$pdf->SetY(173);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vci3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $uni3=$resp_r['cantidad'];
}
if(isset($uni3)){
$pdf->SetY(173);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($uni3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dosi3=$resp_r['cantidad'];
}
if(isset($dosi3)){
$pdf->SetY(173);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($dosi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tresi3=$resp_r['cantidad'];
}
if(isset($tresi3)){
$pdf->SetY(173);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($tresi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cuati3=$resp_r['cantidad'];
}
if(isset($cuati3)){
$pdf->SetY(173);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($cuati3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cini3=$resp_r['cantidad'];
}
if(isset($cini3)){
$pdf->SetY(173);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($cini3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $seisi3=$resp_r['cantidad'];
}
if(isset($seisi3)){
$pdf->SetY(173);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($seisi3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 3' and fecha='$fechar' and hora='7' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sieti3=$resp_r['cantidad'];
}
if(isset($sieti3)){
$pdf->SetY(173);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sieti3),1,0,'C');
}else{
  $pdf->SetY(173);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL VIA ENTERAL

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nmoct=$resp_r['cantidad'];
}
if(isset($nmoct)){
$pdf->SetY(176);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($nmoct),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nm=$resp_r['cantidad'];
}
if(isset($nm)){
$pdf->SetY(176);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($nm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='10' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dm=$resp_r9['cantidad'];
}
if(isset($dm)){
$pdf->SetY(176);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($dm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $om=$resp_r['cantidad'];
}
if(isset($om)){
$pdf->SetY(176);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($om),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dom=$resp_r['cantidad'];
}
if(isset($dom)){
$pdf->SetY(176);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($dom),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $trm=$resp_r['cantidad'];
}
if(isset($trm)){
$pdf->SetY(176);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($trm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $catm=$resp_r['cantidad'];
}
if(isset($catm)){
$pdf->SetY(176);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($catm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $quim=$resp_r['cantidad'];
}
if(isset($quim)){
$pdf->SetY(176);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($quim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='16' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsm=$resp_r['cantidad'];
}
if(isset($dsm)){
$pdf->SetY(176);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($dsm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsim=$resp_r['cantidad'];
}
if(isset($dsim)){
$pdf->SetY(176);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($dsim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsom=$resp_r['cantidad'];
}
if(isset($dsom)){
$pdf->SetY(176);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($dsom),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='19' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dnm=$resp_r['cantidad'];
}
if(isset($dnm)){
$pdf->SetY(176);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($dnm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $veim=$resp_r['cantidad'];
}
if(isset($veim)){
$pdf->SetY(176);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($veim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vum=$resp_r['cantidad'];
}
if(isset($vum)){
$pdf->SetY(176);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vum),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdm=$resp_r['cantidad'];
}
if(isset($vdm)){
$pdf->SetY(176);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vdm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vtm=$resp_r['cantidad'];
}
if(isset($vtm)){
$pdf->SetY(176);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vtm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vcum=$resp_r['cantidad'];
}
if(isset($vcum)){
$pdf->SetY(176);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vcum),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vcim=$resp_r['cantidad'];
}
if(isset($vcim)){
$pdf->SetY(176);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($vcim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vesm=$resp_r['cantidad'];
}
if(isset($vesm)){
$pdf->SetY(176);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($vesm),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vesim=$resp_r['cantidad'];
}
if(isset($vesim)){
$pdf->SetY(176);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($vesim),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cuatrom=$resp_r['cantidad'];
}
if(isset($cuatrom)){
$pdf->SetY(176);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($cuatrom),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cincom=$resp_r['cantidad'];
}
if(isset($cincom)){
$pdf->SetY(176);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($cincom),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sesism=$resp_r['cantidad'];
}
if(isset($sesism)){
$pdf->SetY(176);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sesism),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sietem=$resp_r['cantidad'];
}
if(isset($sietem)){
$pdf->SetY(176);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sietem),1,0,'C');
}else{
  $pdf->SetY(176);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//infusion 4 infusion 4 infusion 4 infusion 4 infusion 4 infusion 4 infusion 4 infusion 4 infusion 4 infusion 4 infusion 4 infusion 4

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gocti4=$resp_r['cantidad'];
}
if(isset($gocti4)){
$pdf->SetY(179);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($gocti4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gi4=$resp_r['cantidad'];
}
if(isset($gi4)){
$pdf->SetY(179);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($gi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ggi4=$resp_r9['cantidad'];
}
if(isset($ggi4)){
$pdf->SetY(179);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ggi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gggi4=$resp_r['cantidad'];
}
if(isset($gggi4)){
$pdf->SetY(179);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($gggi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdi4=$resp_r['cantidad'];
}
if(isset($gdi4)){
$pdf->SetY(179);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($gdi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtri4=$resp_r['cantidad'];
}
if(isset($gtri4)){
$pdf->SetY(179);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($gtri4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcai4=$resp_r['cantidad'];
}
if(isset($gcai4)){
$pdf->SetY(179);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($gcai4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $qui4=$resp_r['cantidad'];
}
if(isset($qui4)){
$pdf->SetY(179);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($qui4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='16' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diessi4=$resp_r['cantidad'];
}
if(isset($diessi4)){
$pdf->SetY(179);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($diessi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsi4=$resp_r['cantidad'];
}
if(isset($dsi4)){
$pdf->SetY(179);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($dsi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='18' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dci4=$resp_r['cantidad'];
}
if(isset($dci4)){
$pdf->SetY(179);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($dci4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dni4=$resp_r['cantidad'];
}
if(isset($dni4)){
$pdf->SetY(179);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($dni4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vei4=$resp_r['cantidad'];
}
if(isset($vei4)){
$pdf->SetY(179);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vei4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vui4=$resp_r['cantidad'];
}
if(isset($vui4)){
$pdf->SetY(179);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vui4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdi4=$resp_r['cantidad'];
}
if(isset($vdi4)){
$pdf->SetY(179);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vdi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vti4=$resp_r['cantidad'];
}
if(isset($vti4)){
$pdf->SetY(179);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vti4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vci4=$resp_r['cantidad'];
}
if(isset($vci4)){
$pdf->SetY(179);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vci4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $uni4=$resp_r['cantidad'];
}
if(isset($uni4)){
$pdf->SetY(179);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($uni4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dosi4=$resp_r['cantidad'];
}
if(isset($dosi4)){
$pdf->SetY(179);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($dosi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tresi4=$resp_r['cantidad'];
}
if(isset($tresi4)){
$pdf->SetY(179);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($tresi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cuati4=$resp_r['cantidad'];
}
if(isset($cuati4)){
$pdf->SetY(179);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($cuati4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cini4=$resp_r['cantidad'];
}
if(isset($cini4)){
$pdf->SetY(179);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($cini4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='6' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $seisi4=$resp_r['cantidad'];
}
if(isset($seisi4)){
$pdf->SetY(179);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($seisi4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 4' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sieti4=$resp_r['cantidad'];
}
if(isset($sieti4)){
$pdf->SetY(179);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sieti4),1,0,'C');
}else{
  $pdf->SetY(179);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS HEMODERIVADOS

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vnoc=$resp_r['cantidad'];
}
if(isset($vnoc)){
$pdf->SetY(182);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($vnoc),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vn=$resp_r['cantidad'];
}
if(isset($vn)){
$pdf->SetY(182);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($vn),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdod=$resp_r9['cantidad'];
}
if(isset($vdod)){
$pdf->SetY(182);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($vdod),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vo=$resp_r['cantidad'];
}
if(isset($vo)){
$pdf->SetY(182);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($vo),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdo=$resp_r['cantidad'];
}
if(isset($vdo)){
$pdf->SetY(182);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($vdo),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vtr=$resp_r['cantidad'];
}
if(isset($vtr)){
$pdf->SetY(182);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($vtr),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vca=$resp_r['cantidad'];
}
if(isset($vca)){
$pdf->SetY(182);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($vca),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vqu=$resp_r['cantidad'];
}
if(isset($vqu)){
$pdf->SetY(182);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($vqu),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vds=$resp_r['cantidad'];
}
if(isset($vds)){
$pdf->SetY(182);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($vds),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdss=$resp_r['cantidad'];
}
if(isset($vdss)){
$pdf->SetY(182);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($vdss),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdoc=$resp_r['cantidad'];
}
if(isset($vdoc)){
$pdf->SetY(182);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($vdoc),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdnn=$resp_r['cantidad'];
}
if(isset($vdnn)){
$pdf->SetY(182);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($vdnn),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvei=$resp_r['cantidad'];
}
if(isset($vvei)){
$pdf->SetY(182);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vvei),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvun=$resp_r['cantidad'];
}
if(isset($vvun)){
$pdf->SetY(182);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vvun),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvdos=$resp_r['cantidad'];
}
if(isset($vvdos)){
$pdf->SetY(182);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vvdos),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvtres=$resp_r['cantidad'];
}
if(isset($vvtres)){
$pdf->SetY(182);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vvtres),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vvcua=$resp_r['cantidad'];
}
if(isset($vvcua)){
$pdf->SetY(182);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vvcua),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vuno=$resp_r['cantidad'];
}
if(isset($vuno)){
$pdf->SetY(182);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($vuno),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vpdos=$resp_r['cantidad'];
}
if(isset($vpdos)){
$pdf->SetY(182);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($vpdos),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vptres=$resp_r['cantidad'];
}
if(isset($vptres)){
$pdf->SetY(182);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($vptres),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vcuatro=$resp_r['cantidad'];
}
if(isset($vcuatro)){
$pdf->SetY(182);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($vcuatro),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vcinco=$resp_r['cantidad'];
}
if(isset($vcinco)){
$pdf->SetY(182);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($vcinco),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vsesis=$resp_r['cantidad'];
}
if(isset($vsesis)){
$pdf->SetY(182);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($vsesis),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='VIA ORAL' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vsiete=$resp_r['cantidad'];
}
if(isset($vsiete)){
$pdf->SetY(182);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($vsiete),1,0,'C');
}else{
  $pdf->SetY(182);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//infusion 5 infusion 5 infusion 5 infusion 5i infusion 5 infusion 5iinfusion 5 infusion 5i infusion 5 infusion 5i infusion 5 infusion 5i

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gocti5=$resp_r['cantidad'];
}
if(isset($gocti5)){
$pdf->SetY(185);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($gocti5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gi5=$resp_r['cantidad'];
}
if(isset($gi5)){
$pdf->SetY(185);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($gi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ggi5=$resp_r9['cantidad'];
}
if(isset($ggi5)){
$pdf->SetY(185);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ggi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gggi5=$resp_r['cantidad'];
}
if(isset($gggi5)){
$pdf->SetY(185);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($gggi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gdi5=$resp_r['cantidad'];
}
if(isset($gdi5)){
$pdf->SetY(185);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($gdi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gtri5=$resp_r['cantidad'];
}
if(isset($gtri5)){
$pdf->SetY(185);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($gtri5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $gcai5=$resp_r['cantidad'];
}
if(isset($gcai5)){
$pdf->SetY(185);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($gcai5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $qui5=$resp_r['cantidad'];
}
if(isset($qui5)){
$pdf->SetY(185);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($qui5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $diessi5=$resp_r['cantidad'];
}
if(isset($diessi5)){
$pdf->SetY(185);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($diessi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsi5=$resp_r['cantidad'];
}
if(isset($dsi5)){
$pdf->SetY(185);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($dsi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dci5=$resp_r['cantidad'];
}
if(isset($dci5)){
$pdf->SetY(185);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($dci5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dni5=$resp_r['cantidad'];
}
if(isset($dni5)){
$pdf->SetY(185);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($dni5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vei5=$resp_r['cantidad'];
}
if(isset($vei5)){
$pdf->SetY(185);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vei5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vui5=$resp_r['cantidad'];
}
if(isset($vui5)){
$pdf->SetY(185);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vui5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vdi5=$resp_r['cantidad'];
}
if(isset($vdi5)){
$pdf->SetY(185);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vdi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='23' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vti5=$resp_r['cantidad'];
}
if(isset($vti5)){
$pdf->SetY(185);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vti5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vci5=$resp_r['cantidad'];
}
if(isset($vci5)){
$pdf->SetY(185);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vci5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $uni5=$resp_r['cantidad'];
}
if(isset($uni5)){
$pdf->SetY(185);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($uni5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dosi5=$resp_r['cantidad'];
}
if(isset($dosi5)){
$pdf->SetY(185);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($dosi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $tresi5=$resp_r['cantidad'];
}
if(isset($tresi5)){
$pdf->SetY(185);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($tresi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cuati5=$resp_r['cantidad'];
}
if(isset($cuati5)){
$pdf->SetY(185);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($cuati5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cini5=$resp_r['cantidad'];
}
if(isset($cini5)){
$pdf->SetY(185);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($cini5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $seisi5=$resp_r['cantidad'];
}
if(isset($seisi5)){
$pdf->SetY(185);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($seisi5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSION 5' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sieti5=$resp_r['cantidad'];
}
if(isset($sieti5)){
$pdf->SetY(185);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sieti5),1,0,'C');
}else{
  $pdf->SetY(185);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//AMINAS AMINAS AMINAS

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $canoctt=$resp_r['cantidad'];
}
if(isset($canoctt)){
$pdf->SetY(188);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($canoctt),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $can=$resp_r['cantidad'];
}
if(isset($can)){
$pdf->SetY(188);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($can),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardie=$resp_r9['cantidad'];
}
if(isset($cardie)){
$pdf->SetY(188);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($cardie),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASo=$resp_r['cantidad'];
}
if(isset($AMINASo)){
$pdf->SetY(188);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($AMINASo),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASdo=$resp_r['cantidad'];
}
if(isset($AMINASdo)){
$pdf->SetY(188);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($AMINASdo),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINAStr=$resp_r['cantidad'];
}
if(isset($AMINAStr)){
$pdf->SetY(188);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($AMINAStr),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINAScat=$resp_r['cantidad'];
}
if(isset($AMINAScat)){
$pdf->SetY(188);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($AMINAScat),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='15' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASquince=$resp_r['cantidad'];
}
if(isset($AMINASquince)){
$pdf->SetY(188);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($AMINASquince),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASds=$resp_r['cantidad'];
}
if(isset($AMINASds)){
$pdf->SetY(188);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($AMINASds),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASdss=$resp_r['cantidad'];
}
if(isset($AMINASdss)){
$pdf->SetY(188);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($AMINASdss),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASdoc=$resp_r['cantidad'];
}
if(isset($AMINASdoc)){
$pdf->SetY(188);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($AMINASdoc),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASdn=$resp_r['cantidad'];
}
if(isset($AMINASdn)){
$pdf->SetY(188);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($AMINASdn),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASvei=$resp_r['cantidad'];
}
if(isset($AMINASvei)){
$pdf->SetY(188);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($AMINASvei),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvuno=$resp_r['cantidad'];
}
if(isset($carvuno)){
$pdf->SetY(188);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($carvuno),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvdos=$resp_r['cantidad'];
}
if(isset($carvdos)){
$pdf->SetY(188);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($carvdos),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvtres=$resp_r['cantidad'];
}
if(isset($carvtres)){
$pdf->SetY(188);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($carvtres),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvcu=$resp_r['cantidad'];
}
if(isset($carvcu)){
$pdf->SetY(188);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($carvcu),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $caru=$resp_r['cantidad'];
}
if(isset($caru)){
$pdf->SetY(188);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($caru),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardos=$resp_r['cantidad'];
}
if(isset($cardos)){
$pdf->SetY(188);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($cardos),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carres=$resp_r['cantidad'];
}
if(isset($carres)){
$pdf->SetY(188);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($carres),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carc=$resp_r['cantidad'];
}
if(isset($carc)){
$pdf->SetY(188);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($carc),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carcin=$resp_r['cantidad'];
}
if(isset($carcin)){
$pdf->SetY(188);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($carcin),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carse=$resp_r['cantidad'];
}
if(isset($carse)){
$pdf->SetY(188);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($carse),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='AMINAS' and fecha='$fechar' and hora='7' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carsiete=$resp_r['cantidad'];
}
if(isset($carsiete)){
$pdf->SetY(188);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($carsiete),1,0,'C');
}else{
  $pdf->SetY(188);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//CARGAS // CARGAS // CARGAS // CARGAS // CARGAS // CARGAS // CARGAS // CARGAS // CARGAS // CARGAS // CARGAS

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='8' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $canocttca=$resp_r['cantidad'];
}
if(isset($canocttca)){
$pdf->SetY(191);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($canocttca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $canca=$resp_r['cantidad'];
}
if(isset($canca)){
$pdf->SetY(191);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($canca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardieca=$resp_r9['cantidad'];
}
if(isset($cardieca)){
$pdf->SetY(191);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($cardieca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASoca=$resp_r['cantidad'];
}
if(isset($AMINASoca)){
$pdf->SetY(191);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($AMINASoca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASdoca=$resp_r['cantidad'];
}
if(isset($AMINASdoca)){
$pdf->SetY(191);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($AMINASdoca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINAStrca=$resp_r['cantidad'];
}
if(isset($AMINAStrca)){
$pdf->SetY(191);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($AMINAStrca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINAScatca=$resp_r['cantidad'];
}
if(isset($AMINAScatca)){
$pdf->SetY(191);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($AMINAScatca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASquinceca=$resp_r['cantidad'];
}
if(isset($AMINASquinceca)){
$pdf->SetY(191);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($AMINASquinceca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASdsca=$resp_r['cantidad'];
}
if(isset($AMINASdsca)){
$pdf->SetY(191);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($AMINASdsca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASdssca=$resp_r['cantidad'];
}
if(isset($AMINASdssca)){
$pdf->SetY(191);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($AMINASdssca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASdocca=$resp_r['cantidad'];
}
if(isset($AMINASdocca)){
$pdf->SetY(191);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($AMINASdocca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASdnca=$resp_r['cantidad'];
}
if(isset($AMINASdnca)){
$pdf->SetY(191);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($AMINASdnca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $AMINASveica=$resp_r['cantidad'];
}
if(isset($AMINASveica)){
$pdf->SetY(191);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($AMINASveica),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvunoca=$resp_r['cantidad'];
}
if(isset($carvunoca)){
$pdf->SetY(191);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($carvunoca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvdosca=$resp_r['cantidad'];
}
if(isset($carvdosca)){
$pdf->SetY(191);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($carvdosca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvtresca=$resp_r['cantidad'];
}
if(isset($carvtresca)){
$pdf->SetY(191);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($carvtresca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carvcuca=$resp_r['cantidad'];
}
if(isset($carvcuca)){
$pdf->SetY(191);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($carvcuca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $caruca=$resp_r['cantidad'];
}
if(isset($caruca)){
$pdf->SetY(191);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($caruca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $cardosca=$resp_r['cantidad'];
}
if(isset($cardosca)){
$pdf->SetY(191);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($cardosca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carresca=$resp_r['cantidad'];
}
if(isset($carresca)){
$pdf->SetY(191);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($carresca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carcca=$resp_r['cantidad'];
}
if(isset($carcca)){
$pdf->SetY(191);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($carcca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carcinca=$resp_r['cantidad'];
}
if(isset($carcinca)){
$pdf->SetY(191);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($carcinca),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carsecar=$resp_r['cantidad'];
}
if(isset($carsecar)){
$pdf->SetY(191);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($carsecar),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='CARGAS' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $carsietecar=$resp_r['cantidad'];
}
if(isset($carsietecar)){
$pdf->SetY(191);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($carsietecar),1,0,'C');
}else{
  $pdf->SetY(191);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES INFUSIONES
$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $innoct=$resp_r['cantidad'];
}
if(isset($innoct)){
$pdf->SetY(194);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($innoct),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inn=$resp_r['cantidad'];
}
if(isset($inn)){
$pdf->SetY(194);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($inn),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ind=$resp_r9['cantidad'];
}
if(isset($ind)){
$pdf->SetY(194);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ind),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ino=$resp_r['cantidad'];
}
if(isset($ino)){
$pdf->SetY(194);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($ino),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='12' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indo=$resp_r['cantidad'];
}
if(isset($indo)){
$pdf->SetY(194);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($indo),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intrece=$resp_r['cantidad'];
}
if(isset($intrece)){
$pdf->SetY(194);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($intrece),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $incat=$resp_r['cantidad'];
}
if(isset($incat)){
$pdf->SetY(194);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($incat),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inquin=$resp_r['cantidad'];
}
if(isset($inquin)){
$pdf->SetY(194);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($inquin),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inds=$resp_r['cantidad'];
}
if(isset($inds)){
$pdf->SetY(194);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($inds),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indss=$resp_r['cantidad'];
}
if(isset($indss)){
$pdf->SetY(194);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($indss),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indoch=$resp_r['cantidad'];
}
if(isset($indoch)){
$pdf->SetY(194);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($indoch),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indn=$resp_r['cantidad'];
}
if(isset($indn)){
$pdf->SetY(194);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($indn),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inveinte=$resp_r['cantidad'];
}
if(isset($inveinte)){
$pdf->SetY(194);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($inveinte),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inveun=$resp_r['cantidad'];
}
if(isset($inveun)){
$pdf->SetY(194);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($inveun),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inved=$resp_r['cantidad'];
}
if(isset($inved)){
$pdf->SetY(194);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($inved),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inveitr=$resp_r['cantidad'];
}
if(isset($inveitr)){
$pdf->SetY(194);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($inveitr),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $invecuat=$resp_r['cantidad'];
}
if(isset($invecuat)){
$pdf->SetY(194);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($invecuat),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inuno=$resp_r['cantidad'];
}
if(isset($inuno)){
$pdf->SetY(194);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($inuno),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indos=$resp_r['cantidad'];
}
if(isset($indos)){
$pdf->SetY(194);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($indos),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intres=$resp_r['cantidad'];
}
if(isset($intres)){
$pdf->SetY(194);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($intres),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $incuatro=$resp_r['cantidad'];
}
if(isset($incuatro)){
$pdf->SetY(194);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($incuatro),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $incinco=$resp_r['cantidad'];
}
if(isset($incinco)){
$pdf->SetY(194);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($incinco),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inseis=$resp_r['cantidad'];
}
if(isset($inseis)){
$pdf->SetY(194);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($inseis),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='INFUSIONES' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insiete=$resp_r['cantidad'];
}
if(isset($insiete)){
$pdf->SetY(194);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($insiete),1,0,'C');
}else{
  $pdf->SetY(194);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//NUTRICION ENTERAL NUTRICION ENTERAL NUTRICION ENTERAL NUTRICION ENTERAL NUTRICION ENTERAL NUTRICION ENTERAL NUTRICION ENTERAL NUTRICION ENTERAL 

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $innoctne=$resp_r['cantidad'];
}
if(isset($innoctne)){
$pdf->SetY(197);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($innoctne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $innne=$resp_r['cantidad'];
}
if(isset($innne)){
$pdf->SetY(197);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($innne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indne=$resp_r9['cantidad'];
}
if(isset($indne)){
$pdf->SetY(197);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($indne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inone=$resp_r['cantidad'];
}
if(isset($inone)){
$pdf->SetY(197);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($inone),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indone=$resp_r['cantidad'];
}
if(isset($indone)){
$pdf->SetY(197);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($indone),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intrecene=$resp_r['cantidad'];
}
if(isset($intrecene)){
$pdf->SetY(197);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($intrecene),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $incatne=$resp_r['cantidad'];
}
if(isset($incatne)){
$pdf->SetY(197);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($incatne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inquinne=$resp_r['cantidad'];
}
if(isset($inquinne)){
$pdf->SetY(197);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($inquinne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indsne=$resp_r['cantidad'];
}
if(isset($indsne)){
$pdf->SetY(197);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($indsne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indssne=$resp_r['cantidad'];
}
if(isset($indssne)){
$pdf->SetY(197);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($indssne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indochne=$resp_r['cantidad'];
}
if(isset($indochne)){
$pdf->SetY(197);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($indochne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indnnne=$resp_r['cantidad'];
}
if(isset($indnnne)){
$pdf->SetY(197);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($indnnne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inveintene=$resp_r['cantidad'];
}
if(isset($inveintene)){
$pdf->SetY(197);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($inveintene),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inveunne=$resp_r['cantidad'];
}
if(isset($inveunne)){
$pdf->SetY(197);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($inveunne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $invedne=$resp_r['cantidad'];
}
if(isset($invedne)){
$pdf->SetY(197);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($invedne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inveitrne=$resp_r['cantidad'];
}
if(isset($inveitrne)){
$pdf->SetY(197);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($inveitrne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $invecuatne=$resp_r['cantidad'];
}
if(isset($invecuatne)){
$pdf->SetY(197);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($invecuatne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inunonee=$resp_r['cantidad'];
}
if(isset($inunonee)){
$pdf->SetY(197);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($inunonee),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $indosne=$resp_r['cantidad'];
}
if(isset($indosne)){
$pdf->SetY(197);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($indosne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $intresne=$resp_r['cantidad'];
}
if(isset($intresne)){
$pdf->SetY(197);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($intresne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $incuatrone=$resp_r['cantidad'];
}
if(isset($incuatrone)){
$pdf->SetY(197);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($incuatrone),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $incincone=$resp_r['cantidad'];
}
if(isset($incincone)){
$pdf->SetY(197);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($incincone),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $inseisne=$resp_r['cantidad'];
}
if(isset($inseisne)){
$pdf->SetY(197);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($inseisne),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $insietene=$resp_r['cantidad'];
}
if(isset($insietene)){
$pdf->SetY(197);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($insietene),1,0,'C');
}else{
  $pdf->SetY(197);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//OTROS INGRESO OTROS INGRESOS OTROS INGRESOS OTROS INGRESOS OTROS INGRESOS OTROS INGRESOS OTROS INGRESOS

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $onoc=$resp_r['cantidad'];
}
if(isset($onoc)){
$pdf->SetY(200);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($onoc),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $on=$resp_r['cantidad'];
}
if(isset($on)){
$pdf->SetY(200);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($on),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odie=$resp_r9['cantidad'];
}
if(isset($odie)){
$pdf->SetY(200);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($odie),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oonce=$resp_r['cantidad'];
}
if(isset($oonce)){
$pdf->SetY(200);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($oonce),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odoce=$resp_r['cantidad'];
}
if(isset($odoce)){
$pdf->SetY(200);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($odoce),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otrece=$resp_r['cantidad'];
}
if(isset($otrece)){
$pdf->SetY(200);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($otrece),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ocat=$resp_r['cantidad'];
}
if(isset($ocat)){
$pdf->SetY(200);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($ocat),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='15' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oquin=$resp_r['cantidad'];
}
if(isset($oquin)){
$pdf->SetY(200);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($oquin),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odseis=$resp_r['cantidad'];
}
if(isset($odseis)){
$pdf->SetY(200);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($odseis),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odsiete=$resp_r['cantidad'];
}
if(isset($odsiete)){
$pdf->SetY(200);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($odsiete),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odocho=$resp_r['cantidad'];
}
if(isset($odocho)){
$pdf->SetY(200);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($odocho),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $odn=$resp_r['cantidad'];
}
if(isset($odn)){
$pdf->SetY(200);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($odn),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oveinte=$resp_r['cantidad'];
}
if(isset($oveinte)){
$pdf->SetY(200);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($oveinte),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ovuno=$resp_r['cantidad'];
}
if(isset($ovuno)){
$pdf->SetY(200);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($ovuno),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ovdos=$resp_r['cantidad'];
}
if(isset($ovdos)){
$pdf->SetY(200);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($ovdos),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ovtres=$resp_r['cantidad'];
}
if(isset($ovtres)){
$pdf->SetY(200);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($ovtres),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ovcuat=$resp_r['cantidad'];
}
if(isset($ovcuat)){
$pdf->SetY(200);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($ovcuat),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otuno=$resp_r['cantidad'];
}
if(isset($otuno)){
$pdf->SetY(200);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($otuno),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otdos=$resp_r['cantidad'];
}
if(isset($otdos)){
$pdf->SetY(200);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($otdos),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ottres=$resp_r['cantidad'];
}
if(isset($ottres)){
$pdf->SetY(200);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ottres),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otcua=$resp_r['cantidad'];
}
if(isset($otcua)){
$pdf->SetY(200);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($otcua),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otcin=$resp_r['cantidad'];
}
if(isset($otcin)){
$pdf->SetY(200);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($otcin),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otseis=$resp_r['cantidad'];
}
if(isset($otseis)){
$pdf->SetY(200);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($otseis),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $otsiete=$resp_r['cantidad'];
}
if(isset($otsiete)){
$pdf->SetY(200);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($otsiete),1,0,'C');
}else{
  $pdf->SetY(200);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//NUTRICION PARENERA UTRICION PARENTERAL NUTRICION PARENTERAL NUTRICION PARENTERAL NUTRICON PARENTERAL

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrio=$resp_r['cantidad'];
}
if(isset($nutrio)){
$pdf->SetY(203);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($nutrio),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrinuev=$resp_r['cantidad'];
}
if(isset($nutrinuev)){
$pdf->SetY(203);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($nutrinuev),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridiez=$resp_r9['cantidad'];
}
if(isset($nutridiez)){
$pdf->SetY(203);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($nutridiez),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrionce=$resp_r['cantidad'];
}
if(isset($nutrionce)){
$pdf->SetY(203);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($nutrionce),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridoce=$resp_r['cantidad'];
}
if(isset($nutridoce)){
$pdf->SetY(203);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($nutridoce),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutritrece=$resp_r['cantidad'];
}
if(isset($nutritrece)){
$pdf->SetY(203);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($nutritrece),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutricat=$resp_r['cantidad'];
}
if(isset($nutricat)){
$pdf->SetY(203);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($nutricat),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutriquince=$resp_r['cantidad'];
}
if(isset($nutriquince)){
$pdf->SetY(203);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($nutriquince),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridesseis=$resp_r['cantidad'];
}
if(isset($nutridesseis)){
$pdf->SetY(203);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($nutridesseis),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridesie=$resp_r['cantidad'];
}
if(isset($nutridesie)){
$pdf->SetY(203);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($nutridesie),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridocho=$resp_r['cantidad'];
}
if(isset($nutridocho)){
$pdf->SetY(203);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($nutridocho),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridnueveee=$resp_r['cantidad'];
}
if(isset($nutridnueveee)){
$pdf->SetY(203);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($nutridnueveee),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutriveinte=$resp_r['cantidad'];
}
if(isset($nutriveinte)){
$pdf->SetY(203);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($nutriveinte),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrivuno=$resp_r['cantidad'];
}
if(isset($nutrivuno)){
$pdf->SetY(203);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($nutrivuno),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nurivdos=$resp_r['cantidad'];
}
if(isset($nurivdos)){
$pdf->SetY(203);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($nurivdos),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrivcuat=$resp_r['cantidad'];
}
if(isset($nutrivcuat)){
$pdf->SetY(203);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($nutrivcuat),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrivcdd=$resp_r['cantidad'];
}
if(isset($nutrivcdd)){
$pdf->SetY(203);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($nutrivcdd),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrino=$resp_r['cantidad'];
}
if(isset($nutrino)){
$pdf->SetY(203);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($nutrino),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutridosss=$resp_r['cantidad'];
}
if(isset($nutridosss)){
$pdf->SetY(203);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($nutridosss),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutritres=$resp_r['cantidad'];
}
if(isset($nutritres)){
$pdf->SetY(203);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($nutritres),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutricc=$resp_r['cantidad'];
}
if(isset($nutricc)){
$pdf->SetY(203);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($nutricc),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutricinc=$resp_r['cantidad'];
}
if(isset($nutricinc)){
$pdf->SetY(203);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($nutricinc),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutriseiss=$resp_r['cantidad'];
}
if(isset($nutriseiss)){
$pdf->SetY(203);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($nutriseiss),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from ing_enf_quir where des='NUTRICION PARENTERAL' and fecha='$fechar' and hora='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $nutrisiete=$resp_r['cantidad'];
}
if(isset($nutrisiete)){
$pdf->SetY(203);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($nutrisiete),1,0,'C');
}else{
  $pdf->SetY(203);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL MATUTINO

$resps = $conexion->query("select SUM(cantidad) as sumbase from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14' )") or die($conexion->error);
while ($resp_rso = $resps->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbases=$resp_rso['sumbase'];
}

$resp = $conexion->query("select SUM(cantidad) as summed from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rme = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summed=$resp_rme['summed'];
}

$respor = $conexion->query("select SUM(cantidad) as vias from ing_enf_quir where des='VIA ORAL' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rvvi = $respor->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vias=$resp_rvvi['vias'];
}

$resp = $conexion->query("select SUM(cantidad) as sumAMINAS from ing_enf_quir where des='AMINAS' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rcc = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumAMINAS=$resp_rcc['sumAMINAS'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcargas from ing_enf_quir where des='CARGAS' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rcc = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcargas=$resp_rcc['sumcargas'];
}

$resp = $conexion->query("select SUM(cantidad) as sumcantidad from ing_enf_quir where des='INFUSIONES' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rin = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidad=$resp_rin['sumcantidad'];
}


$resp = $conexion->query("select SUM(cantidad) as sumne from ing_enf_quir where des='NUTRICION ENTERAL' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rin = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumne=$resp_rin['sumne'];
}

$resp = $conexion->query("select SUM(cantidad) as sum from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_rtr = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sum=$resp_rtr['sum'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnut from ing_enf_quir where (des='NUTRICION PARENTERAL' || des='INFUSION 2' || des='INFUSION 3' || des='INFUSION 4' || des='INFUSION 5') and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='8' or hora='9' or hora='10' or hora='11' or hora='12' or hora='13' or hora='14')") or die($conexion->error);
while ($resp_run = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnut=$resp_run['sumnut'];
}


$sumatotal=$sumbases+$summed+$vias+$sumAMINAS+$sumcantidad+$sum+$sumnut+$sumne+$sumcargas;

/*if(isset($sum)){
*/
$pdf->SetY(206);
$pdf->SetX(40);
$pdf->Cell(49,6, utf8_decode($sumatotal . ' ML'),1,0,'C');
/*
}else{
  $pdf->SetY(206);
$pdf->SetX(40);
  $pdf->Cell(42,6, utf8_decode(''),1,0,'C');
}*/

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL VESPERTINO

$resp = $conexion->query("select SUM(cantidad) as sumbasev from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbasev=$resp_r['sumbasev'];
}

$resp = $conexion->query("select SUM(cantidad) as summedv from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedv=$resp_r['summedv'];
}

$resp = $conexion->query("select SUM(cantidad) as viav from ing_enf_quir where des='VIA ORAL' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $viav=$resp_r['viav'];
}

$resp = $conexion->query("select SUM(cantidad) as sumAMINASv from ing_enf_quir where (des='AMINAS'||des='CARGAS' ||des='NUTRICION ENTERAL') and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumAMINASv=$resp_r['sumAMINASv'];
}


$resp = $conexion->query("select SUM(cantidad) as sumcantidadv from ing_enf_quir where des='INFUSIONES' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidadv=$resp_r['sumcantidadv'];
}

$resp = $conexion->query("select SUM(cantidad) as sumv from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumv=$resp_r['sumv'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnutrv from ing_enf_quir where (des='NUTRICION PARENTERAL' || des='INFUSION 2' || des='INFUSION 3' || des='INFUSION 4' || des='INFUSION 5') and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='15' or hora='16' or hora='17' or hora='18' or hora='19' or hora='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnutrv=$resp_r['sumnutrv'];
}


$sumatotalv=$sumbasev+$summedv+$viav+$sumAMINASv+$sumcantidadv+$sumv+$sumnutrv;


/*if(isset($g9)){
*/
$pdf->SetY(206);
$pdf->SetX(89);
$pdf->Cell(42,6, utf8_decode($sumatotalv . ' ML'),1,0,'C');
/*
}else{
  $pdf->SetY(206);
$pdf->SetX(82);
 $pdf->Cell(49,6, utf8_decode(''),1,0,'C');
}*/

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL NOCTURNO NOCTURNO

$resp = $conexion->query("select SUM(cantidad) as sumbasen from ing_enf_quir where des='SOLUCION BASE' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbasen=$resp_r['sumbasen'];
}

$resp = $conexion->query("select SUM(cantidad) as summedn from ing_enf_quir where des='MEDICAMENTOS' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedn=$resp_r['summedn'];
}

$resp = $conexion->query("select SUM(cantidad) as vian from ing_enf_quir where des='VIA ORAL' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vian=$resp_r['vian'];
}

$resp = $conexion->query("select SUM(cantidad) as sumAMINASn from ing_enf_quir where (des='AMINAS'||des='CARGAS'||des='NUTRICION ENTERAL') and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumAMINASn=$resp_r['sumAMINASn'];
}


$resp = $conexion->query("select SUM(cantidad) as sumcantidadn from ing_enf_quir where des='INFUSIONES' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidadn=$resp_r['sumcantidadn'];
}

$resp = $conexion->query("select SUM(cantidad) as sumn from ing_enf_quir where des='HEMODERIVADOS' and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumn=$resp_r['sumn'];
}

$resp = $conexion->query("select SUM(cantidad) as sumnutno from ing_enf_quir where (des='NUTRICION PARENTERAL' || des='INFUSION 2' || des='INFUSION 3' || des='INFUSION 4' || des='INFUSION 5') and fecha='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora='21' or hora='22' or hora='23' or hora='24' or hora='1' or hora='2' or hora='3' or hora='4' or hora='5' or hora='6' or hora='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnutno=$resp_r['sumnutno'];
}


$sumatotaln=$sumbasen+$summedn+$vian+$sumAMINASn+$sumcantidadn+$sumn+$sumnutno;

//total global
$totalglob=$sumatotal+$sumatotalv+$sumatotaln;


/*if(isset($g10)){
  */
$pdf->SetY(206);
$pdf->SetX(131);
$pdf->Cell(77,6, utf8_decode($sumatotaln . ' ML ' . ' Total global: ' . $totalglob . 'ML'),1,0,'C');
/*
}else{

  $pdf->SetY(206);
$pdf->SetX(131);
  $pdf->Cell(77,6, utf8_decode(''),1,0,'C');
}
*/


//DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS DIURESUS DIURESIS DIURESIS DIURESIS DIURESIS DIURESIS

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='8' and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $onoctt=$resp_r['cant_eg'];
}
if(isset($onoctt)){
$pdf->SetY(213);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($onoctt),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='9' and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $on=$resp_r['cant_eg'];
}
if(isset($on)){
$pdf->SetY(213);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($on),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordies=$resp_r9['cant_eg'];
}
if(isset($ordies)){
$pdf->SetY(213);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ordies),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oro=$resp_r['cant_eg'];
}
if(isset($oro)){
$pdf->SetY(213);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($oro),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordoce=$resp_r['cant_eg'];
}
if(isset($ordoce)){
$pdf->SetY(213);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ordoce),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ortrece=$resp_r['cant_eg'];
}
if(isset($ortrece)){
$pdf->SetY(213);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ortrece),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcat=$resp_r['cant_eg'];
}
if(isset($orcat)){
$pdf->SetY(213);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($orcat),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orquin=$resp_r['cant_eg'];
}
if(isset($orquin)){
$pdf->SetY(213);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($orquin),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ords=$resp_r['cant_eg'];
}
if(isset($ords)){
$pdf->SetY(213);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ords),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordss=$resp_r['cant_eg'];
}
if(isset($ordss)){
$pdf->SetY(213);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ordss),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordsocho=$resp_r['cant_eg'];
}
if(isset($ordsocho)){
$pdf->SetY(213);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ordsocho),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordn=$resp_r['cant_eg'];
}
if(isset($ordn)){
$pdf->SetY(213);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ordn),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvei=$resp_r['cant_eg'];
}
if(isset($orvei)){
$pdf->SetY(213);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($orvei),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvuno=$resp_r['cant_eg'];
}
if(isset($orvuno)){
$pdf->SetY(213);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($orvuno),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='22' and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvdos=$resp_r['cant_eg'];
}
if(isset($orvdos)){
$pdf->SetY(213);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($orvdos),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orveintres=$resp_r['cant_eg'];
}
if(isset($orveintres)){
$pdf->SetY(213);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($orveintres),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvecua=$resp_r['cant_eg'];
}
if(isset($orvecua)){
$pdf->SetY(213);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($orvecua),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oruno=$resp_r['cant_eg'];
}
if(isset($oruno)){
$pdf->SetY(213);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($oruno),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordos=$resp_r['cant_eg'];
}
if(isset($ordos)){
$pdf->SetY(213);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ordos),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ortres=$resp_r['cant_eg'];
}
if(isset($ortres)){
$pdf->SetY(213);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ortres),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcuatro=$resp_r['cant_eg'];
}
if(isset($orcuatro)){
$pdf->SetY(213);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($orcuatro),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcinco=$resp_r['cant_eg'];
}
if(isset($orcinco)){
$pdf->SetY(213);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($orcinco),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orsesis=$resp_r['cant_eg'];
}
if(isset($orsesis)){
$pdf->SetY(213);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($orsesis),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orsiete=$resp_r['cant_eg'];
}
if(isset($orsiete)){
$pdf->SetY(213);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($orsiete),1,0,'C');
}else{
  $pdf->SetY(213);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA SARATOGA

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $onoctts=$resp_r['cant_eg'];
}
if(isset($onoctts)){
$pdf->SetY(216);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($onoctts),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ons=$resp_r['cant_eg'];
}
if(isset($ons)){
$pdf->SetY(216);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($ons),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordiess=$resp_r9['cant_eg'];
}
if(isset($ordiess)){
$pdf->SetY(216);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ordiess),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $oros=$resp_r['cant_eg'];
}
if(isset($oros)){
$pdf->SetY(216);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($oros),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordoces=$resp_r['cant_eg'];
}
if(isset($ordoces)){
$pdf->SetY(216);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ordoces),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ortreces=$resp_r['cant_eg'];
}
if(isset($ortreces)){
$pdf->SetY(216);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($ortreces),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcats=$resp_r['cant_eg'];
}
if(isset($orcats)){
$pdf->SetY(216);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($orcats),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orquins=$resp_r['cant_eg'];
}
if(isset($orquins)){
$pdf->SetY(216);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($orquins),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordssa=$resp_r['cant_eg'];
}
if(isset($ordssa)){
$pdf->SetY(216);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ordssa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordsssa=$resp_r['cant_eg'];
}
if(isset($ordsssa)){
$pdf->SetY(216);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ordsssa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordsochosa=$resp_r['cant_eg'];
}
if(isset($ordsochosa)){
$pdf->SetY(216);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ordsochosa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='19' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordnsa=$resp_r['cant_eg'];
}
if(isset($ordnsa)){
$pdf->SetY(216);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ordnsa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orveisa=$resp_r['cant_eg'];
}
if(isset($orveisa)){
$pdf->SetY(216);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($orveisa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvunosa=$resp_r['cant_eg'];
}
if(isset($orvunosa)){
$pdf->SetY(216);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($orvunosa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvdossa=$resp_r['cant_eg'];
}
if(isset($orvdossa)){
$pdf->SetY(216);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($orvdossa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='23' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orveintressa=$resp_r['cant_eg'];
}
if(isset($orveintressa)){
$pdf->SetY(216);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($orveintressa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orvecuasa=$resp_r['cant_eg'];
}
if(isset($orvecuasa)){
$pdf->SetY(216);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($orvecuasa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orunosa=$resp_r['cant_eg'];
}
if(isset($orunosa)){
$pdf->SetY(216);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($orunosa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ordosssa=$resp_r['cant_eg'];
}
if(isset($ordosssa)){
$pdf->SetY(216);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ordosssa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ortressa=$resp_r['cant_eg'];
}
if(isset($ortressa)){
$pdf->SetY(216);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($ortressa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcuatrosa=$resp_r['cant_eg'];
}
if(isset($orcuatrosa)){
$pdf->SetY(216);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($orcuatrosa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orcincosa=$resp_r['cant_eg'];
}
if(isset($orcincosa)){
$pdf->SetY(216);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($orcincosa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orsesissa=$resp_r['cant_eg'];
}
if(isset($orsesissa)){
$pdf->SetY(216);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($orsesissa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $orsietesa=$resp_r['cant_eg'];
}
if(isset($orsietesa)){
$pdf->SetY(216);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($orsietesa),1,0,'C');
}else{
  $pdf->SetY(216);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}



//ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS ESTOMAS

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomnocs=$resp_r['cant_eg'];
}
if(isset($vomnocs)){
$pdf->SetY(219);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($vomnocs),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomns=$resp_r['cant_eg'];
}
if(isset($vomns)){
$pdf->SetY(219);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($vomns),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomds=$resp_r9['cant_eg'];
}
if(isset($vomds)){
$pdf->SetY(219);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($vomds),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomonces=$resp_r['cant_eg'];
}
if(isset($vomonces)){
$pdf->SetY(219);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($vomonces),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdoces=$resp_r['cant_eg'];
}
if(isset($vomdoces)){
$pdf->SetY(219);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($vomdoces),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomtreces=$resp_r['cant_eg'];
}
if(isset($vomtreces)){
$pdf->SetY(219);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($vomtreces),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcatos=$resp_r['cant_eg'];
}
if(isset($vomcatos)){
$pdf->SetY(219);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($vomcatos),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomquins=$resp_r['cant_eg'];
}
if(isset($vomquins)){
$pdf->SetY(219);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($vomquins),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdiseiss=$resp_r['cant_eg'];
}
if(isset($vomdiseiss)){
$pdf->SetY(219);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($vomdiseiss),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdisietes=$resp_r['cant_eg'];
}
if(isset($vomdisietes)){
$pdf->SetY(219);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($vomdisietes),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdiochs=$resp_r['cant_eg'];
}
if(isset($vomdiochs)){
$pdf->SetY(219);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($vomdiochs),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdins=$resp_r['cant_eg'];
}
if(isset($vomdins)){
$pdf->SetY(219);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($vomdins),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomveintes=$resp_r['cant_eg'];
}
if(isset($vomveintes)){
$pdf->SetY(219);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vomveintes),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomveunos=$resp_r['cant_eg'];
}
if(isset($vomveunos)){
$pdf->SetY(219);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vomveunos),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvdosss=$resp_r['cant_eg'];
}
if(isset($vomvdosss)){
$pdf->SetY(219);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vomvdosss),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvtressa=$resp_r['cant_eg'];
}
if(isset($vomvtressa)){
$pdf->SetY(219);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vomvtressa),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvcuatrosa=$resp_r['cant_eg'];
}
if(isset($vomvcuatrosa)){
$pdf->SetY(219);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vomvcuatrosa),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomunosa=$resp_r['cant_eg'];
}
if(isset($vomunosa)){
$pdf->SetY(219);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($vomunosa),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdossa=$resp_r['cant_eg'];
}
if(isset($vomdossa)){
$pdf->SetY(219);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($vomdossa),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomtressd=$resp_r['cant_eg'];
}
if(isset($vomtressd)){
$pdf->SetY(219);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($vomtressd),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcuasd=$resp_r['cant_eg'];
}
if(isset($vomcuasd)){
$pdf->SetY(219);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($vomcuasd),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcinsd=$resp_r['cant_eg'];
}
if(isset($vomcinsd)){
$pdf->SetY(219);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($vomcinsd),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomsesd=$resp_r['cant_eg'];
}
if(isset($vomsesd)){
$pdf->SetY(219);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($vomsesd),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomsietesd=$resp_r['cant_eg'];
}
if(isset($vomsietesd)){
$pdf->SetY(219);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($vomsietesd),1,0,'C');
}else{
  $pdf->SetY(219);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//VOMITO VOMITO VOMITO VOMITO EG

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomnoc=$resp_r['cant_eg'];
}
if(isset($vomnoc)){
$pdf->SetY(222);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($vomnoc),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomn=$resp_r['cant_eg'];
}
if(isset($vomn)){
$pdf->SetY(222);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($vomn),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomd=$resp_r9['cant_eg'];
}
if(isset($vomd)){
$pdf->SetY(222);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($vomd),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomonce=$resp_r['cant_eg'];
}
if(isset($vomonce)){
$pdf->SetY(222);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($vomonce),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdoce=$resp_r['cant_eg'];
}
if(isset($vomdoce)){
$pdf->SetY(222);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($vomdoce),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomtrece=$resp_r['cant_eg'];
}
if(isset($vomtrece)){
$pdf->SetY(222);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($vomtrece),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcato=$resp_r['cant_eg'];
}
if(isset($vomcato)){
$pdf->SetY(222);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($vomcato),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomquin=$resp_r['cant_eg'];
}
if(isset($vomquin)){
$pdf->SetY(222);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($vomquin),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdiseis=$resp_r['cant_eg'];
}
if(isset($vomdiseis)){
$pdf->SetY(222);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($vomdiseis),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdisiete=$resp_r['cant_eg'];
}
if(isset($vomdisiete)){
$pdf->SetY(222);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($vomdisiete),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdioch=$resp_r['cant_eg'];
}
if(isset($vomdioch)){
$pdf->SetY(222);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($vomdioch),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdin=$resp_r['cant_eg'];
}
if(isset($vomdin)){
$pdf->SetY(222);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($vomdin),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomveinte=$resp_r['cant_eg'];
}
if(isset($vomveinte)){
$pdf->SetY(222);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($vomveinte),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomveuno=$resp_r['cant_eg'];
}
if(isset($vomveuno)){
$pdf->SetY(222);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($vomveuno),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvdos=$resp_r['cant_eg'];
}
if(isset($vomvdos)){
$pdf->SetY(222);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($vomvdos),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvtres=$resp_r['cant_eg'];
}
if(isset($vomvtres)){
$pdf->SetY(222);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($vomvtres),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomvcuatro=$resp_r['cant_eg'];
}
if(isset($vomvcuatro)){
$pdf->SetY(222);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($vomvcuatro),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomuno=$resp_r['cant_eg'];
}
if(isset($vomuno)){
$pdf->SetY(222);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($vomuno),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomdos=$resp_r['cant_eg'];
}
if(isset($vomdos)){
$pdf->SetY(222);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($vomdos),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomtres=$resp_r['cant_eg'];
}
if(isset($vomtres)){
$pdf->SetY(222);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($vomtres),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcua=$resp_r['cant_eg'];
}
if(isset($vomcua)){
$pdf->SetY(222);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($vomcua),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomcin=$resp_r['cant_eg'];
}
if(isset($vomcin)){
$pdf->SetY(222);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($vomcin),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomse=$resp_r['cant_eg'];
}
if(isset($vomse)){
$pdf->SetY(222);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($vomse),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vomsiete=$resp_r['cant_eg'];
}
if(isset($vomsiete)){
$pdf->SetY(222);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($vomsiete),1,0,'C');
}else{
  $pdf->SetY(222);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//SANGRADO SANGRADO SANGRADO SAGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO SANGRADO

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sannoct=$resp_r['cant_eg'];
}
if(isset($sannoct)){
$pdf->SetY(225);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($sannoct),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='9' and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sann=$resp_r['cant_eg'];
}
if(isset($sann)){
$pdf->SetY(225);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($sann),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sand=$resp_r9['cant_eg'];
}
if(isset($sand)){
$pdf->SetY(225);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($sand),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sano=$resp_r['cant_eg'];
}
if(isset($sano)){
$pdf->SetY(225);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($sano),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sando=$resp_r['cant_eg'];
}
if(isset($sando)){
$pdf->SetY(225);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($sando),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $santre=$resp_r['cant_eg'];
}
if(isset($santre)){
$pdf->SetY(225);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($santre),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='14' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanc=$resp_r['cant_eg'];
}
if(isset($sanc)){
$pdf->SetY(225);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($sanc),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanq=$resp_r['cant_eg'];
}
if(isset($sanq)){
$pdf->SetY(225);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($sanq),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sands=$resp_r['cant_eg'];
}
if(isset($sands)){
$pdf->SetY(225);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($sands),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sandss=$resp_r['cant_eg'];
}
if(isset($sandss)){
$pdf->SetY(225);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($sandss),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sandocho=$resp_r['cant_eg'];
}
if(isset($sandocho)){
$pdf->SetY(225);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($sandocho),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sandn=$resp_r['cant_eg'];
}
if(isset($sandn)){
$pdf->SetY(225);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($sandn),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanv=$resp_r['cant_eg'];
}
if(isset($sanv)){
$pdf->SetY(225);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($sanv),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanvun=$resp_r['cant_eg'];
}
if(isset($sanvun)){
$pdf->SetY(225);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($sanvun),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanvdos=$resp_r['cant_eg'];
}
if(isset($sanvdos)){
$pdf->SetY(225);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($sanvdos),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanvtr=$resp_r['cant_eg'];
}
if(isset($sanvtr)){
$pdf->SetY(225);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($sanvtr),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanvcu=$resp_r['cant_eg'];
}
if(isset($sanvcu)){
$pdf->SetY(225);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($sanvcu),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanun=$resp_r['cant_eg'];
}
if(isset($sanun)){
$pdf->SetY(225);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($sanun),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sandos=$resp_r['cant_eg'];
}
if(isset($sandos)){
$pdf->SetY(225);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($sandos),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $santres=$resp_r['cant_eg'];
}
if(isset($santres)){
$pdf->SetY(225);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($santres),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sancuatro=$resp_r['cant_eg'];
}
if(isset($sancuatro)){
$pdf->SetY(225);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($sancuatro),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sancin=$resp_r['cant_eg'];
}
if(isset($sancin)){
$pdf->SetY(225);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($sancin),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sanseis=$resp_r['cant_eg'];
}
if(isset($sanseis)){
$pdf->SetY(225);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sanseis),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sansiete=$resp_r['cant_eg'];
}
if(isset($sansiete)){
$pdf->SetY(225);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sansiete),1,0,'C');
}else{
  $pdf->SetY(225);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


//SEP SEP SEP SEP SEP SEP SEP SEP SEP SEP SEP SEP SSEP SEP SEP SEP S

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepnocts=$resp_r['cant_eg'];
}
if(isset($sepnocts)){
$pdf->SetY(228);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($sepnocts),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepns=$resp_r['cant_eg'];
}
if(isset($sepns)){
$pdf->SetY(228);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($sepns),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepds=$resp_r9['cant_eg'];
}
if(isset($sepds)){
$pdf->SetY(228);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($sepds),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepos=$resp_r['cant_eg'];
}
if(isset($sepos)){
$pdf->SetY(228);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($sepos),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepdos=$resp_r['cant_eg'];
}
if(isset($sepdos)){
$pdf->SetY(228);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($sepdos),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $septres=$resp_r['cant_eg'];
}
if(isset($septres)){
$pdf->SetY(228);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($septres),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepcs=$resp_r['cant_eg'];
}
if(isset($sepcs)){
$pdf->SetY(228);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($sepcs),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepqs=$resp_r['cant_eg'];
}
if(isset($sepqs)){
$pdf->SetY(228);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($sepqs),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepdss=$resp_r['cant_eg'];
}
if(isset($sepdss)){
$pdf->SetY(228);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($sepdss),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepdss=$resp_r['cant_eg'];
}
if(isset($sepdss)){
$pdf->SetY(228);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($sepdss),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepdochos=$resp_r['cant_eg'];
}
if(isset($sepdochos)){
$pdf->SetY(228);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($sepdochos),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepdns=$resp_r['cant_eg'];
}
if(isset($sepdns)){
$pdf->SetY(228);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($sepdns),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepvs=$resp_r['cant_eg'];
}
if(isset($sepvs)){
$pdf->SetY(228);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($sepvs),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepvuns=$resp_r['cant_eg'];
}
if(isset($sepvuns)){
$pdf->SetY(228);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($sepvuns),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepvdoss=$resp_r['cant_eg'];
}
if(isset($sepvdoss)){
$pdf->SetY(228);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($sepvdoss),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepvtrs=$resp_r['cant_eg'];
}
if(isset($sepvtrs)){
$pdf->SetY(228);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($sepvtrs),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='24' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepvcus=$resp_r['cant_eg'];
}
if(isset($sepvcus)){
$pdf->SetY(228);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($sepvcus),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepuns=$resp_r['cant_eg'];
}
if(isset($sepuns)){
$pdf->SetY(228);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($sepuns),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepdosss=$resp_r['cant_eg'];
}
if(isset($sepdosss)){
$pdf->SetY(228);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($sepdosss),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $septresse=$resp_r['cant_eg'];
}
if(isset($septresse)){
$pdf->SetY(228);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($septresse),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepcuatros=$resp_r['cant_eg'];
}
if(isset($sepcuatros)){
$pdf->SetY(228);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($sepcuatros),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepcins=$resp_r['cant_eg'];
}
if(isset($sepcins)){
$pdf->SetY(228);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($sepcins),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepseiss=$resp_r['cant_eg'];
}
if(isset($sepseiss)){
$pdf->SetY(228);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sepseiss),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SEP' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sepsietea=$resp_r['cant_eg'];
}
if(isset($sepsietea)){
$pdf->SetY(228);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($sepsietea),1,0,'C');
}else{
  $pdf->SetY(228);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//SONDA SONDA SONDA SONDA SONDA SONDA SONDA SONDA NASOGASTRICA SONDA NASO EG

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sondanoct=$resp_r['cant_eg'];
}
if(isset($sondanoct)){
$pdf->SetY(231);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($sondanoct),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sondan=$resp_r['cant_eg'];
}
if(isset($sondan)){
$pdf->SetY(231);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($sondan),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sd=$resp_r9['cant_eg'];
}
if(isset($sd)){
$pdf->SetY(231);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($sd),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $so=$resp_r['cant_eg'];
}
if(isset($so)){
$pdf->SetY(231);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($so),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdo=$resp_r['cant_eg'];
}
if(isset($sdo)){
$pdf->SetY(231);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($sdo),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $strece=$resp_r['cant_eg'];
}
if(isset($strece)){
$pdf->SetY(231);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($strece),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $scat=$resp_r['cant_eg'];
}
if(isset($scat)){
$pdf->SetY(231);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($scat),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $squin=$resp_r['cant_eg'];
}
if(isset($squin)){
$pdf->SetY(231);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($squin),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdseis=$resp_r['cant_eg'];
}
if(isset($sdseis)){
$pdf->SetY(231);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($sdseis),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdsiete=$resp_r['cant_eg'];
}
if(isset($sdsiete)){
$pdf->SetY(231);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($sdsiete),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdocho=$resp_r['cant_eg'];
}
if(isset($sdocho)){
$pdf->SetY(231);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($sdocho),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdn=$resp_r['cant_eg'];
}
if(isset($sdn)){
$pdf->SetY(231);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($sdn),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sv=$resp_r['cant_eg'];
}
if(isset($sv)){
$pdf->SetY(231);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($sv),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svu=$resp_r['cant_eg'];
}
if(isset($svu)){
$pdf->SetY(231);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($svu),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svdos=$resp_r['cant_eg'];
}
if(isset($svdos)){
$pdf->SetY(231);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($svdos),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svtres=$resp_r['cant_eg'];
}
if(isset($svtres)){
$pdf->SetY(231);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($svtres),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svcuatro=$resp_r['cant_eg'];
}
if(isset($svcuatro)){
$pdf->SetY(231);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($svcuatro),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $suno=$resp_r['cant_eg'];
}
if(isset($suno)){
$pdf->SetY(231);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($suno),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdos=$resp_r['cant_eg'];
}
if(isset($sdos)){
$pdf->SetY(231);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($sdos),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $stres=$resp_r['cant_eg'];
}
if(isset($stres)){
$pdf->SetY(231);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($stres),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $scuatro=$resp_r['cant_eg'];
}
if(isset($scuatro)){
$pdf->SetY(231);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($scuatro),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $scin=$resp_r['cant_eg'];
}
if(isset($scin)){
$pdf->SetY(231);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($scin),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sseis=$resp_r['cant_eg'];
}
if(isset($sseis)){
$pdf->SetY(231);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sseis),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ssiete=$resp_r['cant_eg'];
}
if(isset($ssiete)){
$pdf->SetY(231);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($ssiete),1,0,'C');
}else{
  $pdf->SetY(231);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC VAC

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sondanoctV=$resp_r['cant_eg'];
}
if(isset($sondanoctV)){
$pdf->SetY(234);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($sondanoctV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sondanV=$resp_r['cant_eg'];
}
if(isset($sondanV)){
$pdf->SetY(234);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($sondanV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdV=$resp_r9['cant_eg'];
}
if(isset($sdV)){
$pdf->SetY(234);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($sdV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $soV=$resp_r['cant_eg'];
}
if(isset($soV)){
$pdf->SetY(234);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($soV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdoV=$resp_r['cant_eg'];
}
if(isset($sdoV)){
$pdf->SetY(234);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($sdoV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $streceV=$resp_r['cant_eg'];
}
if(isset($streceV)){
$pdf->SetY(234);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($streceV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $scatV=$resp_r['cant_eg'];
}
if(isset($scatV)){
$pdf->SetY(234);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($scatV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $squinV=$resp_r['cant_eg'];
}
if(isset($squinV)){
$pdf->SetY(234);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($squinV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='16' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdseisV=$resp_r['cant_eg'];
}
if(isset($sdseisV)){
$pdf->SetY(234);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($sdseisV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdsieteV=$resp_r['cant_eg'];
}
if(isset($sdsieteV)){
$pdf->SetY(234);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($sdsieteV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdochoV=$resp_r['cant_eg'];
}
if(isset($sdochoV)){
$pdf->SetY(234);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($sdochoV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdnV=$resp_r['cant_eg'];
}
if(isset($sdnV)){
$pdf->SetY(234);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($sdnV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svV=$resp_r['cant_eg'];
}
if(isset($svV)){
$pdf->SetY(234);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($svV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svuV=$resp_r['cant_eg'];
}
if(isset($svuV)){
$pdf->SetY(234);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($svuV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svdosV=$resp_r['cant_eg'];
}
if(isset($svdosV)){
$pdf->SetY(234);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($svdosV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svtresV=$resp_r['cant_eg'];
}
if(isset($svtresV)){
$pdf->SetY(234);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($svtresV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $svcuatroV=$resp_r['cant_eg'];
}
if(isset($svcuatroV)){
$pdf->SetY(234);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($svcuatroV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sunoV=$resp_r['cant_eg'];
}
if(isset($sunoV)){
$pdf->SetY(234);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($sunoV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sdosV=$resp_r['cant_eg'];
}
if(isset($sdosV)){
$pdf->SetY(234);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($sdosV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $stresV=$resp_r['cant_eg'];
}
if(isset($stresV)){
$pdf->SetY(234);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($stresV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $scuatroV=$resp_r['cant_eg'];
}
if(isset($scuatroV)){
$pdf->SetY(234);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($scuatroV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $scinV=$resp_r['cant_eg'];
}
if(isset($scinV)){
$pdf->SetY(234);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($scinV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sseisV=$resp_r['cant_eg'];
}
if(isset($sseisV)){
$pdf->SetY(234);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($sseisV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='VAC' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ssieteV=$resp_r['cant_eg'];
}
if(isset($ssieteV)){
$pdf->SetY(234);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($ssieteV),1,0,'C');
}else{
  $pdf->SetY(234);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//HERIDA QUIR HERIDA QUIR HERIDA QUIR HERIDA QUIR HERIDA QUIR HERIDA QUIR HERIDA QUIR

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hnoct=$resp_r['cant_eg'];
}
if(isset($hnoct)){
$pdf->SetY(237);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($hnoct),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hn=$resp_r['cant_eg'];
}
if(isset($hn)){
$pdf->SetY(237);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($hn),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdies=$resp_r9['cant_eg'];
}
if(isset($hdies)){
$pdf->SetY(237);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($hdies),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $honce=$resp_r['cant_eg'];
}
if(isset($honce)){
$pdf->SetY(237);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($honce),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdoce=$resp_r['cant_eg'];
}
if(isset($hdoce)){
$pdf->SetY(237);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($hdoce),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $htrece=$resp_r['cant_eg'];
}
if(isset($htrece)){
$pdf->SetY(237);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($htrece),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hcato=$resp_r['cant_eg'];
}
if(isset($hcato)){
$pdf->SetY(237);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($hcato),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hquin=$resp_r['cant_eg'];
}
if(isset($hquin)){
$pdf->SetY(237);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($hquin),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdseis=$resp_r['cant_eg'];
}
if(isset($hdseis)){
$pdf->SetY(237);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($hdseis),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdsiete=$resp_r['cant_eg'];
}
if(isset($hdsiete)){
$pdf->SetY(237);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($hdsiete),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdocho=$resp_r['cant_eg'];
}
if(isset($hdocho)){
$pdf->SetY(237);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($hdocho),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdn=$resp_r['cant_eg'];
}
if(isset($hdn)){
$pdf->SetY(237);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($hdn),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dve=$resp_r['cant_eg'];
}
if(isset($dve)){
$pdf->SetY(237);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($dve),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hvuno=$resp_r['cant_eg'];
}
if(isset($hvuno)){
$pdf->SetY(237);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($hvuno),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hvdos=$resp_r['cant_eg'];
}
if(isset($hvdos)){
$pdf->SetY(237);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($hvdos),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hvtres=$resp_r['cant_eg'];
}
if(isset($hvtres)){
$pdf->SetY(237);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($hvtres),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hvcuatro=$resp_r['cant_eg'];
}
if(isset($hvcuatro)){
$pdf->SetY(237);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($hvcuatro),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $huno=$resp_r['cant_eg'];
}
if(isset($huno)){
$pdf->SetY(237);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($huno),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hdos=$resp_r['cant_eg'];
}
if(isset($hdos)){
$pdf->SetY(237);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($hdos),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $htres=$resp_r['cant_eg'];
}
if(isset($htres)){
$pdf->SetY(237);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($htres),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hcuatro=$resp_r['cant_eg'];
}
if(isset($hcuatro)){
$pdf->SetY(237);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($hcuatro),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hcinco=$resp_r['cant_eg'];
}
if(isset($hcinco)){
$pdf->SetY(237);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($hcinco),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hseis=$resp_r['cant_eg'];
}
if(isset($hseis)){
$pdf->SetY(237);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($hseis),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $hsiete=$resp_r['cant_eg'];
}
if(isset($hsiete)){
$pdf->SetY(237);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($hsiete),1,0,'C');
}else{
  $pdf->SetY(237);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACIONES EVACUACUONES EG

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evnoct=$resp_r['cant_eg'];
}
if(isset($evnoct)){
$pdf->SetY(243);
$pdf->SetX(40);
$pdf->Cell(7,6, utf8_decode($evnoct),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(40);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evn=$resp_r['cant_eg'];
}
if(isset($evn)){
$pdf->SetY(243);
$pdf->SetX(47);
$pdf->Cell(7,6, utf8_decode($evn),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(47);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdies=$resp_r9['cant_eg'];
}
if(isset($evdies)){
$pdf->SetY(243);
$pdf->SetX(54);
$pdf->Cell(7,6, utf8_decode($evdies),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(54);
 $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evonce=$resp_r['cant_eg'];
}
if(isset($evonce)){
$pdf->SetY(243);
$pdf->SetX(61);
$pdf->Cell(7,6, utf8_decode($evonce),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(61);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdoce=$resp_r['cant_eg'];
}
if(isset($evdoce)){
$pdf->SetY(243);
$pdf->SetX(68);
$pdf->Cell(7,6, utf8_decode($evdoce),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(68);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evtrece=$resp_r['cant_eg'];
}
if(isset($evtrece)){
$pdf->SetY(243);
$pdf->SetX(75);
$pdf->Cell(7,6, utf8_decode($evtrece),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(75);
      $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evcato=$resp_r['cant_eg'];
}
if(isset($evcato)){
$pdf->SetY(243);
$pdf->SetX(82);
$pdf->Cell(7,6, utf8_decode($evcato),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(82);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evquin=$resp_r['cant_eg'];
}
if(isset($evquin)){
$pdf->SetY(243);
$pdf->SetX(89);
$pdf->Cell(7,6, utf8_decode($evquin),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(89);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdse=$resp_r['cant_eg'];
}
if(isset($evdse)){
$pdf->SetY(243);
$pdf->SetX(96);
$pdf->Cell(7,6, utf8_decode($evdse),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(96);
    $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdsiete=$resp_r['cant_eg'];
}
if(isset($evdsiete)){
$pdf->SetY(243);
$pdf->SetX(103);
$pdf->Cell(7,6, utf8_decode($evdsiete),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(103);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdocho=$resp_r['cant_eg'];
}
if(isset($evdocho)){
$pdf->SetY(243);
$pdf->SetX(110);
$pdf->Cell(7,6, utf8_decode($evdocho),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(110);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdn=$resp_r['cant_eg'];
}
if(isset($evdn)){
$pdf->SetY(243);
$pdf->SetX(117);
$pdf->Cell(7,6, utf8_decode($evdn),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(117);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvei=$resp_r['cant_eg'];
}
if(isset($evvei)){
$pdf->SetY(243);
$pdf->SetX(124);
$pdf->Cell(7,6, utf8_decode($evvei),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(124);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvuno=$resp_r['cant_eg'];
}
if(isset($evvuno)){
$pdf->SetY(243);
$pdf->SetX(131);
$pdf->Cell(7,6, utf8_decode($evvuno),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(131);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvdos=$resp_r['cant_eg'];
}
if(isset($evvdos)){
$pdf->SetY(243);
$pdf->SetX(138);
$pdf->Cell(7,6, utf8_decode($evvdos),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(138);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvtres=$resp_r['cant_eg'];
}
if(isset($evvtres)){
$pdf->SetY(243);
$pdf->SetX(145);
$pdf->Cell(7,6, utf8_decode($evvtres),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(145);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evvc=$resp_r['cant_eg'];
}
if(isset($evvc)){
$pdf->SetY(243);
$pdf->SetX(152);
$pdf->Cell(7,6, utf8_decode($evvc),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(152);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evuno=$resp_r['cant_eg'];
}
if(isset($evuno)){
$pdf->SetY(243);
$pdf->SetX(159);
$pdf->Cell(7,6, utf8_decode($evuno),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(159);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evdos=$resp_r['cant_eg'];
}
if(isset($evdos)){
$pdf->SetY(243);
$pdf->SetX(166);
$pdf->Cell(7,6, utf8_decode($evdos),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(166);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evtres=$resp_r['cant_eg'];
}
if(isset($evtres)){
$pdf->SetY(243);
$pdf->SetX(173);
$pdf->Cell(7,6, utf8_decode($evtres),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(173);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evcuatro=$resp_r['cant_eg'];
}
if(isset($evcuatro)){
$pdf->SetY(243);
$pdf->SetX(180);
$pdf->Cell(7,6, utf8_decode($evcuatro),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(180);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evcin=$resp_r['cant_eg'];
}
if(isset($evcin)){
$pdf->SetY(243);
$pdf->SetX(187);
$pdf->Cell(7,6, utf8_decode($evcin),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(187);
   $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evseis=$resp_r['cant_eg'];
}
if(isset($evseis)){
$pdf->SetY(243);
$pdf->SetX(194);
$pdf->Cell(7,6, utf8_decode($evseis),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(194);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $evsiete=$resp_r['cant_eg'];
}
if(isset($evsiete)){
$pdf->SetY(243);
$pdf->SetX(201);
$pdf->Cell(7,6, utf8_decode($evsiete),1,0,'C');
}else{
  $pdf->SetY(243);
$pdf->SetX(201);
  $pdf->Cell(7,6, utf8_decode(''),1,0,'C');
}


//DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE DRENAJE



$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dnoctt=$resp_r['cant_eg'];
}
if(isset($dnoctt)){
$pdf->SetY(249);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($dnoctt),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dn1=$resp_r['cant_eg'];
}
if(isset($dn1)){
$pdf->SetY(249);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($dn1),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddi=$resp_r9['cant_eg'];
}
if(isset($ddi)){
$pdf->SetY(249);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ddi),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $donce=$resp_r['cant_eg'];
}
if(isset($donce)){
$pdf->SetY(249);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($donce),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddoce=$resp_r['cant_eg'];
}
if(isset($ddoce)){
$pdf->SetY(249);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ddoce),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='13' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dtrece=$resp_r['cant_eg'];
}
if(isset($dtrece)){
$pdf->SetY(249);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($dtrece),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dcat=$resp_r['cant_eg'];
}
if(isset($dcat)){
$pdf->SetY(249);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($dcat),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dquince=$resp_r['cant_eg'];
}
if(isset($dquince)){
$pdf->SetY(249);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($dquince),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddseis=$resp_r['cant_eg'];
}
if(isset($ddseis)){
$pdf->SetY(249);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ddseis),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddsiete=$resp_r['cant_eg'];
}
if(isset($ddsiete)){
$pdf->SetY(249);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ddsiete),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddocho=$resp_r['cant_eg'];
}
if(isset($ddocho)){
$pdf->SetY(249);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ddocho),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddnue=$resp_r['cant_eg'];
}
if(isset($ddnue)){
$pdf->SetY(249);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ddnue),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvein=$resp_r['cant_eg'];
}
if(isset($dvein)){
$pdf->SetY(249);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($dvein),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvuno=$resp_r['cant_eg'];
}
if(isset($dvuno)){
$pdf->SetY(249);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($dvuno),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvdos=$resp_r['cant_eg'];
}
if(isset($dvdos)){
$pdf->SetY(249);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($dvdos),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvtres=$resp_r['cant_eg'];
}
if(isset($dvtres)){
$pdf->SetY(249);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($dvtres),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvcuatro=$resp_r['cant_eg'];
}
if(isset($dvcuatro)){
$pdf->SetY(249);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($dvcuatro),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $duno=$resp_r['cant_eg'];
}
if(isset($duno)){
$pdf->SetY(249);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($duno),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddos=$resp_r['cant_eg'];
}
if(isset($ddos)){
$pdf->SetY(249);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ddos),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dtres=$resp_r['cant_eg'];
}
if(isset($dtres)){
$pdf->SetY(249);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($dtres),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dcuatro=$resp_r['cant_eg'];
}
if(isset($dcuatro)){
$pdf->SetY(249);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($dcuatro),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dcinco=$resp_r['cant_eg'];
}
if(isset($dcinco)){
$pdf->SetY(249);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($dcinco),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dseis=$resp_r['cant_eg'];
}
if(isset($dseis)){
$pdf->SetY(249);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($dseis),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsiete=$resp_r['cant_eg'];
}
if(isset($dsiete)){
$pdf->SetY(249);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($dsiete),1,0,'C');
}else{
  $pdf->SetY(249);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//PENROSE DERECHO PENROSE DERECHO PENROSE DERECHO PENROSE DERECHO PENROSE RECHO

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dnocttpd=$resp_r['cant_eg'];
}
if(isset($dnocttpd)){
$pdf->SetY(252);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($dnocttpd),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dn1p=$resp_r['cant_eg'];
}
if(isset($dn1p)){
$pdf->SetY(252);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($dn1p),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddip=$resp_r9['cant_eg'];
}
if(isset($ddip)){
$pdf->SetY(252);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($ddip),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $doncep=$resp_r['cant_eg'];
}
if(isset($doncep)){
$pdf->SetY(252);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($doncep),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddocep=$resp_r['cant_eg'];
}
if(isset($ddocep)){
$pdf->SetY(252);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($ddocep),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dtrecep=$resp_r['cant_eg'];
}
if(isset($dtrecep)){
$pdf->SetY(252);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($dtrecep),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dcatp=$resp_r['cant_eg'];
}
if(isset($dcatp)){
$pdf->SetY(252);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($dcatp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dquincep=$resp_r['cant_eg'];
}
if(isset($dquincep)){
$pdf->SetY(252);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($dquincep),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddseisp=$resp_r['cant_eg'];
}
if(isset($ddseisp)){
$pdf->SetY(252);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($ddseisp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddsietep=$resp_r['cant_eg'];
}
if(isset($ddsietep)){
$pdf->SetY(252);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($ddsietep),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddochop=$resp_r['cant_eg'];
}
if(isset($ddochop)){
$pdf->SetY(252);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($ddochop),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddnuep=$resp_r['cant_eg'];
}
if(isset($ddnuep)){
$pdf->SetY(252);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($ddnuep),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dveinp=$resp_r['cant_eg'];
}
if(isset($dveinp)){
$pdf->SetY(252);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($dveinp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvunop=$resp_r['cant_eg'];
}
if(isset($dvunop)){
$pdf->SetY(252);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($dvunop),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvdosp=$resp_r['cant_eg'];
}
if(isset($dvdosp)){
$pdf->SetY(252);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($dvdosp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvtresp=$resp_r['cant_eg'];
}
if(isset($dvtresp)){
$pdf->SetY(252);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($dvtresp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dvcuatrop=$resp_r['cant_eg'];
}
if(isset($dvcuatrop)){
$pdf->SetY(252);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($dvcuatrop),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dunop=$resp_r['cant_eg'];
}
if(isset($dunop)){
$pdf->SetY(252);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($dunop),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $ddospp=$resp_r['cant_eg'];
}
if(isset($ddospp)){
$pdf->SetY(252);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($ddospp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dtrespp=$resp_r['cant_eg'];
}
if(isset($dtrespp)){
$pdf->SetY(252);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($dtrespp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dcuatropp=$resp_r['cant_eg'];
}
if(isset($dcuatropp)){
$pdf->SetY(252);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($dcuatropp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dcincopp=$resp_r['cant_eg'];
}
if(isset($dcincopp)){
$pdf->SetY(252);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($dcincopp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dseispp=$resp_r['cant_eg'];
}
if(isset($dseispp)){
$pdf->SetY(252);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($dseispp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE DER' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dsietepp=$resp_r['cant_eg'];
}
if(isset($dsietepp)){
$pdf->SetY(252);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($dsietepp),1,0,'C');
}else{
  $pdf->SetY(252);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}
//BOIVACK BIO VACK VIOBACK BIOVACK BIOVACK BIOVACK BIOVACK BIOBACK

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bionoct=$resp_r['cant_eg'];
}
if(isset($bionoct)){
$pdf->SetY(255);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($bionoct),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bion=$resp_r['cant_eg'];
}
if(isset($bion)){
$pdf->SetY(255);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($bion),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biod=$resp_r9['cant_eg'];
}
if(isset($biod)){
$pdf->SetY(255);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($biod),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioo=$resp_r['cant_eg'];
}
if(isset($bioo)){
$pdf->SetY(255);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($bioo),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodoce=$resp_r['cant_eg'];
}
if(isset($biodoce)){
$pdf->SetY(255);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($biodoce),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biotrece=$resp_r['cant_eg'];
}
if(isset($biotrece)){
$pdf->SetY(255);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($biotrece),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biocat=$resp_r['cant_eg'];
}
if(isset($biocat)){
$pdf->SetY(255);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($biocat),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioquin=$resp_r['cant_eg'];
}
if(isset($bioquin)){
$pdf->SetY(255);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($bioquin),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodseis=$resp_r['cant_eg'];
}
if(isset($biodseis)){
$pdf->SetY(255);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($biodseis),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodsiete=$resp_r['cant_eg'];
}
if(isset($biodsiete)){
$pdf->SetY(255);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($biodsiete),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioocho=$resp_r['cant_eg'];
}
if(isset($bioocho)){
$pdf->SetY(255);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($bioocho),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodnue=$resp_r['cant_eg'];
}
if(isset($biodnue)){
$pdf->SetY(255);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($biodnue),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioveinte=$resp_r['cant_eg'];
}
if(isset($bioveinte)){
$pdf->SetY(255);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($bioveinte),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovuno=$resp_r['cant_eg'];
}
if(isset($biovuno)){
$pdf->SetY(255);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($biovuno),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovdos=$resp_r['cant_eg'];
}
if(isset($biovdos)){
$pdf->SetY(255);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($biovdos),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovtres=$resp_r['cant_eg'];
}
if(isset($biovtres)){
$pdf->SetY(255);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($biovtres),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovcuatro=$resp_r['cant_eg'];
}
if(isset($biovcuatro)){
$pdf->SetY(255);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($biovcuatro),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biouno=$resp_r['cant_eg'];
}
if(isset($biouno)){
$pdf->SetY(255);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($biouno),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodos=$resp_r['cant_eg'];
}
if(isset($biodos)){
$pdf->SetY(255);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($biodos),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biotres=$resp_r['cant_eg'];
}
if(isset($biotres)){
$pdf->SetY(255);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($biotres),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biocuatro=$resp_r['cant_eg'];
}
if(isset($biocuatro)){
$pdf->SetY(255);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($biocuatro),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='5' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biocinco=$resp_r['cant_eg'];
}
if(isset($biocinco)){
$pdf->SetY(255);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($biocinco),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioseis=$resp_r['cant_eg'];
}
if(isset($bioseis)){
$pdf->SetY(255);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($bioseis),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC' and fecha_eg='$fechar' and hora_eg='7' and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biosiete=$resp_r['cant_eg'];
}
if(isset($biosiete)){
$pdf->SetY(255);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($biosiete),1,0,'C');
}else{
  $pdf->SetY(255);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//BOIVACK BIO VACK VIOBACK BIOVACK BIOVACK BIOVACK BIOVACK BIOBACK DERECHO DERECHO DERECHO DERECHO DERECHO DERCHO DERECHO DERECHO DERECHO

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bionoctd=$resp_r['cant_eg'];
}
if(isset($bionoctd)){
$pdf->SetY(258);
$pdf->SetX(40);
$pdf->Cell(7,3, utf8_decode($bionoctd),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(40);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biond=$resp_r['cant_eg'];
}
if(isset($biond)){
$pdf->SetY(258);
$pdf->SetX(47);
$pdf->Cell(7,3, utf8_decode($biond),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(47);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodd=$resp_r9['cant_eg'];
}
if(isset($biodd)){
$pdf->SetY(258);
$pdf->SetX(54);
$pdf->Cell(7,3, utf8_decode($biodd),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(54);
 $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biood=$resp_r['cant_eg'];
}
if(isset($biood)){
$pdf->SetY(258);
$pdf->SetX(61);
$pdf->Cell(7,3, utf8_decode($biood),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(61);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodoced=$resp_r['cant_eg'];
}
if(isset($biodoced)){
$pdf->SetY(258);
$pdf->SetX(68);
$pdf->Cell(7,3, utf8_decode($biodoced),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(68);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biotreced=$resp_r['cant_eg'];
}
if(isset($biotreced)){
$pdf->SetY(258);
$pdf->SetX(75);
$pdf->Cell(7,3, utf8_decode($biotreced),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(75);
      $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biocatd=$resp_r['cant_eg'];
}
if(isset($biocatd)){
$pdf->SetY(258);
$pdf->SetX(82);
$pdf->Cell(7,3, utf8_decode($biocatd),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(82);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioquind=$resp_r['cant_eg'];
}
if(isset($bioquind)){
$pdf->SetY(258);
$pdf->SetX(89);
$pdf->Cell(7,3, utf8_decode($bioquind),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(89);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodseisd=$resp_r['cant_eg'];
}
if(isset($biodseisd)){
$pdf->SetY(258);
$pdf->SetX(96);
$pdf->Cell(7,3, utf8_decode($biodseisd),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(96);
    $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodsieted=$resp_r['cant_eg'];
}
if(isset($biodsieted)){
$pdf->SetY(258);
$pdf->SetX(103);
$pdf->Cell(7,3, utf8_decode($biodsieted),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(103);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioochod=$resp_r['cant_eg'];
}
if(isset($bioochod)){
$pdf->SetY(258);
$pdf->SetX(110);
$pdf->Cell(7,3, utf8_decode($bioochod),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(110);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodnued=$resp_r['cant_eg'];
}
if(isset($biodnued)){
$pdf->SetY(258);
$pdf->SetX(117);
$pdf->Cell(7,3, utf8_decode($biodnued),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(117);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioveinted=$resp_r['cant_eg'];
}
if(isset($bioveinted)){
$pdf->SetY(258);
$pdf->SetX(124);
$pdf->Cell(7,3, utf8_decode($bioveinted),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(124);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovunod=$resp_r['cant_eg'];
}
if(isset($biovunod)){
$pdf->SetY(258);
$pdf->SetX(131);
$pdf->Cell(7,3, utf8_decode($biovunod),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(131);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovdosd=$resp_r['cant_eg'];
}
if(isset($biovdosd)){
$pdf->SetY(258);
$pdf->SetX(138);
$pdf->Cell(7,3, utf8_decode($biovdosd),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(138);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovtresd=$resp_r['cant_eg'];
}
if(isset($biovtresd)){
$pdf->SetY(258);
$pdf->SetX(145);
$pdf->Cell(7,3, utf8_decode($biovtresd),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(145);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biovcuatrod=$resp_r['cant_eg'];
}
if(isset($biovcuatrod)){
$pdf->SetY(258);
$pdf->SetX(152);
$pdf->Cell(7,3, utf8_decode($biovcuatrod),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(152);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biounod=$resp_r['cant_eg'];
}
if(isset($biounod)){
$pdf->SetY(258);
$pdf->SetX(159);
$pdf->Cell(7,3, utf8_decode($biounod),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(159);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biodosd=$resp_r['cant_eg'];
}
if(isset($biodosd)){
$pdf->SetY(258);
$pdf->SetX(166);
$pdf->Cell(7,3, utf8_decode($biodosd),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(166);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biotresd=$resp_r['cant_eg'];
}
if(isset($biotresd)){
$pdf->SetY(258);
$pdf->SetX(173);
$pdf->Cell(7,3, utf8_decode($biotresd),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(173);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biocuatrod=$resp_r['cant_eg'];
}
if(isset($biocuatrod)){
$pdf->SetY(258);
$pdf->SetX(180);
$pdf->Cell(7,3, utf8_decode($biocuatrod),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(180);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biocincod=$resp_r['cant_eg'];
}
if(isset($biocincod)){
$pdf->SetY(258);
$pdf->SetX(187);
$pdf->Cell(7,3, utf8_decode($biocincod),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(187);
   $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $bioseisd=$resp_r['cant_eg'];
}
if(isset($bioseisd)){
$pdf->SetY(258);
$pdf->SetX(194);
$pdf->Cell(7,3, utf8_decode($bioseisd),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(194);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='BIOVAC DER' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $biosieted=$resp_r['cant_eg'];
}
if(isset($biosieted)){
$pdf->SetY(258);
$pdf->SetX(201);
$pdf->Cell(7,3, utf8_decode($biosieted),1,0,'C');
}else{
  $pdf->SetY(258);
$pdf->SetX(201);
  $pdf->Cell(7,3, utf8_decode(''),1,0,'C');
}

//DRENOVACK DRENOVACK DRENOVACK DRENOVACK DRENOVACK DRENOVACK DRENOVAVK EG

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drnoct=$resp_r['cant_eg'];
}
if(isset($drnoct)){
$pdf->SetY(261);
$pdf->SetX(40);
$pdf->Cell(7,4, utf8_decode($drnoct),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(40);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drn=$resp_r['cant_eg'];
}
if(isset($drn)){
$pdf->SetY(261);
$pdf->SetX(47);
$pdf->Cell(7,4, utf8_decode($drn),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(47);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drd=$resp_r9['cant_eg'];
}
if(isset($drd)){
$pdf->SetY(261);
$pdf->SetX(54);
$pdf->Cell(7,4, utf8_decode($drd),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(54);
 $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $dron=$resp_r['cant_eg'];
}
if(isset($dron)){
$pdf->SetY(261);
$pdf->SetX(61);
$pdf->Cell(7,4, utf8_decode($dron),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(61);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdoce=$resp_r['cant_eg'];
}
if(isset($drdoce)){
$pdf->SetY(261);
$pdf->SetX(68);
$pdf->Cell(7,4, utf8_decode($drdoce),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(68);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drtrece=$resp_r['cant_eg'];
}
if(isset($drtrece)){
$pdf->SetY(261);
$pdf->SetX(75);
$pdf->Cell(7,4, utf8_decode($drtrece),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(75);
      $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drcat=$resp_r['cant_eg'];
}
if(isset($drcat)){
$pdf->SetY(261);
$pdf->SetX(82);
$pdf->Cell(7,4, utf8_decode($drcat),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(82);
    $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drquin=$resp_r['cant_eg'];
}
if(isset($drquin)){
$pdf->SetY(261);
$pdf->SetX(89);
$pdf->Cell(7,4, utf8_decode($drquin),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(89);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdseis=$resp_r['cant_eg'];
}
if(isset($drdseis)){
$pdf->SetY(261);
$pdf->SetX(96);
$pdf->Cell(7,4, utf8_decode($drdseis),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(96);
    $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdsiete=$resp_r['cant_eg'];
}
if(isset($drdsiete)){
$pdf->SetY(261);
$pdf->SetX(103);
$pdf->Cell(7,4, utf8_decode($drdsiete),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(103);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='18' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdocho=$resp_r['cant_eg'];
}
if(isset($drdocho)){
$pdf->SetY(261);
$pdf->SetX(110);
$pdf->Cell(7,4, utf8_decode($drdocho),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(110);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdnueve=$resp_r['cant_eg'];
}
if(isset($drdnueve)){
$pdf->SetY(261);
$pdf->SetX(117);
$pdf->Cell(7,4, utf8_decode($drdnueve),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(117);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drveinte=$resp_r['cant_eg'];
}
if(isset($drveinte)){
$pdf->SetY(261);
$pdf->SetX(124);
$pdf->Cell(7,4, utf8_decode($drveinte),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(124);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='21' and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drveintuno=$resp_r['cant_eg'];
}
if(isset($drveintuno)){
$pdf->SetY(261);
$pdf->SetX(131);
$pdf->Cell(7,4, utf8_decode($drveintuno),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(131);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='22' and neonato!='Si'  and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drvdos=$resp_r['cant_eg'];
}
if(isset($drvdos)){
$pdf->SetY(261);
$pdf->SetX(138);
$pdf->Cell(7,4, utf8_decode($drvdos),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(138);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drvtres=$resp_r['cant_eg'];
}
if(isset($drvtres)){
$pdf->SetY(261);
$pdf->SetX(145);
$pdf->Cell(7,4, utf8_decode($drvtres),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(145);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) { 
  $pdf->SetFont('Arial', '', 6);
  $drvcuatro=$resp_r['cant_eg'];
}
if(isset($drvcuatro)){
$pdf->SetY(261);
$pdf->SetX(152);
$pdf->Cell(7,4, utf8_decode($drvcuatro),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(152);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drun=$resp_r['cant_eg'];
}
if(isset($drun)){
$pdf->SetY(261);
$pdf->SetX(159);
$pdf->Cell(7,4, utf8_decode($drun),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(159);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drdos=$resp_r['cant_eg'];
}
if(isset($drdos)){
$pdf->SetY(261);
$pdf->SetX(166);
$pdf->Cell(7,4, utf8_decode($drdos),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(166);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drtres=$resp_r['cant_eg'];
}
if(isset($drtres)){
$pdf->SetY(261);
$pdf->SetX(173);
$pdf->Cell(7,4, utf8_decode($drtres),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(173);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drcua=$resp_r['cant_eg'];
}
if(isset($drcua)){
$pdf->SetY(261);
$pdf->SetX(180);
$pdf->Cell(7,4, utf8_decode($drcua),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(180);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drcin=$resp_r['cant_eg'];
}
if(isset($drcin)){
$pdf->SetY(261);
$pdf->SetX(187);
$pdf->Cell(7,4, utf8_decode($drcin),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(187);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drseis=$resp_r['cant_eg'];
}
if(isset($drseis)){
$pdf->SetY(261);
$pdf->SetX(194);
$pdf->Cell(7,4, utf8_decode($drseis),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(194);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $drsiete=$resp_r['cant_eg'];
}
if(isset($drsiete)){
$pdf->SetY(261);
$pdf->SetX(201);
$pdf->Cell(7,4, utf8_decode($drsiete),1,0,'C');
}else{
  $pdf->SetY(261);
$pdf->SetX(201);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


//RIOBACK RIO BACK RIOBACXK RIO BACK RIOBACK RIO BACK
$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='8'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rionoct=$resp_r['cant_eg'];
}
if(isset($rionoct)){
$pdf->SetY(265);
$pdf->SetX(40);
$pdf->Cell(7,4, utf8_decode($rionoct),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(40);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='9'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rion=$resp_r['cant_eg'];
}
if(isset($rion)){
$pdf->SetY(265);
$pdf->SetX(47);
$pdf->Cell(7,4, utf8_decode($rion),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(47);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp9 = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='10'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r9 = $resp9->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodies=$resp_r9['cant_eg'];
}
if(isset($riodies)){
$pdf->SetY(265);
$pdf->SetX(54);
$pdf->Cell(7,4, utf8_decode($riodies),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(54);
 $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='11'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rioonce=$resp_r['cant_eg'];
}
if(isset($rioonce)){
$pdf->SetY(265);
$pdf->SetX(61);
$pdf->Cell(7,4, utf8_decode($rioonce),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(61);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='12'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodoce=$resp_r['cant_eg'];
}
if(isset($riodoce)){
$pdf->SetY(265);
$pdf->SetX(68);
$pdf->Cell(7,4, utf8_decode($riodoce),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(68);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='13'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riotrece=$resp_r['cant_eg'];
}
if(isset($riotrece)){
$pdf->SetY(265);
$pdf->SetX(75);
$pdf->Cell(7,4, utf8_decode($riotrece),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(75);
      $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='14'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riocat=$resp_r['cant_eg'];
}
if(isset($riocat)){
$pdf->SetY(265);
$pdf->SetX(82);
$pdf->Cell(7,4, utf8_decode($riocat),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(82);
    $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}
//TER GLAS vesp

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='15'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rioquin=$resp_r['cant_eg'];
}
if(isset($rioquin)){
$pdf->SetY(265);
$pdf->SetX(89);
$pdf->Cell(7,4, utf8_decode($rioquin),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(89);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='16'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodsesis=$resp_r['cant_eg'];
}
if(isset($riodsesis)){
$pdf->SetY(265);
$pdf->SetX(96);
$pdf->Cell(7,4, utf8_decode($riodsesis),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(96);
    $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='17'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodsiete=$resp_r['cant_eg'];
}
if(isset($riodsiete)){
$pdf->SetY(265);
$pdf->SetX(103);
$pdf->Cell(7,4, utf8_decode($riodsiete),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(103);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='18'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodocho=$resp_r['cant_eg'];
}
if(isset($riodocho)){
$pdf->SetY(265);
$pdf->SetX(110);
$pdf->Cell(7,4, utf8_decode($riodocho),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(110);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='19'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodnueve=$resp_r['cant_eg'];
}
if(isset($riodnueve)){
$pdf->SetY(265);
$pdf->SetX(117);
$pdf->Cell(7,4, utf8_decode($riodnueve),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(117);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='20'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rioveinte=$resp_r['cant_eg'];
}
if(isset($rioveinte)){
$pdf->SetY(265);
$pdf->SetX(124);
$pdf->Cell(7,4, utf8_decode($rioveinte),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(124);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='21'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riovuno=$resp_r['cant_eg'];
}
if(isset($riovuno)){
$pdf->SetY(265);
$pdf->SetX(131);
$pdf->Cell(7,4, utf8_decode($riovuno),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(131);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='22'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riovdos=$resp_r['cant_eg'];
}
if(isset($riovdos)){
$pdf->SetY(265);
$pdf->SetX(138);
$pdf->Cell(7,4, utf8_decode($riovdos),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(138);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='23'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riovtres=$resp_r['cant_eg'];
}
if(isset($riovtres)){
$pdf->SetY(265);
$pdf->SetX(145);
$pdf->Cell(7,4, utf8_decode($riovtres),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(145);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='24'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riovcuatro=$resp_r['cant_eg'];
}
if(isset($riovcuatro)){
$pdf->SetY(265);
$pdf->SetX(152);
$pdf->Cell(7,4, utf8_decode($riovcuatro),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(152);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='1'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riouno=$resp_r['cant_eg'];
}
if(isset($riouno)){
$pdf->SetY(265);
$pdf->SetX(159);
$pdf->Cell(7,4, utf8_decode($riouno),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(159);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='2'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riodoos=$resp_r['cant_eg'];
}
if(isset($riodoos)){
$pdf->SetY(265);
$pdf->SetX(166);
$pdf->Cell(7,4, utf8_decode($riodoos),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(166);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='3'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riotres=$resp_r['cant_eg'];
}
if(isset($riotres)){
$pdf->SetY(265);
$pdf->SetX(173);
$pdf->Cell(7,4, utf8_decode($riotres),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(173);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='4'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riocuat=$resp_r['cant_eg'];
}
if(isset($riocuat)){
$pdf->SetY(265);
$pdf->SetX(180);
$pdf->Cell(7,4, utf8_decode($riocuat),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(180);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='5'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riocin=$resp_r['cant_eg'];
}
if(isset($riocin)){
$pdf->SetY(265);
$pdf->SetX(187);
$pdf->Cell(7,4, utf8_decode($riocin),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(187);
   $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}

$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='6'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $rioseis=$resp_r['cant_eg'];
}
if(isset($rioseis)){
$pdf->SetY(265);
$pdf->SetX(194);
$pdf->Cell(7,4, utf8_decode($rioseis),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(194);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}


$resp = $conexion->query("select * from eg_enf_quir where des_eg='PENROSE' and fecha_eg='$fechar' and hora_eg='7'  and neonato!='Si' and id_atencion=$id_atencion") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $riosiete=$resp_r['cant_eg'];
}
if(isset($riosiete)){
$pdf->SetY(265);
$pdf->SetX(201);
$pdf->Cell(7,4, utf8_decode($riosiete),1,0,'C');
}else{
  $pdf->SetY(265);
$pdf->SetX(201);
  $pdf->Cell(7,4, utf8_decode(''),1,0,'C');
}



//FIN CONSULTAS FIN CONSULTAS POR HORAS FIN CONSULTAS POR HORAS


//EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL MATUTINO

$resp = $conexion->query("select SUM(cant_eg) as sumo from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumo=$resp_r['sumo'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumvom from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumvom=$resp_r['sumvom'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsan from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsan=$resp_r['sumsan'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsonda from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsonda=$resp_r['sumsonda'];
}


$resp = $conexion->query("select SUM(cant_eg) as sumher from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumher=$resp_r['sumher'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumeva from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumeva=$resp_r['sumeva'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumdren from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdren=$resp_r['sumdren'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbio from eg_enf_quir where (des_eg='BIOVAC' || des_eg='BIOVAC DER') and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbio=$resp_r['sumbio'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdreno from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdreno=$resp_r['sumdreno'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumrio from eg_enf_quir where (des_eg='PENROSE' || des_eg='PENROSE DER') and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumrio=$resp_r['sumrio'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsara from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsara=$resp_r['sumsara'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumestomas from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='8' or hora_eg='9' or hora_eg='10' or hora_eg='11' or hora_eg='12' or hora_eg='13' or hora_eg='14')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumestomas=$resp_r['sumestomas'];
}

$sumatotalegr=$sumo+$sumvom+$sumsan+$sumsonda+$sumher+$sumeva+$sumdren+$sumbio+$sumdreno+$sumrio+$sumsara+$sumestomas;

/*if(isset($sum)){
*/
$pdf->SetY(269);
$pdf->SetX(40);
$pdf->Cell(49,3, utf8_decode($sumatotalegr . ' ML'),1,0,'C');
/*
}else{
  $pdf->SetY(206);
$pdf->SetX(40);
  $pdf->Cell(42,6, utf8_decode(''),1,0,'C');
}*/

//EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL EGRESO PARCIAL TOTAL VESPERTINO

$resp = $conexion->query("select SUM(cant_eg) as sumov from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumov=$resp_r['sumov'];
}

$resp = $conexion->query("select SUM(cant_eg) as summedveg from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedveg=$resp_r['summedveg'];
}

$resp = $conexion->query("select SUM(cant_eg) as viaveg from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $viaveg=$resp_r['viaveg'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumsondaeg from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumsondaeg=$resp_r['sumsondaeg'];
}


$resp = $conexion->query("select SUM(cant_eg) as sumhereg from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumhereg=$resp_r['sumhereg'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumevaeg from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumevaeg=$resp_r['sumevaeg'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumdreneg from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdreneg=$resp_r['sumdreneg'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbioeg from eg_enf_quir where (des_eg='BIOVAC' || des_eg='BIOVAC DER') and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbioeg=$resp_r['sumbioeg'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdrenoeg from eg_enf_quir where des_eg='DRENOVACK' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdrenoeg=$resp_r['sumdrenoeg'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumrioeg from eg_enf_quir where (des_eg='PENROSE'|| des_eg='PENROSE DER') and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumrioeg=$resp_r['sumrioeg'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumtoga from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumtoga=$resp_r['sumtoga'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumtomas from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='15' or hora_eg='16' or hora_eg='17' or hora_eg='18' or hora_eg='19' or hora_eg='20')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumtomas=$resp_r['sumtomas'];
}

$sumatotalveg=$sumov+$summedveg+$viaveg+$sumsondaeg+$sumhereg+$sumevaeg+$sumdreneg+$sumbioeg+$sumdrenoeg+$sumrioeg+$sumtoga+$sumtomas;


/*if(isset($g9)){
*/
$pdf->SetY(269);
$pdf->SetX(89);
$pdf->Cell(42,3, utf8_decode($sumatotalveg . ' ML'),1,0,'C');
/*
}else{
  $pdf->SetY(206);
$pdf->SetX(82);
 $pdf->Cell(49,6, utf8_decode(''),1,0,'C');
}*/

//INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL INGRESO PARCIAL TOTAL NOCTURNO NOCTURNO

$resp = $conexion->query("select SUM(cant_eg) as sumbasen from eg_enf_quir where des_eg='ORINA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbasen=$resp_r['sumbasen'];
}

$resp = $conexion->query("select SUM(cant_eg) as summedn from eg_enf_quir where des_eg='VOMITO' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $summedn=$resp_r['summedn'];
}

$resp = $conexion->query("select SUM(cant_eg) as vian from eg_enf_quir where des_eg='SANGRADO' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $vian=$resp_r['vian'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumAMINASn from eg_enf_quir where des_eg='SONDA NASOGASTRICA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumAMINASn=$resp_r['sumAMINASn'];
}


$resp = $conexion->query("select SUM(cant_eg) as sumcantidadn from eg_enf_quir where des_eg='HERIDA QUIRURGICA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumcantidadn=$resp_r['sumcantidadn'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumn from eg_enf_quir where des_eg='EVACUACIONES' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumn=$resp_r['sumn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdrenajesegn from eg_enf_quir where des_eg='DRENAJES' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdrenajesegn=$resp_r['sumdrenajesegn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumbioegn from eg_enf_quir where (des_eg='BIOVAC' || des_eg='BIOVAC DER') and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumbioegn=$resp_r['sumbioegn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumdrenoegn from eg_enf_quir where des_eg='DRENOVAC' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumdrenoegn=$resp_r['sumdrenoegn'];
}
$resp = $conexion->query("select SUM(cant_eg) as sumrioegn from eg_enf_quir where (des_eg='PENROSE' || des_eg='PENROSE DER')  and neonato!='Si' and fecha_eg='$fechar' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumrioegn=$resp_r['sumrioegn'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumns from eg_enf_quir where des_eg='SARATOGA' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumns=$resp_r['sumns'];
}

$resp = $conexion->query("select SUM(cant_eg) as sumnoesto from eg_enf_quir where des_eg='ESTOMAS' and fecha_eg='$fechar'  and neonato!='Si' and id_atencion=$id_atencion and (hora_eg='21' or hora_eg='22' or hora_eg='23' or hora_eg='24' or hora_eg='1' or hora_eg='2' or hora_eg='3' or hora_eg='4' or hora_eg='5' or hora_eg='6' or hora_eg='7')") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
  $sumnoesto=$resp_r['sumnoesto'];
}

$sumaegresosn=$sumbasen+$summedn+$vian+$sumAMINASn+$sumcantidadn+$sumn+$sumdrenajesegn+$sumbioegn+$sumdrenoegn+$sumrioegn+$sumns+$sumnoesto;


//total global egresos
$totglobe=$sumatotalegr+$sumatotalveg+$sumaegresosn;

/*if(isset($g10)){
  */
$pdf->SetY(269);
$pdf->SetX(131);
$pdf->Cell(77,3, utf8_decode($sumaegresosn . ' ML'.'Total global: ' . $totglobe . 'ML'),1,0,'C');
/*
}else{

  $pdf->SetY(206);
$pdf->SetX(131);
  $pdf->Cell(77,6, utf8_decode(''),1,0,'C');
}
*/

//BALANCE TOTAL INGRESOS - EGRESOS DE LOS 3 TURNOS SUMATORIA FINAL
$sumaingresos=$sumatotal+$sumatotalv+$sumatotaln;
$sumaegresos=$sumatotalegr+$sumatotalveg+$sumaegresosn;
$TOTALRESTA=$sumaingresos-$sumaegresos;

$TOTRESTAMAT=$sumatotal-$sumatotalegr;
$TOTRESTAVESP=$sumatotalv-$sumatotalveg;
$TOTRESTANOCT=$sumatotaln-$sumaegresosn;

 $pdf->SetFont('Arial', 'B', 6);
$pdf->SetY(272);
$pdf->SetX(40);
$pdf->Cell(49,3, utf8_decode($TOTRESTAMAT . ' ML'),1,0,'C');

$pdf->SetY(272);
$pdf->SetX(89);
$pdf->Cell(42,3, utf8_decode($TOTRESTAVESP . ' ML'),1,0,'C');

$pdf->SetY(272);
$pdf->SetX(131);
$baltotiye=$totalglob-$totglobe;

$pdf->Cell(77,3, utf8_decode($TOTRESTANOCT . ' ML' . ' Balance total: ' . $baltotiye . ' ML'),1,0,'C');

//CONSULTA A TABLA PRINCIPAL


$val = "SELECT * FROM enf_reg_clin WHERE fecha_mat='$fechar' and hora_mat=$hora_mat and id_atencion=$id_atencion ORDER BY id_clinreg DESC";
$val_r = $conexion->query($val);
while ($val_res = $val_r->fetch_assoc()) {
/*$quemadura_mat=$val_res['quemadura_mat'];
$heridap_mat=$val_res['heridap_mat'];
$enfisema_mat=$val_res['enfisema_mat'];
$ulcpre_mat=$val_res['ulcpre_mat'];
$dermoabra_mat=$val_res['dermoabra_mat'];
$hematoma_mat=$val_res['hematoma_mat'];
$ciano_mat=$val_res['ciano_mat'];
$rash_mat=$val_res['rash_mat'];
$fracturas_mat=$val_res['fracturas_mat'];
$herquir_mat=$val_res['herquir_mat'];
$equimosis_mat=$val_res['equimosis_mat'];
$funprev_mat=$val_res['funprev_mat']; */

$fron_mat=$val_res['fron_mat'];
$malar_mat=$val_res['malar_mat'];
$mandi_mat=$val_res['mandi_mat'];
$delto_mat=$val_res['delto_mat'];
$pecto_mat=$val_res['pecto_mat'];
$esternal_mat=$val_res['esternal_mat'];
$antebrazo_mat=$val_res['antebrazo_mat'];
$muñeca_mat=$val_res['muñeca_mat'];
$manopal_mat=$val_res['manopal_mat'];
$muslo_mat=$val_res['muslo_mat'];
$rodilla_mat=$val_res['rodilla_mat'];
$pierna_mat=$val_res['pierna_mat'];
$pirnasal_mat=$val_res['pirnasal_mat'];
$maxsup_mat=$val_res['maxsup_mat'];
$menton_mat=$val_res['menton_mat'];
$acromial_mat=$val_res['acromial_mat'];
$brazo_mat=$val_res['brazo_mat'];
$plicodo_mat=$val_res['plicodo_mat'];
$abdomen_mat=$val_res['abdomen_mat'];
$regingui_mat=$val_res['regingui_mat'];
$regpub_mat=$val_res['regpub_mat'];
$pdedo_mat=$val_res['pdedo_mat'];
$sdedo_mat=$val_res['sdedo_mat'];
$tdedo_mat=$val_res['tdedo_mat'];
$cdedo_mat=$val_res['cdedo_mat'];
$qdedo_mat=$val_res['qdedo_mat'];
$tobi_mat=$val_res['tobi_mat'];
$piedor_mat=$val_res['piedor_mat'];
$parie_mat=$val_res['parie_mat'];
$occi_mat=$val_res['occi_mat'];
$nuca_mat=$val_res['nuca_mat'];
$brazo2_mat=$val_res['brazo2_mat'];
$codo2_mat=$val_res['codo2_mat'];
$antebrazo2_mat=$val_res['antebrazo2_mat'];
$muñeca2_mat=$val_res['muñeca2_mat'];

$manodor_mat=$val_res['manodor_mat'];
$plipop_mat=$val_res['plipop_mat'];
$pierna2_mat=$val_res['pierna2_mat'];
$pieplan_mat=$val_res['pieplan_mat'];
$cuellopos_mat=$val_res['cuellopos_mat'];
$reginter_mat=$val_res['reginter_mat'];
$regesca_mat=$val_res['regesca_mat'];
$reginfra_mat=$val_res['reginfra_mat'];
$lumbar_mat=$val_res['lumbar_mat'];
$gluteo_mat=$val_res['gluteo_mat'];
$muslo2_mat=$val_res['muslo2_mat'];
$talon2_mat=$val_res['talon2_mat'];
$antebrazo2_mat=$val_res['antebrazo2_mat'];
$muñeca2_mat=$val_res['muñeca2_mat'];
  
$ramsay_mat=$val_res['ramsay_mat'];
//$escaladolor_mat=$val_res['escaladolor_mat'];  
$apecular_mat=$val_res['apecular_mat'];  
$respmot_mat=$val_res['respmot_mat']; 
$respver_mat=$val_res['respver_mat']; 

$tamder_mat=$val_res['tamder_mat'];  
$tamizq_mat=$val_res['tamizq_mat'];  
$simder_mat=$val_res['simder_mat']; 
$simizq_mat=$val_res['simizq_mat']; 


$caidprev_mat=$val_res['caidprev_mat'];  
$medcaidas_mat=$val_res['medcaidas_mat'];  
$defsens_mat=$val_res['defsens_mat']; 
$edomental_mat=$val_res['edomental_mat']; 
$deambula_mat=$val_res['deambula_mat']; 
$totalcaidas_mat=$val_res['totalcaidas_mat'];
$clasriesg_mat=$val_res['clasriesg_mat']; 
$nomenfermera_mat=$val_res['nomenfermera_mat']; 
$riesgcaida_mat=$val_res['riesgcaida_mat'];

$edofisico_mat=$val_res['edofisico_mat'];  
$edomentalnor_mat=$val_res['edomentalnor_mat'];  
$actividad_mat=$val_res['actividad_mat']; 
$movilidad_mat=$val_res['movilidad_mat']; 
$incont_mat=$val_res['incont_mat']; 
$totnorton_mat=$val_res['totnorton_mat'];
$clasriesgnor_mat=$val_res['clasriesgnor_mat']; 
$nomenfnorton_mat=$val_res['nomenfnorton_mat']; 
$acriesg_mat=$val_res['acriesg_mat'];

//dispositivos
$ccalibre_mat=$val_res['ccalibre_mat'];  
$ctipo_mat=$val_res['ctipo_mat'];  
$cnomreal_mat=$val_res['cnomreal_mat']; 
$cdias_mat=$val_res['cdias_mat']; 
$cobserv_mat=$val_res['cobserv_mat']; 

$percali_mat=$val_res['percali_mat'];
$pertipo_mat=$val_res['pertipo_mat']; 
$pernomreal_mat=$val_res['pernomreal_mat']; 
$perdias_mat=$val_res['perdias_mat'];
$perobserv_mat=$val_res['perobserv_mat'];  

$per2cali_mat=$val_res['per2cali_mat'];  
$per2tipo_mat=$val_res['per2tipo_mat']; 
$per2nomreal_mat=$val_res['per2nomreal_mat']; 
$per2dias_mat=$val_res['per2dias_mat']; 
$per2observ_mat=$val_res['per2observ_mat'];

$tcali_mat=$val_res['tcali_mat']; 
$ttipo_mat=$val_res['ttipo_mat']; 
$tnomreal_mat=$val_res['tnomreal_mat'];
$tdias_mat=$val_res['tdias_mat'];  
$tobserv_mat=$val_res['tobserv_mat'];

$soncali_mat=$val_res['soncali_mat']; 
$sontipo_mat=$val_res['sontipo_mat']; 
$sonnomreal_mat=$val_res['sonnomreal_mat']; 
$sondias_mat=$val_res['sondias_mat'];
$sonobserv_mat=$val_res['sonobserv_mat']; 

$nascali_mat=$val_res['nascali_mat']; 
$nastipo_mat=$val_res['nastipo_mat']; 
$nasnomreal_mat=$val_res['nasnomreal_mat']; 
$nasdias_mat=$val_res['nasdias_mat'];
$nasobserv_mat=$val_res['nasobserv_mat']; 

}



$val = $conexion->query("select * from enf_reg_clin where fecha_mat='$fechar' and hora_mat=$hora_mat AND id_atencion=$id_atencion order by fecha_mat") or die($conexion->error);
while ($val_r = $val->fetch_assoc()) {
  //MARCAJE
$id_clinreg=$val_r['id_clinreg'];

$mara=$val_r['mara'];
$marb=$val_r['marb'];
$marc=$val_r['marc'];
$mard=$val_r['mard'];
$mare=$val_r['mare'];
$marf=$val_r['marf'];
$marg=$val_r['marg'];
$marh=$val_r['marh'];

$frenteizquierda=$val_r['frenteizquierda'];
$frentederecha=$val_r['frentederecha'];
$narizc=$val_r['narizc'];
$mejderecha=$val_r['mejderecha'];
$mandiizqui=$val_r['mandiizqui'];
$mandiderr=$val_r['mandiderr'];
$mandicentroo=$val_r['mandicentroo'];
$cvi=$val_r['cvi'];
$homi=$val_r['homi'];
$hombrod=$val_r['hombrod'];
$pecti=$val_r['pecti'];
$pectd=$val_r['pectd'];
$peci=$val_r['peci'];
$brazci=$val_r['brazci'];
$cconder=$val_r['cconder'];
$brazi=$val_r['brazi'];
$annbraz=$val_r['annbraz'];
$derbraz=$val_r['derbraz'];
$muñei=$val_r['muñei'];
$muñecad=$val_r['muñecad'];
$palmai=$val_r['palmai'];
$palmad=$val_r['palmad'];
$ddi=$val_r['ddi'];
$ddoderu=$val_r['ddoderu'];
$ddidos=$val_r['ddidos'];
$dedodos=$val_r['dedodos'];
$dditres=$val_r['dditres'];
$dedotres=$val_r['dedotres'];
$dedocuatro=$val_r['dedocuatro'];
$ddicuatro=$val_r['ddicuatro'];
$ddicinco=$val_r['ddicinco'];
$dedocincoo=$val_r['dedocincoo'];
$iabdomen=$val_r['iabdomen'];
$inglei=$val_r['inglei'];
$musloi=$val_r['musloi'];
$muslod=$val_r['muslod'];
$rodd=$val_r['rodd'];
$rodi=$val_r['rodi'];
$tod=$val_r['tod'];
$toi=$val_r['toi'];
$pied=$val_r['pied'];
$pie=$val_r['pie'];
$plantapiea=$val_r['plantapiea'];
$plantapieader=$val_r['plantapieader'];
$tobilloatd=$val_r['tobilloatd'];
$tobilloati=$val_r['tobilloati'];
$ptiatras=$val_r['ptiatras'];
$ptdatras=$val_r['ptdatras'];
$pierespaldad=$val_r['pierespaldad'];
$pierespaldai=$val_r['pierespaldai'];
$musloatrasiz=$val_r['musloatrasiz'];
$musloatrasder=$val_r['musloatrasder'];
$dorsaliz=$val_r['dorsaliz'];
$dorsalder=$val_r['dorsalder'];
$munecaatrasiz=$val_r['munecaatrasiz'];
$munecaatrasder=$val_r['munecaatrasder'];
$antebdesp=$val_r['antebdesp'];
$antebiesp=$val_r['antebiesp'];
$casicodoi=$val_r['casicodoi'];
$casicododer=$val_r['casicododer'];
$brazaltder=$val_r['brazaltder'];
$brazalti=$val_r['brazalti'];
$glutiz=$val_r['glutiz'];
$glutder=$val_r['glutder'];
$cinturader=$val_r['cinturader'];
$cinturaiz=$val_r['cinturaiz'];
$costilliz=$val_r['costilliz'];
$costillder=$val_r['costillder'];
$espaldaarribader=$val_r['espaldaarribader'];
$espaldarribaiz=$val_r['espaldarribaiz'];
$espaldaalta=$val_r['espaldaalta'];
$cuellatrasb=$val_r['cuellatrasb'];
$cuellatrasmedio=$val_r['cuellatrasmedio'];
$cabedorsalm=$val_r['cabedorsalm'];
$cabealtaizqu=$val_r['cabealtaizqu'];
$cabezaaltader=$val_r['cabezaaltader'];

$nuevo=$val_r['nuevo'];
$nuevo1=$val_r['nuevo1'];
$nuevo2=$val_r['nuevo2'];
$nuevo3=$val_r['nuevo3'];
$nuevo4=$val_r['nuevo4'];
$nuevo5=$val_r['nuevo5'];
$nuevo6=$val_r['nuevo6'];
$nuevo7=$val_r['nuevo7'];

$espizq=$val_r['espizq'];
$espder=$val_r['espder'];
$coxis=$val_r['coxis'];
}











$pdf->Ln(3);

$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 6, utf8_decode('VALORACIÓN NEUROLÓGICA'), 0, 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode('Variable'), 1, 0, 'C');
$pdf->Cell(95, 5, utf8_decode('Respuesta'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('Valor'), 1, 0, 'C');
$pdf->Cell(25, 5, utf8_decode('Resultado'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Espontáneamente'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('4'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(25, 20, utf8_decode($apecular_mat), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode('Apertura ocular'), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('A una orden verbal'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Al dolor'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Sin respuesta'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('A una orden verbal obedece'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('6'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(25, 30, utf8_decode($respmot_mat), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Localiza el dolor'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('5'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode('Mejor respuesta motora'), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Retirada y flexión al dolor'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('4'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Flexión anormal (rigidez de descorticación)'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Extensión (rigidez de descerebración)'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('No responde'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('1'), 1, 0, 'C');


$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Orientado y conversando'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('5'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(25, 25, utf8_decode($respver_mat), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Desorientado y hablando'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('4'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode('Mejor respuesta verbal'), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Palabras inapropiadas'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('3'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Sonidos incomprensibles'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('2'), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',8);
$pdf->Cell(95, 5, utf8_decode('Ninguna respuesta'), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('1'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(55, 5, utf8_decode(''), 1, 0, 'C');
$pdf->Cell(95, 5, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Total:'), 1, 0, 'C');
$totgl=$respver_mat+$apecular_mat+$respmot_mat;
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(25, 5, utf8_decode($totgl), 1, 0, 'C');


if($espizq!=NULL || $espder!=NULL || $nuevo!=NULL ||$nuevo1!=NULL ||$nuevo2!=NULL ||$nuevo3!=NULL ||$nuevo4!=NULL ||$nuevo5!=NULL ||$nuevo6!=NULL ||$nuevo7!=NULL ||$frenteizquierda!=NULL ||$frentederecha!=NULL ||$narizc!=NULL ||$mejderecha!=NULL ||$marf!=NULL ||$mare!=NULL ||$mandiizqui!=NULL ||$mandiderr!=NULL ||$mandicentroo!=NULL ||$cvi!=NULL ||$mard!=NULL ||$homi!=NULL ||$hombrod!=NULL ||$pecti!=NULL ||$pectd!=NULL ||$peci!=NULL ||$marc!=NULL ||$brazci!=NULL ||$cconder!=NULL ||$brazi!=NULL ||$annbraz!=NULL ||$marg!=NULL ||$derbraz!=NULL ||$muñei!=NULL ||$muñecad!=NULL ||$palmai!=NULL ||$palmad!=NULL ||$ddi!=NULL ||$ddoderu!=NULL ||$ddidos!=NULL ||$dedodos!=NULL ||$dditres!=NULL ||$dedotres!=NULL ||$dedocuatro!=NULL ||$ddicuatro!=NULL ||$ddicinco!=NULL ||$dedocincoo!=NULL ||$iabdomen!=NULL ||$marb!=NULL ||$inglei!=NULL ||$mara!=NULL ||$musloi!=NULL ||$muslod!=NULL ||$rodd!=NULL ||$rodi!=NULL ||$tod!=NULL ||$toi!=NULL ||$pied!=NULL ||$pie!=NULL || $coxis!=NULL || $plantapiea!=NULL || $plantapieader!=NULL || $tobilloatd!=NULL ||$tobilloati!=NULL ||$ptiatras!=NULL ||$ptdatras!=NULL ||$pierespaldad!=NULL ||$pierespaldai!=NULL ||$musloatrasiz!=NULL ||$musloatrasder!=NULL ||$dorsalder!=NULL ||$dorsaliz!=NULL ||$munecaatrasiz!=NULL ||$munecaatrasder!=NULL ||$antebdesp!=NULL ||$antebiesp!=NULL ||$casicodoi!=NULL ||$casicododer!=NULL ||$brazaltder!=NULL ||$brazalti!=NULL ||$glutiz!=NULL ||$glutder!=NULL ||$cinturader!=NULL ||$cinturaiz!=NULL ||$marh!=NULL ||$costilliz!=NULL ||$costillder!=NULL ||$espaldaarribader!=NULL ||$espaldarribaiz!=NULL ||$espaldaalta!=NULL ||$cuellatrasb!=NULL ||$cuellatrasmedio!=NULL ||$cabedorsalm!=NULL ||$cabealtaizqu!=NULL || $cabezaaltader!=NULL){
    $pdf->Ln(11);
    $pdf->SetFont('Arial', 'B',8);
$pdf->Cell(190, 5, utf8_decode('VALORACIÓN DE LA PIEL INICIAL'), 0, 0, 'C');
$pdf->Ln(12);
$pdf->Cell(20,5, utf8_decode(''),0,0,'L');
$pdf->Cell(100,10, $pdf->Image('../../img/cuerpof.jpg', $pdf->GetX(), $pdf->GetY(),45),0);

//IMAGEN TRASERA IMAGEN TARSERA TRASERA TRASEA TRASERA TRASERA TRASERA TRASERA TRASERA TRASERA TRASERA TRASERA TRASEA
$pdf->Cell(15,5, utf8_decode(''),0,0,'L');
$pdf->Cell(100,10, $pdf->Image('../../img/cuerpot.jpg', $pdf->GetX(), $pdf->GetY(),41),0);
//$pdf->Image('../../img/cuerpof.jpg' , 79, 103, 56);
if($espizq!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(205);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($espizq), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 209, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(183);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(205);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($espder), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 209, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(183);
$pdf->SetX(101);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo1!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(173);
$pdf->SetX(56);
$pdf->Cell(25, 6, utf8_decode($nuevo1), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 57, 176.6, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(99);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($nuevo2!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(173);
$pdf->SetX(19);
$pdf->Cell(25, 6, utf8_decode($nuevo2), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 20, 176.7, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(89);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo3!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(167.7);
$pdf->SetX(10);
$pdf->Cell(25, 6, utf8_decode($nuevo3), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 171.5, 38, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(138);
$pdf->SetX(89);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo4!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(167.4);
$pdf->SetX(28.3);
$pdf->Cell(25, 6, utf8_decode($nuevo4), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 20, 170.8, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(135.5);
$pdf->SetX(94.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo5!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(167);
$pdf->SetX(56.5);
$pdf->Cell(25, 6, utf8_decode($nuevo5), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 57, 170.8, 37, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(138.5);
$pdf->SetX(99);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($nuevo6!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(164);
$pdf->SetX(56.2);
$pdf->Cell(25, 6, utf8_decode($nuevo6), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 57, 168, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(132);
$pdf->SetX(99);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($nuevo7!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(165);
$pdf->SetX(28);
$pdf->Cell(25, 6, utf8_decode($nuevo7), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 32, 169, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(130);
$pdf->SetX(95);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($nuevo8!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(164);
$pdf->SetX(23.5);
$pdf->Cell(25, 6, utf8_decode($nuevo8), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 28, 168, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(132);
$pdf->SetX(89);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($frenteizquierda!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(140);
$pdf->SetX(16);
$pdf->Cell(25, 6, utf8_decode($frenteizquierda), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 16.5, 144.5, 35, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(141.4);
$pdf->SetX(39);
//$pdf->Cell(25, 6, utf8_decode('x'), 0,0, 'C');
}

if($frentederecha!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(140);
$pdf->SetX(53);
$pdf->Cell(25, 6, utf8_decode($frentederecha), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 53.5, 144.5, 35, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(103.2);
$pdf->SetX(96);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($narizc!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(143);
$pdf->SetX(52);
$pdf->Cell(25, 6, utf8_decode($narizc), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 52.5, 147, 35, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(105.7);
$pdf->SetX(94.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mejderecha!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(145);
$pdf->SetX(54);
$pdf->Cell(25, 6, utf8_decode($mejderecha), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 54.5, 148.8, 33.5, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(108.4);
$pdf->SetX(97.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marf!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(144);
$pdf->SetX(16);
$pdf->Cell(25, 6, utf8_decode($marf), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 16, 148, 35, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(108.5);
$pdf->SetX(92.1);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mare!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(146.6);
$pdf->SetX(16);
$pdf->Cell(25, 6, utf8_decode($mare), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 16, 150.5, 37, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(110.4);
$pdf->SetX(94.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mandiizqui!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(148.3);
$pdf->SetX(16);
$pdf->Cell(25, 6, utf8_decode($mandiizqui), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 16, 151.9, 35.5, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(112.4);
$pdf->SetX(92.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mandiderr!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(147.5);
$pdf->SetX(53.5);
$pdf->Cell(25, 6, utf8_decode($mandiderr), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 54, 151.5, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(111.4);
$pdf->SetX(97.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mandicentroo!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(149.5);
$pdf->SetX(52);
$pdf->Cell(25, 6, utf8_decode($mandicentroo), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 52.5, 153.2, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(113.6);
$pdf->SetX(94.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cvi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(152.5);
$pdf->SetX(14.5);
$pdf->Cell(25, 6, utf8_decode($cvi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 15, 156.5, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(118.3);
$pdf->SetX(90.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mard!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(152.5);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($mard), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 56, 156.5, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(118.3);
$pdf->SetX(98.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($homi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(155);
$pdf->SetX(14.5);
$pdf->Cell(25, 6, utf8_decode($homi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 15, 159, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(122.1);
$pdf->SetX(81.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($hombrod!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(155);
$pdf->SetX(60.8);
$pdf->Cell(25, 6, utf8_decode($hombrod), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 61.5, 159, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(122.1);
$pdf->SetX(107.3);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($pecti!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(158);
$pdf->SetX(14.5);
$pdf->Cell(25, 6, utf8_decode($pecti), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 15, 162, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(125.1);
$pdf->SetX(89.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($pectd!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(158);
$pdf->SetX(57);
$pdf->Cell(25, 6, utf8_decode($pectd), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 56, 162, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(125.1);
$pdf->SetX(100.2);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($peci!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(161);
$pdf->SetX(10);
$pdf->Cell(25, 6, utf8_decode($peci), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 165.5, 41, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(128);
$pdf->SetX(92.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marc!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(161);
$pdf->SetX(70);
$pdf->Cell(25, 6, utf8_decode($marc), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 53.5, 165.5, 41, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(128);
$pdf->SetX(97.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazci!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(160.2);
$pdf->SetX(10);
$pdf->Cell(25, 6, utf8_decode($brazci), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 164.5, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(130);
$pdf->SetX(80.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cconder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(160.5);
$pdf->SetX(62.5);
$pdf->Cell(25, 6, utf8_decode($cconder), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 63, 164.5, 33, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(130);
$pdf->SetX(109.1);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(165);
$pdf->SetX(10);
$pdf->Cell(25, 6, utf8_decode($brazi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 169.5, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(133);
$pdf->SetX(80);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($annbraz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(165);
$pdf->SetX(70);
$pdf->Cell(25, 6, utf8_decode($annbraz), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 65, 169, 29, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(133);
$pdf->SetX(110);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marg!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(170.5);
$pdf->SetX(9.5);
$pdf->Cell(25, 6, utf8_decode($marg), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 174.5, 29, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142.1);
$pdf->SetX(76);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($derbraz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(170);
$pdf->SetX(55);
$pdf->Cell(25, 6, utf8_decode($derbraz), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 65, 174, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142.1);
$pdf->SetX(113.1);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($muñei!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(174.4);
$pdf->SetX(9.5);
$pdf->Cell(25, 6, utf8_decode($muñei), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 10, 178.5, 27, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(145.6);
$pdf->SetX(74.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($muñecad!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(174.4);
$pdf->SetX(68);
$pdf->Cell(25, 6, utf8_decode($muñecad), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 68.5, 178.5, 27, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(145.6);
$pdf->SetX(114.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($palmai!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(179.2);
$pdf->SetX(4);
$pdf->Cell(25, 6, utf8_decode($palmai), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 5, 183, 30.2, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(148);
$pdf->SetX(73.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($palmad!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(179.2);
$pdf->SetX(69.5);
$pdf->Cell(25, 6, utf8_decode($palmad), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 70.2, 183, 30.2, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(148);
$pdf->SetX(115.3);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(177.4);
$pdf->SetX(4.5);
$pdf->Cell(25, 6, utf8_decode($ddi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 5, 181.1, 27, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149.7);
$pdf->SetX(67.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddoderu!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(177.3);
$pdf->SetX(72.5);
$pdf->Cell(25, 6, utf8_decode($ddoderu), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 73.5, 181.1, 30.2, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149.7);
$pdf->SetX(121.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddidos!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(181);
$pdf->SetX(2);
$pdf->Cell(25, 6, utf8_decode($ddidos), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 2.5, 185.1, 30.2, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(153);
$pdf->SetX(69.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedodos!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(181.7);
$pdf->SetX(72);
$pdf->Cell(25, 6, utf8_decode($dedodos), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 72.8, 185.5, 19, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(153);
$pdf->SetX(119.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dditres!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(183.2);
$pdf->SetX(1.7);
$pdf->Cell(25, 6, utf8_decode($dditres), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 2.5, 186.9, 30.9, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155);
$pdf->SetX(70.9);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedotres!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(183.7);
$pdf->SetX(65.5);
$pdf->Cell(25, 6, utf8_decode($dedotres), 0,0, 'C');
$pdf->Image('../registro_clinico/linea.png', 72, 187.4, 19, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155);
$pdf->SetX(118.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedocuatro!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(183.7);
$pdf->SetX(54);
$pdf->Cell(25, 6, utf8_decode($dedocuatro), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 55.1, 187.3, 16, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(156.5);
$pdf->SetX(117.3);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddicuatro!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(183.7);
$pdf->SetX(34);
$pdf->Cell(25, 6, utf8_decode($ddicuatro), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 34.1, 187.3, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(156.5);
$pdf->SetX(72);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($ddicinco!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(181);
$pdf->SetX(35.5);
$pdf->Cell(25, 6, utf8_decode($ddicinco), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 36.1, 184.9, 20, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155.3);
$pdf->SetX(73.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dedocincoo!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(180.7);
$pdf->SetX(52);
$pdf->Cell(25, 6, utf8_decode($dedocincoo), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 53, 184.6, 16, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(155.3);
$pdf->SetX(115.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($iabdomen!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(171.9);
$pdf->SetX(24.8);
$pdf->Cell(25, 6, utf8_decode($iabdomen), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 25, 175.6, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(140.1);
$pdf->SetX(95);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marb!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(177);
$pdf->SetX(53.5);
$pdf->Cell(25, 6, utf8_decode($marb), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 54.5, 180.6, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149);
$pdf->SetX(97.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}


if($inglei!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(177);
$pdf->SetX(26.8);
$pdf->Cell(25, 6, utf8_decode($inglei), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 23, 180.6, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(149);
$pdf->SetX(93);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($mara!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(180);
$pdf->SetX(29);
$pdf->Cell(25, 6, utf8_decode($mara), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 25, 183.5, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(152.3);
$pdf->SetX(94.7);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($musloi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(189.5);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($musloi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 194, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(162.1);
$pdf->SetX(87.8);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($muslod!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(189);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($muslod), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 194, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(162.1);
$pdf->SetX(102.2);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($rodd!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(196);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($rodd), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 200, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(172.1);
$pdf->SetX(102.2);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($rodi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(196);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($rodi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 200, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(172.1);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($tod!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(212);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($tod), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 216, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(192.1);
$pdf->SetX(89);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($toi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(212);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($toi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 216, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(192.1);
$pdf->SetX(100);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pied!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(216);
$pdf->SetX(58);
$pdf->Cell(25, 6, utf8_decode($pied), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 58, 220, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(197);
$pdf->SetX(102);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pie!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(216);
$pdf->SetX(13);
$pdf->Cell(25, 6, utf8_decode($pie), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 13, 220, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(197);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

//$pdf->Ln(110);

//terminomarcaje frontal

//$pdf->Cell(189, 6, utf8_decode(''), 0,0, 'C');

//$pdf->Image('../../img/cuerpot.jpg' , 79, 42, 56);



if($coxis!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(176.3);
$pdf->SetX(135);
$pdf->Cell(25, 6, utf8_decode($coxis), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 135.6, 180, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(88);
$pdf->SetX(94);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

//marcaje trasero
if($plantapiea!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(215);
$pdf->SetX(128);
$pdf->Cell(25, 6, utf8_decode($plantapiea), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 129, 219, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(86.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($plantapieader!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(215);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($plantapieader), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 219, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(142);
$pdf->SetX(101);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($tobilloatd!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(210);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($tobilloatd), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 214, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(137);
$pdf->SetX(101);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($tobilloati!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(210);
$pdf->SetX(128);
$pdf->Cell(25, 6, utf8_decode($tobilloati), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 129, 214, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(137);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($ptiatras!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(203);
$pdf->SetX(128.5);
$pdf->Cell(25, 6, utf8_decode($ptiatras), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 129, 207, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(126);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($ptdatras!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(203);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($ptdatras), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 207, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(126);
$pdf->SetX(100.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pierespaldad!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(196);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($pierespaldad), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 200, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(114);
$pdf->SetX(100.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($pierespaldai!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(196);
$pdf->SetX(129);
$pdf->Cell(25, 6, utf8_decode($pierespaldai), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 130, 200, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(114);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($musloatrasiz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(187);
$pdf->SetX(129);
$pdf->Cell(25, 6, utf8_decode($musloatrasiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 130, 191, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(107);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($musloatrasder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(187);
$pdf->SetX(176);
$pdf->Cell(25, 6, utf8_decode($musloatrasder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 170, 191, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(107);
$pdf->SetX(100.3);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
} 

if($dorsalder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(177.5);
$pdf->SetX(183.6);
$pdf->Cell(25, 6, utf8_decode($dorsalder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 181.5, 181.4, 26, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(91);
$pdf->SetX(117.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($dorsaliz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(177.7);
$pdf->SetX(117);
$pdf->Cell(25, 6, utf8_decode($dorsaliz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 118, 181.4, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(91);
$pdf->SetX(70);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($munecaatrasiz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(174.2);
$pdf->SetX(119);
$pdf->Cell(25, 6, utf8_decode($munecaatrasiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 119.6, 177.9, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(87);
$pdf->SetX(72);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($munecaatrasder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(174.2);
$pdf->SetX(183.5);
$pdf->Cell(25, 6, utf8_decode($munecaatrasder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 179.6, 177.9, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(87);
$pdf->SetX(115);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($antebdesp!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(171.4);
$pdf->SetX(183);
$pdf->Cell(25, 6, utf8_decode($antebdesp), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 179, 175, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(83);
$pdf->SetX(113);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($antebiesp!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(171);
$pdf->SetX(120);
$pdf->Cell(25, 6, utf8_decode($antebiesp), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 120, 175, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(83);
$pdf->SetX(74);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($casicodoi!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(167);
$pdf->SetX(120.5);
$pdf->Cell(25, 6, utf8_decode($casicodoi), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 121, 171.1, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(77);
$pdf->SetX(76);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($casicododer!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(166);
$pdf->SetX(182);
$pdf->Cell(25, 6, utf8_decode($casicododer), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 177, 170, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(77);
$pdf->SetX(111);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazaltder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(162);
$pdf->SetX(179.5);
$pdf->Cell(25, 6, utf8_decode($brazaltder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 176, 166.1, 28, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(71);
$pdf->SetX(110);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($brazalti!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(162);
$pdf->SetX(120);
$pdf->Cell(25, 6, utf8_decode($brazalti), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 120, 166, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(71);
$pdf->SetX(77);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($glutiz!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(179.6);
$pdf->SetX(130);
$pdf->Cell(25, 6, utf8_decode($glutiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 131, 183.3, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(94.5);
$pdf->SetX(90);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($glutder!=NULL){
$pdf->SetFont('Arial', 'B',6);
$pdf->SetY(179.6);
$pdf->SetX(174);
$pdf->Cell(25, 6, utf8_decode($glutder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 168, 183.3, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(94.5);
$pdf->SetX(97.2);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cinturader!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(169.4);
$pdf->SetX(174);
$pdf->Cell(25, 6, utf8_decode($cinturader), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 168, 173, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(84.5);
$pdf->SetX(96.6);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cinturaiz!=NULL){
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(169.4);
$pdf->SetX(131);
$pdf->Cell(25, 6, utf8_decode($cinturaiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 132, 173, 30, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(84.5);
$pdf->SetX(91);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($marh!=NULL){
  $pdf->SetFont('Arial', 'B',5);
$pdf->SetY(165);
$pdf->SetX(133.5);
$pdf->Cell(25, 6, utf8_decode($marh), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 134, 169, 31, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(80);
$pdf->SetX(94);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($costilliz!=NULL){
  $pdf->SetFont('Arial', 'B',5);
$pdf->SetY(164.2);
$pdf->SetX(127.7);
$pdf->Cell(25, 6, utf8_decode($costilliz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 128, 168.1, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(73);
$pdf->SetX(90);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($costillder!=NULL){
  $pdf->SetFont('Arial', 'B',5);
$pdf->SetY(164.2);
$pdf->SetX(177.7);
$pdf->Cell(25, 6, utf8_decode($costillder), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 168, 168.1, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(73);
$pdf->SetX(97);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espaldaarribader!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(157);
$pdf->SetX(177);
$pdf->Cell(25, 6, utf8_decode($espaldaarribader), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 168, 161, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(64);
$pdf->SetX(100);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espaldarribaiz!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(156.9);
$pdf->SetX(126);
$pdf->Cell(25, 6, utf8_decode($espaldarribaiz), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 127, 161, 34, 0.1);

$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(64);
$pdf->SetX(88);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($espaldaalta!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(152);
$pdf->SetX(175);
$pdf->Cell(25, 6, utf8_decode($espaldaalta), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 165, 155.8, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(57);
$pdf->SetX(93.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cuellatrasb!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(148.5);
$pdf->SetX(175);
$pdf->Cell(25, 6, utf8_decode($cuellatrasb), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 165, 152.6, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(52);
$pdf->SetX(93.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cuellatrasmedio!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(146.4);
$pdf->SetX(175);
$pdf->Cell(25, 6, utf8_decode($cuellatrasmedio), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 165, 150.1, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(48.5);
$pdf->SetX(93.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cabedorsalm!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(144);
$pdf->SetX(175);
$pdf->Cell(25, 6, utf8_decode($cabedorsalm), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 165, 148, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(45.5);
$pdf->SetX(93.5);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cabealtaizqu!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(142.1);
$pdf->SetX(129);
$pdf->Cell(25, 6, utf8_decode($cabealtaizqu), 0,0, 'L');
$pdf->Image('../registro_clinico/linea.png', 129, 146, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(42.5);
$pdf->SetX(91);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
}

if($cabezaaltader!=NULL){
  $pdf->SetFont('Arial', 'B',6);
$pdf->SetY(142.1);
$pdf->SetX(177);
$pdf->Cell(25, 6, utf8_decode($cabezaaltader), 0,0, 'R');
$pdf->Image('../registro_clinico/linea.png', 167, 146, 34, 0.1);
$pdf->SetFont('Arial', 'B',5);
$pdf->SetY(42.5);
$pdf->SetX(96);
//$pdf->Cell(25, 6, utf8_decode('X'), 0,0, 'C');
  
}

$pdf->Ln(192); 
}else{
  $pdf->Ln(1);  
}

$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 6, utf8_decode('SOLUCIONES / AMINAS'), 0, 'C');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(118,5, utf8_decode('Solución / Amina'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('Volumen total'),1,0,'C');
$pdf->Cell(10,5, utf8_decode('Inicio'),1,0,'C');
$pdf->Cell(18,5, utf8_decode('ml/hrs'),1,0,'C');
$pdf->Cell(11,5, utf8_decode('Término'),1,0,'C');
$pdf->Cell(18,5, utf8_decode('Fecha término'),1,0,'C');

$cis = $conexion->query("select * from sol_enf where sol_fecha='$fechar' and id_atencion=$id_atencion AND tipo='HOSPITALIZACION' ORDER BY sol_fecha DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);
$pdf->Cell(118,5, utf8_decode($cis_s['sol']),1,0,'L');
$pdf->Cell(20,5, utf8_decode($cis_s['vol']),1,0,'L');
$pdf->Cell(10,5, utf8_decode($cis_s['hora_i']),1,0,'L');
$pdf->Cell(18,5, utf8_decode($cis_s['pvc']),1,0,'L');
$pdf->Cell(11,5, utf8_decode($cis_s['hora_t']),1,0,'L');
$date=date_create($cis_s['fecha_termino']);
$pdf->Cell(18,5, date_format($date,"d/m/Y"),1,0,'L');

}
$pdf->Ln(9);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 4, utf8_decode('CONTROL DE CATÉTERES'),0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(30, 4, utf8_decode('Dispositivo'), 1, 0, 'C');
$pdf->Cell(15, 4, utf8_decode('Tipo'), 1, 0, 'C');
$pdf->Cell(17, 4, utf8_decode('Instalado'), 1, 0, 'C');
$pdf->Cell(20, 4, utf8_decode('Instaló'), 1, 0, 'C');
$pdf->Cell(15, 4, utf8_decode('Dias inst'), 1, 0, 'C');
$pdf->Cell(17, 4, utf8_decode('Se cambió'), 1, 0, 'C');
$pdf->Cell(81, 4, utf8_decode('Observaciones'), 1, 0, 'C');
$pdf->Ln(4);

$sql_est = "SELECT DATEDIFF(fecha_cambio, fecha_inst) as dins FROM cate_enf_hosp where id_atencion = $id_atencion";
      $result_est = $conexion->query($sql_est);
      while ($row_est = $result_est->fetch_assoc()) {
           $dins = $row_est['dins'];
      }
$medica = $conexion->query("select * from cate_enf_hosp WHERE id_atencion=$id_atencion ORDER BY id_cateh DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
$fec_i=date_create($row_m['fecha_inst']);
$fec_i=date_format($fec_i,"d/m/Y");
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(30,4, $row_m['dispositivos'],1,0,'C');
$pdf->Cell(15,4, $row_m['tipo'],1,0,'C');
$pdf->Cell(17,4, $fec_i,1,0,'C');
$pdf->Cell(20,4, $row_m['instalo'],1,0,'C');
$pdf->Cell(15,4, $dins,1,0,'C');
$pdf->Cell(17,4, $row_m['fecha_cambio'],1,0,'C');
$pdf->Cell(81,4, $row_m['cultivo'],1,1,'L');
}

//caidas
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 4, utf8_decode('VALORACIÓN DE RIESGO DE CAIDAS ESCALA DE DOWNTON'), 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Variable'), 1, 0, 'C');
$pdf->Cell(95, 4, utf8_decode('Observación'), 1, 0, 'C');
$pdf->Cell(20, 4, utf8_decode('Calificación'), 1, 0, 'C');
$pdf->Cell(25, 4, utf8_decode('Resultado'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Caidas previas'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('No'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 8, utf8_decode($caidprev_mat), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Si'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Ninguno'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 28, utf8_decode($medcaidas_mat), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Tranquilizantes-Sedantes'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Diuréticos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Medicamentos'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Hipotensores (no diuréticos)'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Antiparksonianos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Antidepresivos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Otros medicamentos'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Ninguno'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 16, utf8_decode($defsens_mat), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Déficits sensoriales'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Alteraciones visuales'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Alteraciones auditivas'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Extremidades (ictus..)'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Estado mental'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Orientado'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 8, utf8_decode($edomental_mat), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Confuso'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Normal'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('0'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 16, utf8_decode($deambula_mat), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode('Deambulación'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Segura con ayuda'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Insegura con ayuda / sin ayuda'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode('Imposible'), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('1'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(55, 4, utf8_decode(''), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(95, 4, utf8_decode(''), 1, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('Total:'), 1, 0, 'C');
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(25, 4, utf8_decode($totalcaidas_mat), 1, 0, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('clasificación del riesgo'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
if($totalcaidas_mat>2){
  $pdf->Cell(110, 4, utf8_decode('Alto riesgo para caída'), 1, 0, 'L');
}else{
   $pdf->Cell(110, 4, utf8_decode('No hay riesgo para caída'), 1, 0, 'L');
}




$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(105, 4, utf8_decode('Intervenciones / recomendaciones para prevención de riesgo de caída'), 1, 0, 'C');
$pdf->SetFont('Arial', '',6);
$pdf->Cell(90, 4, utf8_decode($riesgcaida_mat), 1, 0, 'L');
$pdf->SetFont('Arial', 'B',7);
$pdf->Ln(4);
$pdf->Cell(195, 4, utf8_decode('Interpretación: Todos los pacientes con 3 o más puntos en esta calificación se consideran de alto riesgo para caida'), 1, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Nombre de enfermera (o) que valora'), 1, 0, 'C');
$pdf->SetFont('Arial', '',6);
$pdf->Cell(110, 4, utf8_decode($nomenfermera_mat), 1, 0, 'L');
//nortton tabla

$pdf->Ln(9);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(100, 4, utf8_decode('VALORACIÓN DE ULCERAS POR PRESIÓN NORTON'), 1, 0, 'C');
$pdf->Cell(95, 4, utf8_decode('RESULTADO'), 1, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);

//$pdf->Image('../../imagenes/escala_norton.jpg', 49, 163.5, 100);
$pdf->Cell(30, 4, utf8_decode(''), 0, 0, 'C');

$pdf->Cell(139,25, $pdf->Image('../../imagenes/escala_norton.jpg', $pdf->GetX(), $pdf->GetY(),65),0);

$pdf->SetFont('Arial', 'B',7);
$pdf->SetX(110);
$pdf->Cell(95, 8, utf8_decode($edofisico_mat), 1, 0, 'C');
$pdf->Ln(8);
$pdf->SetX(110);
$pdf->Cell(95, 8, utf8_decode($edomentalnor_mat), 1, 0, 'C');
$pdf->Ln(8);
$pdf->SetX(110);
$pdf->Cell(95, 8, utf8_decode($actividad_mat), 1, 0, 'C');
$pdf->Ln(8);
$pdf->SetX(110);
$pdf->Cell(95, 8, utf8_decode($movilidad_mat), 1, 0, 'C');
$pdf->Ln(8);
$pdf->SetX(110);
$pdf->Cell(95, 8, utf8_decode($incont_mat), 1, 0, 'C');
$pdf->Ln(8);
$pdf->SetX(110);
$pdf->Cell(95, 3, utf8_decode($totnorton_mat), 1, 0, 'C');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Clasificación del riesgo'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
if($totnorton_mat >14){
$pdf->Cell(110, 4, utf8_decode('Risgo minimo'), 1, 0, 'L');
}else if($totnorton_mat >11 && $totnorton_mat <15){
$pdf->Cell(110, 4, utf8_decode('Riesgo evidente'), 1, 0, 'L');
}else if($totnorton_mat >4 && $totnorton_mat <12){
$pdf->Cell(110, 4, utf8_decode('Muy alto riesgo'), 1, 0, 'L');
}else if($totnorton_mat <=4){
$pdf->Cell(110, 4, utf8_decode('No hay suficientes datos para dar una clasificación'), 1, 0, 'L');
}


$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Nombre de enfermera (o) que valora'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(110, 4, utf8_decode($nomenfnorton_mat), 1, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(85, 4, utf8_decode('Intervenciones de acuerdo al riesgo'), 1, 0, 'C');
$pdf->SetFont('Arial', '',7);
$pdf->Cell(110, 4, utf8_decode($acriesg_mat), 1, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 4, utf8_decode('Interpretación: 5-11 puntos: Muy alto riesgo | 12-14 puntos: Riesgo evidente | mayor de 14 puntos: Riesgo minimo'), 1, 0, 'L');






$pdf->Ln(5);
//$pdf->Ln(11);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 6, utf8_decode('MEDICAMENTOS'), 0, 'C');

$pdf->Cell(105,4, utf8_decode('Medicamentos'),1,0,'C');
$pdf->Cell(30,4, utf8_decode('Frecuencia'),1,0,'C');
$pdf->Cell(20,4, utf8_decode('Dósis'),1,0,'C');
$pdf->Cell(20,4, utf8_decode('Vía'),1,0,'C');
$pdf->Cell(20,4, utf8_decode('Hora'),1,0,'C');


$pdf->Ln(4);
$medica = $conexion->query("select * from medica_enf WHERE fecha_mat='$fechar' and id_atencion=$id_atencion and tipo='hospitalizacion' and neonato!='Si' ORDER BY id_med_reg DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
$hora_mat=$row_m['hora_mat'];
if($hora_mat=='8:00' || $hora_mat=='9:00' || $hora_mat=='10:00'|| $hora_mat=='11:00'|| $hora_mat=='12:00' || $hora_mat=='13:00'|| $hora_mat=='14:00'){
$turno="MATUTINO";
}else if ($hora_mat=='15:00' || $hora_mat=='16:00' || $hora_mat=='17:00'|| $hora_mat=='18:00'|| $hora_mat=='19:00' || $hora_mat=='20:00') {
  $turno="VESPERTINO";
}else if ($hora_mat=='21:00' || $hora_mat=='22:00' || $hora_mat=='23:00' || $hora_mat=='24:00'|| $hora_mat=='1:00'|| $hora_mat=='2:00' || $hora_mat=='3:00' || $hora_mat=='4:00' || $hora_mat=='5' || $hora_mat=='6:00' || $hora_mat=='7:00') {
    $turno="NOCTURNO";
}
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(105,4, utf8_decode($row_m['medicam_mat'].' '. $row_m['otro']),1,0,'C');
$pdf->Cell(30,4, $row_m['frec_mat'],1,0,'C');
$pdf->Cell(20,4, $row_m['dosis_mat'],1,0,'C');
$pdf->Cell(20,4, $row_m['via_mat'],1,0,'C');
$pdf->Cell(20,4, $row_m['hora_mat'],1,1,'C');

}

$pdf->Ln(5);
//$pdf->Ln(11);
$pdf->SetFont('Arial', 'B', 8);
$pdf->MultiCell(195, 6, utf8_decode('DIÁMETRO PUPILAR'), 0, 'C');
$pdf->Cell(45,4, utf8_decode('Hora'),1,0,'C');
$pdf->Cell(50,4, utf8_decode('Lado'),1,0,'C');
$pdf->Cell(50,4, utf8_decode('Tamaño'),1,0,'C');
$pdf->Cell(50,4, utf8_decode('Turno'),1,0,'C');

$pdf->Ln(4);
$medica = $conexion->query("select * from d_pupilar WHERE fecha_reporte='$fechar' and id_atencion=$id_atencion ORDER BY id_pupilar DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
$hora=$row_m['hora'];
if($hora=='8' || $hora=='9' || $hora=='10'|| $hora=='11'|| $hora=='12' || $hora=='13'|| $hora=='14'){
$turno="MATUTINO";
}else if ($hora=='15' || $hora=='16' || $hora=='17'|| $hora=='18'|| $hora=='19' || $hora=='20') {
  $turno="VESPERTINO";
}else if ($hora=='21' || $hora=='22' || $hora=='23' || $hora=='24'|| $hora=='1'|| $hora=='2' || $hora=='3' || $hora=='4' || $hora=='5' || $hora=='6' || $hora=='7') {
    $turno="NOCTURNO";
}
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(45,4, utf8_decode($row_m['hora']),1,0,'C');
$pdf->Cell(50,4, $row_m['lado'],1,0,'C');
$pdf->Cell(50,4, $row_m['tamano'],1,0,'C');
$pdf->Cell(50,4, $turno,1,1,'C');
}

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B',8);
$pdf->MultiCell(195, 6, utf8_decode('NOTAS DE ENFERMERÍA'), 0, 'C');

$id_usuam = " ";
$notaenf =" ";
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 5, utf8_decode('Nota de enfermería (Turno matutino)'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', '',8);
$valv = "SELECT * FROM nota_enf_hosp  WHERE turno='MATUTINO' and id_atencion=$id_atencion and fecha='$fechar' order by id_nota_enf ASC limit 6";
$val_rv = $conexion->query($valv);

  
while ($val_resv = $val_rv->fetch_assoc()) {
   
$notaenf=$val_resv['notaenf'];
$pdf->MultiCell(195, 4, utf8_decode($notaenf), 0,'J');
$id_usuam=$val_resv['id_usua'];
}

$id_usuav = " ";
$notaenf =" ";
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 5, utf8_decode('Nota de enfermería (Turno vespertino)'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', '',8);
$ve = "SELECT * FROM nota_enf_hosp WHERE turno='VESPERTINO' and id_atencion=$id_atencion and fecha='$fechar' order by id_nota_enf ASC limit 6";
$v = $conexion->query($ve);
while ($resv = $v->fetch_assoc()) {
$notaenf=$resv['notaenf'];
$pdf->MultiCell(195, 4, utf8_decode($notaenf), 0,'J');
$id_usuav=$resv['id_usua'];
}

$id_usuan = " ";
$notaenf =" ";
$pdf->Ln(1);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 5, utf8_decode('Nota de enfermería (Turno nocturno)'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial', '',8);
$noc = "SELECT * FROM nota_enf_hosp  WHERE turno='NOCTURNO' and id_atencion=$id_atencion and fecha='$fechar' order by id_nota_enf ASC limit 6";
$nocturn = $conexion->query($noc);
while ($res_nocturno = $nocturn->fetch_assoc()) {
$notaenf=$res_nocturno['notaenf'];
$pdf->MultiCell(195, 4, utf8_decode($notaenf), 0,'J');
$id_usuan=$res_nocturno['id_usua'];
}
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',8);
$pdf->Cell(195, 5, utf8_decode('Tipos de laboratorios que se tomaron:'), 0, 0, 'L');
$pdf->Ln(5);
$satl = $conexion->query("select tiposlabo from enf_reg_clin where fecha_mat='$fechar' AND id_atencion=$id_atencion ORDER by id_clinreg DESC") or die($conexion->error);
while ($sat_rl = $satl->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
    $tiposlabo=$sat_rl['tiposlabo'];
 $pdf->MultiCell(195, 4, utf8_decode($tiposlabo), 0,'J');
}



    $id_med = " ";
    $nom = " ";
    $app = " ";
    $apm = " ";
    $pre = " ";
    $ced_p = " ";
    $cargp = " ";
   $firmam ="";
   
   
   
    $sql_med_idm = "SELECT * FROM enf_reg_clin WHERE fecha_mat='$fechar' and turno='MATUTINO' AND id_atencion = $id_atencion  ORDER by id_clinreg ASC ";


    $result_med_idm = $conexion->query($sql_med_idm);

    while ($row_med_idm = $result_med_idm->fetch_assoc()) {
      $id_usuam = $row_med_idm['id_usua'];
      $id_reg_clin=$row_med_idm['id_clinreg'];  // TIENE QUE ENCONTRAR EL id_clinreg ya que si no encuentra no pasara NO SIRVE CON EL id_med
    }




/* validacion para que si encuentra la firma la ponga */
    if ($id_usuam != " ") {
      $sql_enfm = "SELECT * FROM reg_usuarios WHERE id_usua = $id_usuam";
    $result_enfm = $conexion->query($sql_enfm);

    while ($row_enf = $result_enfm->fetch_assoc()) {
      $nom = $row_enf['nombre'];
      $app = $row_enf['papell'];
      $apm = $row_enf['sapell'];
      $pre = $row_enf['pre'];
  $firmam = $row_enf['firma'];
      $ced_p = $row_enf['cedp'];
      $cargp = $row_enf['cargp'];
    }
      $pdf->SetY(-41);

      $pdf->SetFont('Arial', 'B', 6);
     $pdf->Image('../../imgfirma/' . $firmam, 20, 256, 13);
      $pdf->Ln(12);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm ), 0, 0, 'L');
      $pdf->Ln(2);
      $pdf->SetFont('Arial', 'B', 6);
      $pdf->SetX(10);
      //$pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(2);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería matutino'), 0, 0, 'L');
    }else{/* si no encuentra la firma los pone en vacio */
$firmam =null;
$nom = " ";
      $app =" ";
      $apm =" ";
      $pre =" ";
  
      $ced_p =" ";
      $cargp =" ";

$pdf->SetY(-41);

      $pdf->SetFont('Arial', 'B', 6);
     //$pdf->Image('../../imgfirma/' . $firma, 25, 240, 15);
      $pdf->Ln(12);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm ), 0, 0, 'L');
      $pdf->Ln(2);
      $pdf->SetFont('Arial', 'B', 6);
      $pdf->SetX(10);
     // $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(2);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería matutino'), 0, 0, 'L');
    }

   /* termina la validacion */ 

$firmav =" ";
//VESPERTINO FIRMA
      $sql_med_idv = "SELECT * FROM enf_reg_clin WHERE fecha_mat='$fechar' and turno='VESPERTINO' AND id_atencion = $id_atencion  ORDER by id_clinreg ASC ";

    $result_med_id = $conexion->query($sql_med_idv);

    while ($row_med_idv = $result_med_id->fetch_assoc()) {
      $id_usuav = $row_med_idv['id_usua'];
      $id_reg_clinv=$row_med_idv['id_clinreg'];
    }
    /* validacion para que si encuentra la firma la ponga */
    if ($id_usuav != " ") {
    $sql_medv = "SELECT * FROM reg_usuarios WHERE id_usua = $id_usuav";
    $result_medv = $conexion->query($sql_medv);

    while ($row_medv = $result_medv->fetch_assoc()) {
        $nom = $row_medv['nombre'];
        $app = $row_medv['papell'];
        $apm = $row_medv['sapell'];
        $pre = $row_medv['pre'];
        $firmav = $row_medv['firma'];
        $ced_p = $row_medv['cedp'];
        $cargp = $row_medv['cargp'];
}

  $pdf->SetY(-41);

      $pdf->SetFont('Arial', 'B', 6);
     $pdf->Image('../../imgfirma/' . $firmav, 95, 256, 13);
      $pdf->Ln(12);
      $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm), 0, 0, 'L');
      $pdf->Ln(2);
      $pdf->SetFont('Arial', 'B', 6);
      $pdf->SetX(80);
      //$pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(2);
          $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería vespertino'), 0, 0, 'L');
}else{
    
  $nom = " ";
      $app =" ";
      $apm =" ";
      $pre =" ";
$firmav= null;
      $ced_p =" ";
      $cargp =" ";

$pdf->SetY(-41);

      $pdf->SetFont('Arial', 'B', 6);
      //$pdf->Image('../../imgfirma/' . $firman, 98, 228, 15);
      $pdf->Ln(12);
      $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm), 0, 0, 'L');
      $pdf->Ln(2);
      $pdf->SetFont('Arial', 'B', 6);
       $pdf->SetX(80);
     // $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(2);
          $pdf->SetX(80);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería vespertino'), 0, 0, 'L');
}





//turno nocturno
$firman =" ";
      //nocturno FIRMA
      $sql_med_idn = "SELECT * FROM enf_reg_clin WHERE fecha_mat='$fechar' and turno='NOCTURNO' AND id_atencion = $id_atencion  ORDER by id_clinreg ASC";

    $result_med_idn = $conexion->query($sql_med_idn);

    while ($row_med_idn = $result_med_idn->fetch_assoc()) {
      $id_usuan = $row_med_idn['id_usua'];
      $id_reg_clinn=$row_med_idn['id_clinreg'];
    }


if ($id_usuan !=" ") {
      $sql_medn = "SELECT * FROM reg_usuarios WHERE id_usua = $id_usuan";
    $result_medn = $conexion->query($sql_medn);

    while ($row_medn = $result_medn->fetch_assoc()) {
      $nom = $row_medn['nombre'];
      $app = $row_medn['papell'];
      $apm = $row_medn['sapell'];
      $pre = $row_medn['pre'];
    $firman = $row_medn['firma'];
      $ced_p = $row_medn['cedp'];
$cargp = $row_medn['cargp'];
}

  $pdf->SetY(-41);

      $pdf->SetFont('Arial', 'B', 6);
 $pdf->Image('../../imgfirma/' . $firman, 165, 256, 13);
      $pdf->Ln(12);
      $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm), 0, 0, 'L');
      $pdf->Ln(2);
      $pdf->SetFont('Arial', 'B', 6);
       $pdf->SetX(150);
    //  $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(2);
          $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería nocturno'), 0, 0, 'L');
}else{
$firman =null;
   $nom = " ";
      $app =" ";
      $apm =" ";
      $pre =" ";
     
      $ced_p =" ";
      $cargp =" ";

$pdf->SetY(-41);

      $pdf->SetFont('Arial', 'B', 6);
     //$pdf->Image('../../imgfirma/' . $firman, 165, 240, 15);

      $pdf->Ln(12);
      $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode($pre . '. ' . $app . ' ' . $apm), 0, 0, 'L');
      $pdf->Ln(2);
      $pdf->SetFont('Arial', 'B', 6);
      $pdf->SetX(150);
     // $pdf->Cell(190, 4, utf8_decode($cargp . ' ' .'CÉD. PROF. ' . $ced_p), 0, 0, 'L');

      $pdf->Ln(2);
          $pdf->SetX(150);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería nocturno'), 0, 0, 'L');
}


 $pdf->Output(); 
}