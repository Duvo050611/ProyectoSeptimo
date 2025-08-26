<?php
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_med = @$_GET['id_med'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];
$fechar = @$_GET['fechar'];
$hor = @$_GET['hor'];



$sql_clin = "SELECT * FROM enf_reg_urg  where id_atencion = $id_atencion";
$result_clin = $conexion->query($sql_clin);
while ($row_clinreg = $result_clin->fetch_assoc()) {
  $id_enf_urg = $row_clinreg['id_enf_urg'];
}
if(isset($id_enf_urg)){
    $id_enf_urg = $id_enf_urg;
  
  }else{
    $id_enf_urg ='sin doc';
  }
if($id_enf_urg=="sin doc"){
  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "NO EXISTE REGISTRO CLINICO DE URGENCIAS PARA ESTE PACIENTE", 
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
    $id = @$_GET['id'];
    $id_med = @$_GET['id_med'];
    include '../../conexionbd.php';
$id_exp = @$_GET['id_exp'];
$fechar = @$_GET['fechar'];
$hor = @$_GET['hor'];
    $id_atencion = @$_GET['id_atencion'];;

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

    $this->Image("../../configuracion/admin/img2/".$bas, 9, 3, 46, 25);
    $this->Image("../../configuracion/admin/img3/".$f['img_cpdf'],57,3, 100, 25);
    $this->Image("../../configuracion/admin/img4/".$f['img_dpdf'], 159, 7, 50, 20);
}
    $this->SetFont('Arial', 'B', 15);
    $this->SetTextColor(43, 45, 127);
    
    $this->Ln(10);
    $this->SetDrawColor(43, 45, 180);
    
    $this->SetFont('Arial', '', 8);
    

   


    $this->Ln(6);
  $this->SetFont('Arial', 'B', 7);
    $this->SetTextColor(43, 45, 127);
        $this->Cell(160, 5, utf8_decode($Id_exp . '-' . $papell . ' ' . $sapell . ' ' . $nom_pac), 0, 'L');
       $sql_q = "SELECT * from enf_reg_urg where datet='$fechar' and hor='$hor' and id_atencion=$id_atencion";
$result_q = $conexion->query($sql_q); 
while ($row_q = $result_q->fetch_assoc()) {
  $enfur_fecha = $row_q['datet'];
    
} 

$date2 = date_create($enfur_fecha);
$this->Cell(80, 5, utf8_decode('Fecha de registro: '.date_format($date2, "d-m-Y")),0, 'L');
        
        $this->Ln(4);

  }
  function Footer()
  {
    $this->Ln(8);
    $this->SetY(-15);
    $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}' ), 0, 0, 'C');
    $this->Cell(0, 10, utf8_decode('CMSI-15.05'), 0, 1, 'R');
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
  $fechai = $row_ing['fecha'];
  $area= $row_ing['area'];
    $tipo_a= $row_ing['tipo_a'];
  

}

$sql_f = "SELECT datet FROM enf_reg_urg  where datet='$fechar' and hor='$hor' and id_atencion = $id_atencion";
$result_f = $conexion->query($sql_f);
while ($row_f = $result_f->fetch_assoc()) {
$enf_fecha = $row_f['enf_fecha'];

  

}

$sql_camas = "SELECT * FROM cat_camas  where id_atencion = $id_atencion";
$result_camas = $conexion->query($sql_camas);
while ($row_camas = $result_camas->fetch_assoc()) {

  $num_cama = $row_camas['num_cama'];
}

      $sql_est = "SELECT DATEDIFF(datet, '$fechai') as estancia FROM enf_reg_urg where id_atencion = $id_atencion and datet='$fechar'";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
         $estancia = $row_est['estancia'];
       
      }

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
    $pdf->SetTextColor(43, 45, 127);

  $pdf->SetDrawColor(43, 45, 180);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(55);
$pdf->Cell(106, 5, utf8_decode('REGISTRO CLÍNICO DE ENFERMERÍA DE OBSERVACIÓN'), 0, 0, 'C');

//date_default_timezone_set('America/Guatemala');
$fecha_quir = date("d/m/Y H:i a");
$pdf->SetFont('Arial', '', 6.6);
//$pdf->Cell(25, 5, utf8_decode('Fecha de impresión: '.$fecha_quir), 0, 1, 'L');
$pdf->Ln(2.5);
$pdf->SetFont('Arial', '', 8);

$date = date_create($fecha);
//$pdf->Cell(110, 5, utf8_decode('Fecha de ingreso al hospital: '.date_format($date, "d-m-Y H:i:s")),0, 'L');



$pdf->Ln(2.5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, 'Servicio: ', 0, 'L');  
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(120, 5, utf8_decode($tipo_a) , 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);
$date=date_create($fechai);
$pdf->Cell(28, 5, utf8_decode(' Fecha de Ingreso:'),0 , 0, 'L');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(32, 5, date_format($date,'d/m/Y H:i a'), 'B', 0, 'C');
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
$sql_sig ="select * from dat_hclinica WHERE Id_exp=$id_atencion ORDER by id_hc DESC LIMIT 1";
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
$pdf->Cell(25,3, utf8_decode('Grupo sanguineo: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(10,3, utf8_decode($tip_san),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17,3, utf8_decode('Habitación: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(10,3, utf8_decode($num_cama),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 3, 'Tiempo estancia: ', 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(12, 3, $estancia . ' dias', 'B', 'L');
$pdf->SetFont('Arial', 'B', 8);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25,3, utf8_decode('Estado de salud: '),0,'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(71,3, utf8_decode($edo_salud),'B','L');
$pdf->Ln(3);


$sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
  $result_aseg = $conexion->query($sql_aseg);
  while ($row_aseg = $result_aseg->fetch_assoc()) {
 $aseg= $row_aseg['aseg'];
}                      
$pdf->SetFont('Arial', 'B', 8);                                               
$pdf->Cell(20,5, utf8_decode('Aseguradora: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(60,5, utf8_decode($aseg),'B','L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15,5, utf8_decode('Alergias: '),0,'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(100,5, utf8_decode($alergias),'B','L');
$pdf->SetFont('Arial', 'B', 6);


$sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
  $result_aseg = $conexion->query($sql_aseg);
while ($row_aseg = $result_aseg->fetch_assoc()) {
 $aseg= $row_aseg['aseg'];
}                                                                     
$pdf->SetFont('Arial', 'B', 6);
$pdf->Ln(6.4);


$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(10, 6, utf8_decode('DIETA :'), 0, 'L');
$re = $conexion->query("select * from enf_reg_urg where id_atencion=$id_atencion and datet='$fechar' and hor='$hor'") or die($conexion->error);
while ($row_u = $re->fetch_assoc()) {
$tdieta=$row_u['tdieta'];

$apecular=$row_u['apecular'];
$rmotora=$row_u['rmotora'];
$rverbal=$row_u['rverbal'];
$totglas=$row_u['totglas'];
$hora_glas=$row_u['hora_glas'];
$sol_es=$row_u['sol_es'];
$sol_gab=$row_u['sol_gab'];

$tder=$row_u['tder'];
$tizq=$row_u['tizq'];
$sder=$row_u['sder'];
$sizq=$row_u['sizq'];

$hora_gp=$row_u['hora_gp'];
$glic=$row_u['glic'];

$mucosa=$row_u['mucosa'];
$dientes=$row_u['dientes'];
$cabeza=$row_u['cabeza'];
$orejas=$row_u['orejas'];
$ojos=$row_u['ojos'];
$higiene=$row_u['higiene'];
$col=$row_u['col'];



$obse=$row_u['obse'];

$datag=$row_u['datag'];
$dati=$row_u['dati'];
$ex=$row_u['ex'];
$cas=$row_u['cas'];
$rep=$row_u['rep'];
$penc=$row_u['penc'];
$agre=$row_u['agre'];
$origen=$row_u['origen'];
$aluc=$row_u['aluc'];
$tip=$row_u['tip'];
$idea=$row_u['idea'];
$ideah=$row_u['ideah'];
$edoan=$row_u['edoan'];
$com=$row_u['com'];
$res=$row_u['res'];
$disvo=$row_u['disvo'];
$dtipo=$row_u['dtipo'];


$peri=$row_u['peri'];
$evac=$row_u['evac'];
$abd=$row_u['abd'];
$frecu=$row_u['frecu'];
$ulte=$row_u['ulte'];
$ultev=$row_u['ultev'];
$uri=$row_u['uri'];

$tipou=$row_u['tipou'];
$no=$row_u['no'];
$inst=$row_u['inst'];

$eseva=$row_u['eseva'];
$notenf=$row_u['notenf'];

$solp=$row_u['solp'];
$voral=$row_u['voral'];
$totingreso=$row_u['totingreso'];

$vomi=$row_u['vomi'];
$orina=$row_u['orina'];
$evc=$row_u['evc'];
$ot=$row_u['ot'];
$totegreso=$row_u['totegreso'];
$balance=$row_u['balance'];

$tra=$row_u['tra'];
$hor=$row_u['hor'];
$pdi=$row_u['pdi'];
$psi=$row_u['psi'];
$fcu=$row_u['fcu'];
$spoo=$row_u['spoo'];
$edocia=$row_u['edocia'];
$reci=$row_u['reci'];
$entrega=$row_u['entrega'];
$frere=$row_u['frere'];
$tempera=$row_u['tempera'];
$h_p=$row_u['h_p'];
$h_pd=$row_u['h_pd'];


$hora_estoma=$row_u['hora_estoma'];
$estoma=$row_u['estoma'];

//MARCAJE
$mara=$row_u['mara'];
$marb=$row_u['marb'];
$marc=$row_u['marc'];
$mard=$row_u['mard'];
$mare=$row_u['mare'];
$marf=$row_u['marf'];
$marg=$row_u['marg'];
$marh=$row_u['marh'];

$frenteizquierda=$row_u['frenteizquierda'];
$frentederecha=$row_u['frentederecha'];
$narizc=$row_u['narizc'];
$mejderecha=$row_u['mejderecha'];
$mandiizqui=$row_u['mandiizqui'];
$mandiderr=$row_u['mandiderr'];
$mandicentroo=$row_u['mandicentroo'];
$cvi=$row_u['cvi'];
$homi=$row_u['homi'];
$hombrod=$row_u['hombrod'];
$pecti=$row_u['pecti'];
$pectd=$row_u['pectd'];
$peci=$row_u['peci'];
$brazci=$row_u['brazci'];
$cconder=$row_u['cconder'];
$brazi=$row_u['brazi'];
$annbraz=$row_u['annbraz'];
$derbraz=$row_u['derbraz'];
$muñei=$row_u['muñei'];
$muñecad=$row_u['muñecad'];
$palmai=$row_u['palmai'];
$palmad=$row_u['palmad'];
$ddi=$row_u['ddi'];
$ddoderu=$row_u['ddoderu'];
$ddidos=$row_u['ddidos'];
$dedodos=$row_u['dedodos'];
$dditres=$row_u['dditres'];
$dedotres=$row_u['dedotres'];
$dedocuatro=$row_u['dedocuatro'];
$ddicuatro=$row_u['ddicuatro'];
$ddicinco=$row_u['ddicinco'];
$dedocincoo=$row_u['dedocincoo'];
$iabdomen=$row_u['iabdomen'];
$inglei=$row_u['inglei'];
$musloi=$row_u['musloi'];
$muslod=$row_u['muslod'];
$rodd=$row_u['rodd'];
$rodi=$row_u['rodi'];
$tod=$row_u['tod'];
$toi=$row_u['toi'];
$pied=$row_u['pied'];
$pie=$row_u['pie'];
$plantapiea=$row_u['plantapiea'];
$plantapieader=$row_u['plantapieader'];
$tobilloatd=$row_u['tobilloatd'];
$tobilloati=$row_u['tobilloati'];
$ptiatras=$row_u['ptiatras'];
$ptdatras=$row_u['ptdatras'];
$pierespaldad=$row_u['pierespaldad'];
$pierespaldai=$row_u['pierespaldai'];
$musloatrasiz=$row_u['musloatrasiz'];
$musloatrasder=$row_u['musloatrasder'];
$dorsaliz=$row_u['dorsaliz'];
$dorsalder=$row_u['dorsalder'];
$munecaatrasiz=$row_u['munecaatrasiz'];
$munecaatrasder=$row_u['munecaatrasder'];
$antebdesp=$row_u['antebdesp'];
$antebiesp=$row_u['antebiesp'];
$casicodoi=$row_u['casicodoi'];
$casicododer=$row_u['casicododer'];
$brazaltder=$row_u['brazaltder'];
$brazalti=$row_u['brazalti'];
$glutiz=$row_u['glutiz'];
$glutder=$row_u['glutder'];
$cinturader=$row_u['cinturader'];
$cinturaiz=$row_u['cinturaiz'];
$costilliz=$row_u['costilliz'];
$costillder=$row_u['costillder'];
$espaldaarribader=$row_u['espaldaarribader'];
$espaldarribaiz=$row_u['espaldarribaiz'];
$espaldaalta=$row_u['espaldaalta'];
$cuellatrasb=$row_u['cuellatrasb'];
$cuellatrasmedio=$row_u['cuellatrasmedio'];
$cabedorsalm=$row_u['cabedorsalm'];
$cabealtaizqu=$row_u['cabealtaizqu'];
$cabezaaltader=$row_u['cabezaaltader'];


$espizq=$row_u['espizq'];
$espder=$row_u['espder'];
$coxis=$row_u['coxis'];


$nuevo=$row_u['nuevo'];
$nuevo1=$row_u['nuevo1'];
$nuevo2=$row_u['nuevo2'];
$nuevo3=$row_u['nuevo3'];
$nuevo4=$row_u['nuevo4'];
$nuevo5=$row_u['nuevo5'];
$nuevo6=$row_u['nuevo6'];
$nuevo7=$row_u['nuevo7'];
}

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(185, 5, utf8_decode($tdieta),'B', 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(39, 6, utf8_decode('ESTUDIOS DE LABORATORIO :'), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(156, 5, utf8_decode($sol_es), 'B', 'L');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(33, 6, utf8_decode('ESTUDIOS DE GABINETE :'), 0, 'L');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(162, 5, utf8_decode($sol_gab), 'B', 'L');

$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(185, 6, utf8_decode('ESCALA DE COMA DE GLASGOW'), 0,1, 'C');
$pdf->Cell(32, 4, utf8_decode(''), 0,0, 'C');
$pdf->Cell(40, 4, utf8_decode('Ocular'), 1,0, 'C');
$pdf->Cell(40, 4, utf8_decode('Verbal'), 1,0, 'C');
$pdf->Cell(40, 4, utf8_decode('Motora'), 1,0, 'C');
$pdf->Ln(29);

$pdf->Image('../../imagenes/glas.png', 41.9, 99, 120.4);

//HORA GLAS


$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(195, 6, utf8_decode(''), 0, 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(30,5, utf8_decode('Fecha reporte'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('Hora'),1,0,'C');
$pdf->Cell(60,5, utf8_decode('Apertura ocular'),1,0,'C');
$pdf->Cell(34,5, utf8_decode('Verbal'),1,0,'C');
$pdf->Cell(34,5, utf8_decode('Motora'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('Total'),1,0,'C');

$cis = $conexion->query("select * from glasgow_o where id_atencion=$id_atencion ORDER BY id_gla DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);
$date=date_create($cis_s['fecha_reporte']);
$pdf->Cell(30,5, date_format($date,"d/m/Y"),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['hora_glas']),1,0,'C');
$pdf->Cell(60,5, utf8_decode($cis_s['apecular']),1,0,'C');
$pdf->Cell(34,5, utf8_decode($cis_s['rverbal']),1,0,'C');
$pdf->Cell(34,5, utf8_decode($cis_s['rmotora']),1,0,'C');

$pdf->Cell(20,5, utf8_decode($cis_s['apecular']+$cis_s['rverbal']+$cis_s['rmotora']),1,0,'C');
}
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell(195, 6, utf8_decode('MEDICAMENTOS APLICADOS'), 0, 'C');

$pdf->Cell(108,4, utf8_decode('Medicamentos'),1,0,'C');
$pdf->Cell(40,4, utf8_decode('Otros'),1,0,'C');
$pdf->Cell(15,4, utf8_decode('Dósis'),1,0,'C');
$pdf->Cell(25,4, utf8_decode('Vía'),1,0,'C');
$pdf->Cell(10,4, utf8_decode('Hora'),1,0,'C');
$pdf->Ln(4);
$medica = $conexion->query("select * from medica_enf WHERE fecha_mat='$fechar' and id_atencion=$id_atencion and 
(tipo='OBSERVACIÓN' || tipo='OBSERVACION') AND material !='Si' ORDER BY id_med_reg DESC") or die($conexion->error);
while ($row_m = $medica->fetch_assoc()) {
$hora_mat=$row_m['hora_mat'];


$hora_med = strval($hora_mat);

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(108,4, utf8_decode($row_m['medicam_mat']),1,0,'C');
$pdf->Cell(40,4, utf8_decode($row_m['cantidad'].' - '.$row_m['otro']),1,0,'C');
$pdf->Cell(15,4, $row_m['dosis_mat'],1,0,'C');
$pdf->Cell(25,4, $row_m['via_mat'],1,0,'C');
$pdf->Cell(10,4, $row_m['hora_mat'],1,1,'C');


}


$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(195, 6, utf8_decode('SOLUCIONES'), 0, 'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(18,5, utf8_decode('Hora inicio'),1,0,'C');
$pdf->Cell(18,5, utf8_decode('Hora término'),1,0,'C');
$pdf->Cell(60,5, utf8_decode('Soluciones'),1,0,'C');
$pdf->Cell(28,5, utf8_decode('Tipo de catéter'),1,0,'C');
$pdf->Cell(19,5, utf8_decode('Sitio'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('Volumen total'),1,0,'C');
$pdf->Cell(15,5, utf8_decode('Fecha'),1,0,'C');
$pdf->Cell(20,5, utf8_decode('Tipo'),1,0,'C');
$cis = $conexion->query("select * from sol_enf where id_atencion=$id_atencion AND (tipo='OBSERVACION' || tipo='OBSERVACIÓN') ORDER BY sol_fecha DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);

$pdf->Cell(18,5, utf8_decode($cis_s['hora_i']),1,0,'C');
$pdf->Cell(18,5, utf8_decode($cis_s['hora_t'].' ML'),1,0,'C');
$pdf->Cell(60,5, utf8_decode($cis_s['sol']),1,0,'C');
$pdf->Cell(28,5, utf8_decode($cis_s['tcate']),1,0,'C');
$pdf->Cell(19,5, utf8_decode($cis_s['sitio']),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['vol']),1,0,'C');
$date=date_create($cis_s['sol_fecha']);
$pdf->Cell(15,5, date_format($date,"d/m/Y"),1,0,'C');
$pdf->Cell(20,5, utf8_decode($cis_s['tipo']),1,0,'C');
}

$pdf->Ln(9);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 6, utf8_decode('PUPILAS'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(57,5, utf8_decode(),0,0,'C');
$pdf->Cell(189,25, $pdf->Image('../../imagenes/val_pupilar.jpg', $pdf->GetX(), $pdf->GetY(),80),0);
$pdf->Ln(11);
$pdf->Cell(49.5, 6, utf8_decode('Fecha reporte'), 1, 0, 'C');
$pdf->Cell(49.5, 6, utf8_decode('Hora'), 1, 0, 'C');
$pdf->Cell(49.5, 6, utf8_decode('Lado de ojo'), 1, 0, 'C');
$pdf->Cell(49.5, 6, utf8_decode('Tamaño'), 1, 0, 'C');
$pdf->Ln(1);
$cis = $conexion->query("select * from d_pupilar where id_atencion=$id_atencion AND obs='Si' ORDER BY id_pupilar DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);
$date=date_create($cis_s['fecha_reporte']);
$pdf->Cell(49.5,5, date_format($date,"d/m/Y"),1,0,'C');
$pdf->Cell(49.5,5, utf8_decode($cis_s['hora']),1,0,'C');
$pdf->Cell(49.5,5, utf8_decode($cis_s['lado'].' ML'),1,0,'C');
$pdf->Cell(49.5,5, utf8_decode($cis_s['tamano']),1,0,'C');

}


$pdf->Ln(8);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 6, utf8_decode('GLICEMIA CAPILAR'), 0,0, 'C');
$pdf->Ln(7);
$pdf->Cell(59.5, 6, utf8_decode('Fecha reporte'), 1,0, 'C');
$pdf->Cell(49.5, 6, utf8_decode('Hora'), 1,0, 'C');
$pdf->Cell(89.5, 6, utf8_decode('Valor'), 1,0, 'C');
$pdf->Ln(1);
$cis = $conexion->query("select * from glic_ca where id_atencion=$id_atencion ORDER BY id_glc DESC") or die($conexion->error);
while ($cis_s = $cis->fetch_assoc()) {
  $pdf->SetFont('Arial', '', 6);
$pdf->Ln(5);
$date=date_create($cis_s['fecha_reporte']);
$pdf->Cell(59.5,5, date_format($date,"d/m/Y"),1,0,'C');
$pdf->Cell(49.5,5, utf8_decode($cis_s['hora_g']),1,0,'C');
$pdf->Cell(89.5,5, utf8_decode($cis_s['valor'].' ML'),1,0,'C');


}

$pdf->Ln(90);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 6, utf8_decode('VALORACIÓN DE LA PIEL'), 0,0, 'C');

$pdf->Ln(7);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(25, 6, utf8_decode('Mucosa oral'), 0,0, 'L');
if($mucosa=="humeda"){
  $mucosa="X";
$pdf->Cell(5, 4.2, utf8_decode($mucosa), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Húmeda'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
}else if($mucosa=="seca"){
$mucosa="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Húmeda'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($mucosa), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
}

$pdf->Ln(5.5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(25, 6, utf8_decode('Dientes'), 0,0, 'L');
if($dientes=="limpios"){
  $dientes="X";
$pdf->Cell(5, 4.2, utf8_decode($dientes), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Limpios'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Sucios'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Caries'), 0,0, 'C');
}else if($dientes=="sucios"){
$dientes="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Limpios'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($dientes), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Sucios'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Caries'), 0,0, 'C');
}else if($dientes=="caries"){
$dientes="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Limpios'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Sucios'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($dientes), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Caries'), 0,0, 'C');
}

$pdf->Ln(5.5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(25, 6, utf8_decode('Cabeza'), 0,0, 'L');
if($cabeza=="limpia"){
  $cabeza="X";
$pdf->Cell(5, 4.2, utf8_decode($cabeza), 'B', 'R');
$pdf->Cell(10, 6, utf8_decode('Limpia'), 0,0, 'C');
$pdf->Cell(12, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Sucia'), 0,0, 'C');
}else if($cabeza=="sucia"){
$cabeza="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(10, 6, utf8_decode('Limpia'), 0,0, 'C');
$pdf->Cell(12, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($cabeza), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Sucia'), 0,0, 'C');
}

$pdf->Ln(5.5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(25, 6, utf8_decode('Orejas'), 0,0, 'L');
if($orejas=="limpias"){
  $orejas="X";
$pdf->Cell(5, 4.2, utf8_decode($orejas), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Limpias'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Sucias'), 0,0, 'C');
}else if($orejas=="sucias"){
$orejas="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Limpias'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($orejas), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Sucias'), 0,0, 'C');
}

//OJOS
$pdf->Ln(5.5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(25, 6, utf8_decode('Ojos'), 0,0, 'L');
if($ojos=="enrojecidos"){
  $ojos="X";
$pdf->Cell(5, 4.2, utf8_decode($ojos), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Enrojecidos'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Lagrimeo'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Secreción'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(22, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($ojos=="lagrimeo"){
$ojos="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Enrojecidos'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($ojos), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Lagrimeo'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Secreción'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(22, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($ojos=="secrecion"){
$ojos="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Enrojecidos'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Lagrimeo'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($ojos), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Secreción'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(22, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($ojos=="sin alteracion"){
$ojos="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Enrojecidos'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Lagrimeo'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Secreción'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($ojos), 'B', 'C');
$pdf->Cell(22, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}
//ojost
$pdf->Ln(5.5);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 6, utf8_decode('Piel'), 0,0, 'L');

//HIGIENE
$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(25, 6, utf8_decode('Higene'), 0,0, 'L');
if($higiene=="limpia"){
  $higiene="X";
$pdf->Cell(5, 4.2, utf8_decode($higiene), 'B', 'R');
$pdf->Cell(10, 6, utf8_decode('Limpia'), 0,0, 'C');
$pdf->Cell(12, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(9, 6, utf8_decode('Sucia'), 0,0, 'C');
$pdf->Cell(8.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Hidratada'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
}else if($higiene=="sucia"){
$higiene="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(10, 6, utf8_decode('Limpia'), 0,0, 'C');
$pdf->Cell(12, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($higiene), 'B', 'C');
$pdf->Cell(9, 6, utf8_decode('Sucia'), 0,0, 'C');
$pdf->Cell(8.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Hidratada'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
}else if($higiene=="hidratada"){
$higiene="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(10, 6, utf8_decode('Limpia'), 0,0, 'C');
$pdf->Cell(12, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(9, 6, utf8_decode('Sucia'), 0,0, 'C');
$pdf->Cell(8.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($higiene), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Hidratada'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
}else if($higiene=="seca"){
$higiene="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(10, 6, utf8_decode('Limpia'), 0,0, 'C');
$pdf->Cell(12, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(9, 6, utf8_decode('Sucia'), 0,0, 'C');
$pdf->Cell(8.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Hidratada'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($higiene), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
}
//HIGIENET

//coloracion
$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(25, 6, utf8_decode('Coloración'), 0,0, 'L');
if($col=="normal"){
  $col="X";
$pdf->Cell(5, 4.2, utf8_decode($col), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Normal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Pálida'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Ictericia'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Cianótica'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Marmórea'), 0,0, 'C');
}else if($col=="palida"){
$col="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Normal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($col), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Pálida'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Ictericia'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Cianótica'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Marmórea'), 0,0, 'C');
}else if($col=="ictericia"){
$col="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Normal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Pálida'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($col), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Ictericia'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Cianótica'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Marmórea'), 0,0, 'C');
}else if($col=="cianotica"){
$col="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Normal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Pálida'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Ictericia'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($col), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Cianótica'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Marmórea'), 0,0, 'C');
}else if($col=="marmorea"){
$col="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Normal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Pálida'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Ictericia'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Cianótica'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($col), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Marmórea'), 0,0, 'C');
}
//coloracipn


if($espizq!=NULL || $espder!=NULL || $nuevo!=NULL ||$nuevo1!=NULL ||$nuevo2!=NULL ||$nuevo3!=NULL ||$nuevo4!=NULL ||$nuevo5!=NULL ||$nuevo6!=NULL ||$nuevo7!=NULL ||$frenteizquierda!=NULL ||$frentederecha!=NULL ||$narizc!=NULL ||$mejderecha!=NULL ||$marf!=NULL ||$mare!=NULL ||$mandiizqui!=NULL ||$mandiderr!=NULL ||$mandicentroo!=NULL ||$cvi!=NULL ||$mard!=NULL ||$homi!=NULL ||$hombrod!=NULL ||$pecti!=NULL ||$pectd!=NULL ||$peci!=NULL ||$marc!=NULL ||$brazci!=NULL ||$cconder!=NULL ||$brazi!=NULL ||$annbraz!=NULL ||$marg!=NULL ||$derbraz!=NULL ||$muñei!=NULL ||$muñecad!=NULL ||$palmai!=NULL ||$palmad!=NULL ||$ddi!=NULL ||$ddoderu!=NULL ||$ddidos!=NULL ||$dedodos!=NULL ||$dditres!=NULL ||$dedotres!=NULL ||$dedocuatro!=NULL ||$ddicuatro!=NULL ||$ddicinco!=NULL ||$dedocincoo!=NULL ||$iabdomen!=NULL ||$marb!=NULL ||$inglei!=NULL ||$mara!=NULL ||$musloi!=NULL ||$muslod!=NULL ||$rodd!=NULL ||$rodi!=NULL ||$tod!=NULL ||$toi!=NULL ||$pied!=NULL ||$pie!=NULL || $coxis!=NULL || $plantapiea!=NULL || $plantapieader!=NULL || $tobilloatd!=NULL ||$tobilloati!=NULL ||$ptiatras!=NULL ||$ptdatras!=NULL ||$pierespaldad!=NULL ||$pierespaldai!=NULL ||$musloatrasiz!=NULL ||$musloatrasder!=NULL ||$dorsalder!=NULL ||$dorsaliz!=NULL ||$munecaatrasiz!=NULL ||$munecaatrasder!=NULL ||$antebdesp!=NULL ||$antebiesp!=NULL ||$casicodoi!=NULL ||$casicododer!=NULL ||$brazaltder!=NULL ||$brazalti!=NULL ||$glutiz!=NULL ||$glutder!=NULL ||$cinturader!=NULL ||$cinturaiz!=NULL ||$marh!=NULL ||$costilliz!=NULL ||$costillder!=NULL ||$espaldaarribader!=NULL ||$espaldarribaiz!=NULL ||$espaldaalta!=NULL ||$cuellatrasb!=NULL ||$cuellatrasmedio!=NULL ||$cabedorsalm!=NULL ||$cabealtaizqu!=NULL || $cabezaaltader!=NULL){
    $pdf->Ln(52);
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

$pdf->Ln(152); 
}else{
  $pdf->Ln(-100);  
}


$pdf->Ln(135);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(198, 6, utf8_decode('OBSERVACIONES'), 1,0, 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', '',7);
$pdf->MultiCell(198, 6, utf8_decode($obse),1, 'J');
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 6, utf8_decode('EVALUACIÓN DE ABUSO / MALTRATO / AGRESIÓN'), 0,0, 'C');

$pdf->Ln(7);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(78, 6, utf8_decode('Datos de agresión física'), 0,0, 'L');

if($datag=="SI"){
  $datag="X";
$pdf->Cell(5, 4.2, utf8_decode($datag), 'B', 'R');
$pdf->Cell(3, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}else if($datag=="NO"){
$datag="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(3, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($datag), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}


$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(78, 6, utf8_decode('Datos de temor o inquietud al acercamiento físico'), 0,0, 'L');
if($dati=="SI"){
  $dati="X";
$pdf->Cell(5, 4.2, utf8_decode($dati), 'B', 'R');
$pdf->Cell(3, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}else if($dati=="NO"){
$dati="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(3, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($dati), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}

$pdf->Ln(6);
$pdf->Cell(15, 6, utf8_decode('Explique'), 0,0, 'L');
$pdf->Cell(85, 5, utf8_decode($ex),'B', 'L');

$pdf->Ln(6);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(20, 6, utf8_decode('Caso legal'), 0,0, 'L');
if($cas=="SI"){
  $cas="X";
$pdf->Cell(5, 4.2, utf8_decode($cas), 'B', 'R');
$pdf->Cell(3, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
$pdf->Cell(10, 6, utf8_decode(''), 0,0, 'C');
}else if($cas=="NO"){
$cas="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(3, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($cas), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
$pdf->Cell(10, 6, utf8_decode(''), 0,0, 'C');
}

$pdf->SetFont('Arial', '',7);
$pdf->Cell(27, 6, utf8_decode('Reportado al MP'), 0,0, 'L');
if($rep=="SI"){
  $rep="X";
$pdf->Cell(5, 4.2, utf8_decode($rep), 'B', 'R');
$pdf->Cell(3, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}else if($rep=="NO"){
$rep="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(3, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($rep), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}




$pdf->Ln(7);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 6, utf8_decode('VALORACIÓN PSICOLÓGICA'), 0,0, 'C');

//paciente se encuentra
$pdf->Ln(6);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(42, 6, utf8_decode('El pacientese encuentra'), 0,0, 'L');
if($penc=="calmado y relajado"){
  $penc="X";
$pdf->Cell(5, 4.2, utf8_decode($penc), 'B', 'R');
$pdf->Cell(27, 6, utf8_decode('Calmado/relajado'), 0,0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Cooperador'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(13, 6, utf8_decode('Ansioso'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Hostil'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Combativo'), 0,0, 'C');
}else if($penc=="cooperador"){
$penc="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(27, 6, utf8_decode('Calmado/relajado'), 0,0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($penc), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Cooperador'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(13, 6, utf8_decode('Ansioso'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Hostil'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Combativo'), 0,0, 'C');
}else if($penc=="ansioso"){
$penc="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(27, 6, utf8_decode('Calmado/relajado'), 0,0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Cooperador'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($penc), 'B', 'C');
$pdf->Cell(13, 6, utf8_decode('Ansioso'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Hostil'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Combativo'), 0,0, 'C');
}else if($penc=="hostil"){
$penc="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(27, 6, utf8_decode('Calmado/relajado'), 0,0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Cooperador'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(13, 6, utf8_decode('Ansioso'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($penc), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Hostil'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Combativo'), 0,0, 'C');
}else if($penc=="combativo"){
$penc="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(27, 6, utf8_decode('Calmado/relajado'), 0,0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Cooperador'), 0,0, 'C');
$pdf->Cell(7.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(13, 6, utf8_decode('Ansioso'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Hostil'), 0,0, 'C');
$pdf->Cell(5.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($penc), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Combativo'), 0,0, 'C');
}
//pac se ecuentra t


//agre
$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(42, 6, utf8_decode('Agresivo a'), 0,0, 'L');
if($agre=="si mismo"){
  $agre="X";
$pdf->Cell(5, 4.2, utf8_decode($agre), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Si mismo'), 0,0, 'C');
$pdf->Cell(19.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Terceros'), 0,0, 'C');
$pdf->Cell(11.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(25.5, 6, utf8_decode('Infraestructura'), 0,0, 'C');

}else if($agre=="terceros"){
$agre="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Si mismo'), 0,0, 'C');
$pdf->Cell(19.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($agre), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Terceros'), 0,0, 'C');
$pdf->Cell(11.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(25.5, 6, utf8_decode('Infraestructura'), 0,0, 'C');
}else if($agre=="infraestructura"){
$agre="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Si mismo'), 0,0, 'C');
$pdf->Cell(19.5, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Terceros'), 0,0, 'C');
$pdf->Cell(11.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($agre), 'B', 'C');
$pdf->Cell(25.5, 6, utf8_decode('Infraestructura'), 0,0, 'C');
}
//agre t

//origen
$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(42, 6, utf8_decode('Origen'), 0,0, 'L');
if($origen=="psiquiatrico"){
  $origen="X";
$pdf->Cell(5, 4.2, utf8_decode($origen), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Psiquiátrico'), 0,0, 'C');
$pdf->Cell(13.7, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Neurológico'), 0,0, 'C');
$pdf->Cell(6.4, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19.3, 6, utf8_decode('Toxicológico'), 0,0, 'C');

}else if($origen=="neurologico"){
$origen="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Psiquiátrico'), 0,0, 'C');
$pdf->Cell(13.7, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($origen), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Neurológico'), 0,0, 'C');
$pdf->Cell(6.4, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19.3, 6, utf8_decode('Toxicológico'), 0,0, 'C');

}else if($origen=="toxicologico"){
$origen="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Psiquiátrico'), 0,0, 'C');
$pdf->Cell(13.7, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Neurológico'), 0,0, 'C');
$pdf->Cell(6.4, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($origen), 'B', 'C');
$pdf->Cell(19.3, 6, utf8_decode('Toxicológico'), 0,0, 'C');
}
//origen t

//aluc
$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(42, 6, utf8_decode('Alucinaciones'), 0,0, 'L');
if($aluc=="SI"){
  $aluc="X";
$pdf->Cell(5, 4.2, utf8_decode($aluc), 'B', 'R');
$pdf->Cell(4, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(28.9, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');

}else if($aluc=="NO"){
$aluc="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(4, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(28.9, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($aluc), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}
//aluc t

//TIPO
$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(42, 6, utf8_decode('Tipo'), 0,0, 'L');
if($tip=="auditiva"){
  $tip="X";
$pdf->Cell(5, 4.2, utf8_decode($tip), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Auditiva'), 0,0, 'C');
$pdf->Cell(20, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Visual'), 0,0, 'C');
$pdf->Cell(16.3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(13, 6, utf8_decode('Táctiles'), 0,0, 'C');

}else if($tip=="visual"){
$tip="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Auditiva'), 0,0, 'C');
$pdf->Cell(20, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($tip), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Visual'), 0,0, 'C');
$pdf->Cell(16.3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(13, 6, utf8_decode('Táctiles'), 0,0, 'C');

}else if($tip=="tactiles"){
$tip="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Auditiva'), 0,0, 'C');
$pdf->Cell(20, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Visual'), 0,0, 'C');
$pdf->Cell(16.3, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($tip), 'B', 'C');
$pdf->Cell(13, 6, utf8_decode('Táctiles'), 0,0, 'C');
}
//TIPO t

//ideasc
$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(42, 6, utf8_decode('Ideas suicidas'), 0,0, 'L');
if($idea=="SI"){
  $idea="X";
$pdf->Cell(5, 4.2, utf8_decode($idea), 'B', 'R');
$pdf->Cell(4, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(28.9, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');

}else if($idea=="NO"){
$idea="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(4, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(28.9, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($idea), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}
//ideasc t

//ideasH
$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(42, 6, utf8_decode('Ideas homicidas'), 0,0, 'L');
if($ideah=="SI"){
  $ideah="X";
$pdf->Cell(5, 4.2, utf8_decode($ideah), 'B', 'R');
$pdf->Cell(4, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(28.9, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}else if($ideah=="NO"){
$ideah="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(4, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(28.9, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($ideah), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}
//ideasH t


//estado animo
$pdf->Ln(6);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(42, 6, utf8_decode('Estado de ánimo'), 0,0, 'L');
if($edoan=="facie triste"){
  $edoan="X";
$pdf->Cell(5, 4.2, utf8_decode($edoan), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Facie triste'), 0,0, 'C');
$pdf->Cell(14, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(23, 6, utf8_decode('Facie sonriente'), 0,0, 'C');
$pdf->Cell(4, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Facie enojado'), 0,0, 'C');
$pdf->Cell(6, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Inexpresivo'), 0,0, 'C');

}else if($edoan=="facie sonriente"){
$edoan="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Facie triste'), 0,0, 'C');
$pdf->Cell(14, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($edoan), 'B', 'C');
$pdf->Cell(23, 6, utf8_decode('Facie sonriente'), 0,0, 'C');
$pdf->Cell(4, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Facie enojado'), 0,0, 'C');
$pdf->Cell(6, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Inexpresivo'), 0,0, 'C');
}else if($edoan=="ansioso"){
$edoan="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Facie triste'), 0,0, 'C');
$pdf->Cell(14, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(23, 6, utf8_decode('Facie sonriente'), 0,0, 'C');
$pdf->Cell(4, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($edoan), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Facie enojado'), 0,0, 'C');
$pdf->Cell(6, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Inexpresivo'), 0,0, 'C');
}else if($edoan=="inexpresivo"){
$edoan="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(19, 6, utf8_decode('Facie triste'), 0,0, 'C');
$pdf->Cell(14, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(23, 6, utf8_decode('Facie sonriente'), 0,0, 'C');
$pdf->Cell(4, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Facie enojado'), 0,0, 'C');
$pdf->Cell(6, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($edoan), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Inexpresivo'), 0,0, 'C');
}
//edoanimo t
$pdf->Ln(5.5);
$pdf->Cell(21, 6, utf8_decode('Comentarios'), 0,0, 'L');
$pdf->MultiCell(157, 4.8, utf8_decode($com), 'B', 'J');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 6, utf8_decode('VALORACIÓN FUNCIONAL'), 0,0, 'C');

//respirat
$pdf->Ln(5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(30, 6, utf8_decode('Respiratoria'), 0,0, 'L');
if($res=="regular"){
  $res="X";
$pdf->Cell(5, 4.2, utf8_decode($res), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Regular'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Estortes'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Disnea'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Aleteo nasal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(27, 6, utf8_decode('Tiraje intercostal'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(30, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(6, 6, utf8_decode('Tos'), 0,0, 'C');
$pdf->Cell(17, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
$pdf->Cell(16.1, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Productiva'), 0,0, 'C');
$pdf->Cell(3.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(42, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}else if($res=="estortes"){
$res="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Regular'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($res), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Estortes'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Disnea'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Aleteo nasal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(27, 6, utf8_decode('Tiraje intercostal'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(30, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(6, 6, utf8_decode('Tos'), 0,0, 'C');
$pdf->Cell(17, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
$pdf->Cell(16.1, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Productiva'), 0,0, 'C');
$pdf->Cell(3.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(42, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}else if($res=="disnea,"){
$res="X";
$disnea="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Regular'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Estortes'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($disnea), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Disnea'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Aleteo nasal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(27, 6, utf8_decode('Tiraje intercostal'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(30, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(6, 6, utf8_decode('Tos'), 0,0, 'C');
$pdf->Cell(17, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
$pdf->Cell(16.1, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Productiva'), 0,0, 'C');
$pdf->Cell(3.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(42, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}else if($res=="aleteo nasal"){
$res="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Regular'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Estortes'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Disnea'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($res), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Aleteo nasal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(27, 6, utf8_decode('Tiraje intercostal'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(30, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(6, 6, utf8_decode('Tos'), 0,0, 'C');
$pdf->Cell(17, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
$pdf->Cell(16.1, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Productiva'), 0,0, 'C');
$pdf->Cell(3.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(42, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}else if($res=="tiraje intercostal"){
$res="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Regular'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Estortes'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Disnea'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Aleteo nasal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($res), 'B', 'C');
$pdf->Cell(27, 6, utf8_decode('Tiraje intercostal'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(30, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(6, 6, utf8_decode('Tos'), 0,0, 'C');
$pdf->Cell(17, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
$pdf->Cell(16.1, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Productiva'), 0,0, 'C');
$pdf->Cell(3.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(42, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}else if($res=="tos"){
$res="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Regular'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Estortes'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Disnea'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Aleteo nasal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(27, 6, utf8_decode('Tiraje intercostal'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(30, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(5, 4.2, utf8_decode($res), 'B', 'R');
$pdf->Cell(6, 6, utf8_decode('Tos'), 0,0, 'C');
$pdf->Cell(17, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
$pdf->Cell(16.1, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Productiva'), 0,0, 'C');
$pdf->Cell(3.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(42, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}else if($res=="seca"){
$res="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Regular'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Estortes'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Disnea'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Aleteo nasal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(27, 6, utf8_decode('Tiraje intercostal'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(30, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(6, 6, utf8_decode('Tos'), 0,0, 'C');
$pdf->Cell(17, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($res), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
$pdf->Cell(16.1, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Productiva'), 0,0, 'C');
$pdf->Cell(3.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(42, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}else if($res=="productiva"){
$res="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Regular'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Estortes'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Disnea'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Aleteo nasal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(27, 6, utf8_decode('Tiraje intercostal'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(30, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(6, 6, utf8_decode('Tos'), 0,0, 'C');
$pdf->Cell(17, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
$pdf->Cell(16.1, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($res), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Productiva'), 0,0, 'C');
$pdf->Cell(3.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(42, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}else if($res=="sin alteraciones observadas"){
$res="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(13, 6, utf8_decode('Regular'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Estortes'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode('Disnea'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(19, 6, utf8_decode('Aleteo nasal'), 0,0, 'C');
$pdf->Cell(10, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(27, 6, utf8_decode('Tiraje intercostal'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(30, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(6, 6, utf8_decode('Tos'), 0,0, 'C');
$pdf->Cell(17, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Seca'), 0,0, 'C');
$pdf->Cell(16.1, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(17, 6, utf8_decode('Productiva'), 0,0, 'C');
$pdf->Cell(3.2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($res), 'B', 'C');
$pdf->Cell(42, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}
//respiratoria t

//02
$pdf->Ln(5.5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(30, 6, utf8_decode('Dispositivo de O2'), 0,0, 'L');
if($disvo=="SI"){
  $disvo="X";
$pdf->Cell(5, 4.2, utf8_decode($disvo), 'B', 'R');
$pdf->Cell(4, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(19, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}else if($disvo=="NO"){
$disvo="X";
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'R');
$pdf->Cell(4, 6, utf8_decode('Si'), 0,0, 'C');
$pdf->Cell(19, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($disvo), 'B', 'C');
$pdf->Cell(5, 6, utf8_decode('No'), 0,0, 'C');
}
//02 t
$pdf->Cell(18, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(8, 6, utf8_decode('Tipo'), 0,0, 'L');
$pdf->MultiCell(85, 4.8, utf8_decode($dtipo), 'B', 'J');


$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 6, utf8_decode('ABDOMEN'), 0,0, 'C');
$pdf->Ln(5.5);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(18, 6, utf8_decode('Perístalsis'), 0,0, 'L');
$pdf->Cell(20, 4.8, utf8_decode($peri), 'B', 'J');
$pdf->Cell(9, 4.8, utf8_decode(''), 0,0, 'J');
$pdf->Cell(18, 6, utf8_decode('Evacuación'), 0,0, 'L');
$pdf->Cell(29, 4.8, utf8_decode($evac), 'B', 'J');

if($abd=="blandos"){
$abd="X";
$pdf->Cell(4, 6, utf8_decode(''), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($abd), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Blandos'), 0,0, 'C');
$pdf->Cell(2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode(''), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Presente'), 0,0, 'C');
}else if($abd=="presente"){
$abd="X";
$pdf->Cell(4, 6, utf8_decode(''), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Blandos'), 0,0, 'C');
$pdf->Cell(2, 4.2, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.2, utf8_decode($abd), 'B', 'C');
$pdf->Cell(14, 6, utf8_decode('Presente'), 0,0, 'C');
}
$pdf->Cell(2, 6, utf8_decode(''), 0,0, 'C');
$pdf->Cell(18, 6, utf8_decode('Frecuencia'), 0,0, 'L');
$pdf->Cell(24, 4.8, utf8_decode($frecu), 'B', 'J');

$pdf->Ln(5.8);
$pdf->Cell(28, 6, utf8_decode('última evacuación'), 0,0, 'L');
$pdf->Cell(19, 4.8, utf8_decode($ulte), 'B', 'J');


//duro

$pdf->SetFont('Arial', '',7);
$pdf->Cell(20, 6, utf8_decode('Abdomen'), 0,0, 'L');
if($ultev=="duro"){
$ultev="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(8, 6, utf8_decode('Duro'), 0,0, 'C');
$pdf->Cell(13, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Blando'), 0,0, 'C');
$pdf->Cell(7, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('distendido'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode($ultev), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(36, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');

}else if($ultev=="abd"){
$ultev="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(8, 6, utf8_decode('Duro'), 0,0, 'C');
$pdf->Cell(13, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($ultev), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Blando'), 0,0, 'C');
$pdf->Cell(7, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('distendido'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(36, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');


}else if($ultev=="distendido"){
$ultev="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(8, 6, utf8_decode('Duro'), 0,0, 'C');
$pdf->Cell(13, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Blando'), 0,0, 'C');
$pdf->Cell(7, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($ultev), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('distendido'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(36, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');


}else if($ultev=="sin alteraciones observadas"){
$ultev="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(8, 6, utf8_decode('Duro'), 0,0, 'C');
$pdf->Cell(13, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Blando'), 0,0, 'C');
$pdf->Cell(7, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('distendido'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($ultev), 'B', 'C');
$pdf->Cell(36, 6, utf8_decode('Sin alteraciones observadas'), 0,0, 'C');
}

$pdf->Ln(5);
$pdf->Cell(9, 6, utf8_decode('Hora:'), 0,0, 'L');
$pdf->Cell(36, 5, utf8_decode($hora_estoma), 'B', 'L');
$pdf->Cell(9, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(13, 6, utf8_decode('Estoma:'), 0,0, 'L');
$pdf->Cell(36, 5, utf8_decode($estoma), 'B', 'L');

$pdf->Ln(9);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(195, 6, utf8_decode('URINARIO'), 0,0, 'C');
$pdf->Ln(5);
//urinario
$pdf->SetFont('Arial', '',7);

if($uri=="incontinencia"){
  $uri="X";
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="tenesmo"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="poliuria"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="piuria"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="coliuria"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="hematuria"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="retencion"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="disuria"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="oliguria"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="poliquiuria"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="nicturia"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="nicturia"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="dialisis"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="hemodialisis"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="sonda vesical"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('Nicturia'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}else if($uri=="sin alteracion"){
$uri="X";
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(20, 6, utf8_decode('Incontinencia'), 0,0, 'C');
$pdf->Cell(2, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Tenesmo'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Poliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(8, 6, utf8_decode('Piuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Coliuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Hematuria'), 0,0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(15, 6, utf8_decode('Retención'), 0,0, 'C');
$pdf->Cell(6, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(11, 6, utf8_decode('Disuria'), 0,0, 'C');
$pdf->Ln(5);
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(12, 6, utf8_decode('Oliguria'), 0,0, 'C');
$pdf->Cell(10, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(16, 6, utf8_decode('Poliquiuria'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'R');
$pdf->Cell(12, 6, utf8_decode('NICTURIA'), 0,0, 'C');
$pdf->Cell(5.1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(10.5, 6, utf8_decode('Diálisis'), 0,0, 'C');
$pdf->Cell(2.5, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(21, 6, utf8_decode('Hemodiálisis'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode(''), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sonda vesical'), 0,0, 'C');
$pdf->Cell(1, 4.8, utf8_decode(''), 0, 'C');
$pdf->Cell(5, 4.8, utf8_decode($uri), 'B', 'C');
$pdf->Cell(20, 6, utf8_decode('Sin alteración'), 0,0, 'C');
}
//urinario t
$pdf->Ln(5.8);
$pdf->Cell(7.5, 6, utf8_decode('Tipo'), 0,0, 'L');
$pdf->Cell(42, 4.8, utf8_decode($tipou), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode(''), 0,0, 'L');

$pdf->Cell(6, 6, utf8_decode('No.'), 0,0, 'L');
$pdf->Cell(42, 4.8, utf8_decode($no), 'B', 'C');
$pdf->Cell(10, 6, utf8_decode(''), 0,0, 'L');
$pdf->Cell(18, 6, utf8_decode('Instalación'), 0,0, 'L');
$pdf->Cell(45, 4.8, utf8_decode($inst), 'B', 'C');

$pdf->Ln(9);


$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(2, 6, utf8_decode(''), 0,0, 'C');
$pdf->Cell(96, 2, utf8_decode('CONTROL DE LÍQUIDOS'), 0,0, 'C');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B',7);

$pdf->Cell(75, 4, utf8_decode('Ingresos'), 1,0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', '',7);

$pdf->Cell(37.5, 4, utf8_decode('Soluciones parenterales'), 1,0, 'L');
$pdf->Cell(37.5, 4, utf8_decode($solp), 1,0, 'C');
$pdf->Ln(4);

$pdf->Cell(37.5, 4, utf8_decode('Vía oral'), 1,0, 'L');
$pdf->Cell(37.5, 4, utf8_decode($voral), 1,0, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(37.5, 4, utf8_decode('Total'), 1,0, 'C');
$pdf->Cell(37.5, 4, utf8_decode($totingreso), 1,0, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(75, 4, utf8_decode('Egresos'), 1,0, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', '',7);
$pdf->Cell(37.5, 4, utf8_decode('Vómito'), 1,0, 'L');
$pdf->Cell(37.5, 4, utf8_decode($vomi), 1,0, 'C');
$pdf->Ln(4);
$pdf->Cell(37.5, 4, utf8_decode('Orina'), 1,0, 'L');
$pdf->Cell(37.5, 4, utf8_decode($orina), 1,0, 'C');
$pdf->Ln(4);
$pdf->Cell(37.5, 4, utf8_decode('Evacuación'), 1,0, 'L');
$pdf->Cell(37.5, 4, utf8_decode($evc), 1,0, 'C');
$pdf->Ln(4);
$pdf->Cell(37.5, 4, utf8_decode('Otros'), 1,0, 'L');
$pdf->Cell(37.5, 4, utf8_decode($ot), 1,0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(37.5, 4, utf8_decode('Total'), 1,0, 'C');
$pdf->Cell(37.5, 4, utf8_decode($totegreso), 1,0, 'C');
$pdf->Ln(4);
$pdf->Cell(37.5, 4, utf8_decode('Balance'), 1,0, 'C');
$pdf->Cell(37.5, 4, utf8_decode($balance), 1,0, 'C');


$pdf->Ln(35);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(40, 6, utf8_decode(), 0,0, 'C');
$pdf->Cell(100, 6, utf8_decode('VALORACIÓN DEL DOLOR - ESCALA VISUAL ANÁLOGA (EVA)'), 0,0, 'C');
$pdf->Ln(7);
$pdf->Cell(40, 6, utf8_decode(), 0,0, 'C');
$pdf->Cell(160,30, $pdf->Image('../../imagenes/ceroc.png', $pdf->GetX(), $pdf->GetY(),100),0);

$pdf->Ln(26);
$pdf->Cell(70,4, utf8_decode('Fecha'),1,0,'C');
$pdf->Cell(50,4, utf8_decode('Hora'),1,0,'C');
$pdf->Cell(78,4, utf8_decode('Valor'),1,0,'C');


$resp = $conexion->query("select * from eva WHERE id_atencion=$id_atencion ORDER BY id_eva DESC") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
$pdf->SetFont('Arial', '', 6);
$pdf->Ln(4);
$pdf->Cell(70,4, $resp_r['fecha_reporte'],1,0,'C');
$pdf->Cell(50,4, $resp_r['hora_eva'],1,0,'C');
$pdf->Cell(78,4, $resp_r['eseva'],1,0,'C');
}


$pdf->SetFont('Arial', 'B',7);
$pdf->Ln(7);
$pdf->Cell(198,4, utf8_decode('SIGNOS VITALES'),1,0,'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', '',7);
$pdf->Cell(31,4, utf8_decode('Hora'),1,0,'C');
$pdf->Cell(32,4, utf8_decode('Tensión arterial'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(32,4, utf8_decode('Frecuecia cardiaca'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(40,4, utf8_decode('Frecuencia respiratoria'),1,0,'C');
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(31,4, utf8_decode('Temperatura'),1,0,'C');
$pdf->Cell(32,4, utf8_decode('Saturación oxigeno'),1,0,'C');

$resp = $conexion->query("select * from signos_vitales WHERE id_atencion=$id_atencion and tipo='OBSERVACIÓN' ORDER BY fecha DESC") or die($conexion->error);
while ($resp_r = $resp->fetch_assoc()) {
$pdf->SetFont('Arial', '', 6);
$pdf->Ln(4);
$pdf->Cell(31,4, $resp_r['hora'],1,0,'C');
$pdf->Cell(32,4, $resp_r['p_sistol'] .' / '. $resp_r['p_diastol'],1,0,'C');
$pdf->Cell(32,4, $resp_r['fcard'],1,0,'C');
$pdf->Cell(40,4, $resp_r['fresp'],1,0,'C');
$pdf->Cell(31,4, $resp_r['temper'],1,0,'C');
$pdf->Cell(32,4, $resp_r['satoxi'],1,0,'C');
}


$pdf->Ln(6);
$pdf->SetFont('Arial', 'B',7);
$pdf->Cell(196, 4, utf8_decode('NOTA DE ENFERMERÍA'), 0,0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', '',6.9);
$pdf->MultiCell(198, 3.5, utf8_decode($notenf),1, 'J');

$pdf->Ln(2);
$pdf->Cell(60, 5, utf8_decode('Traslado a: ' . $tra), 1,0, 'L');
$pdf->Cell(33, 5, utf8_decode('Hora: ' . $hor), 1,0, 'L');
$pdf->Cell(105, 5, utf8_decode('Entrega: ' . $entrega), 1,0, 'L');
$pdf->Ln(5);
$pdf->Cell(38, 5, utf8_decode('Estado de salud: ' . $edocia), 1,0, 'L');

$pdf->Cell(160, 5, utf8_decode('Recibe: ' . $reci), 1,0, 'L');












//FIRMA
    $id_med = " ";
    $nom = " ";
    $app = " ";
    $apm = " ";
    $pre = " ";
    $ced_p = " ";
    $cargp = " ";
    $sql_med_id = "SELECT * FROM enf_reg_urg WHERE datet='$fechar' and hor='$hor' and id_atencion = $id_atencion  ORDER by id_enf_urg DESC LIMIT 1";
    $result_med_id = $conexion->query($sql_med_id);
    while ($row_med_id = $result_med_id->fetch_assoc()) {
      $id_med = $row_med_id['id_usua'];
      $id_enf_urg=$row_med_id['id_enf_urg'];
    }

/* validacion para que si encuentra la firma la ponga */
    if (isset($id_enf_urg)) {
      $sql_enf = "SELECT * FROM reg_usuarios WHERE id_usua = $id_med";
    $result_enf = $conexion->query($sql_enf);

    while ($row_enf = $result_enf->fetch_assoc()) {
      $nom = $row_enf['nombre'];
      $app = $row_enf['papell'];
      $apm = $row_enf['sapell'];
      $pre = $row_enf['pre'];
      $firma = $row_enf['firma'];
      $ced_p = $row_enf['cedp'];
      $cargp = $row_enf['cargp'];
    }
      $pdf->SetY(-43);

      $pdf->SetFont('Arial', 'B', 6);
      //$pdf->Image('../../imgfirma/' . $firma, 25, 244, 15);
    
      $pdf->Ln(10);
      $pdf->SetX(10);
      $pdf->Cell(190, 1, utf8_decode($pre . '. ' . $app), 0, 0, 'C');
      $pdf->Ln(1);
      $pdf->SetX(10);
      $pdf->Cell(190, 4, utf8_decode('Nombre y firma de enfermería'), 0, 0, 'C');
    }else{/* si no encuentra la firma los pone en vacio */

$nom = " ";
      $app =" ";
      $apm =" ";
      $pre =" ";
    //  $firma = $row_enf['firma'];
      $ced_p =" ";
      $cargp =" ";

$pdf->SetY(-43);

      $pdf->SetFont('Arial', 'B', 6);
    //  $pdf->Image('../../imgfirma/' . $firma, 25, 240, 15);
      
      $pdf->SetX(10);
      $pdf->Cell(190, 1, utf8_decode($pre . '. ' . $app), 0, 0, 'C');
      $pdf->Ln(1);
      $pdf->SetX(10);
      $pdf->Cell(190, 1, utf8_decode('Nombre y firma de enfermería'), 0, 0, 'C');
    }

   /* termina la validacion */ 
 if ($firma==null) {
 $pdf->Image('../../imgfirma/FIRMA.jpg', 94, 250 , 25);
} else {
    $pdf->Image('../../imgfirma/' . $firma, 94, 250 , 25);
}





 $pdf->Output(); 
}