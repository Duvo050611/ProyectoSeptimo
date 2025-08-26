<?php
session_start();
include '../../conexionbd.php';
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];

//date_default_timezone_set('America/Guatemala');

$hora_mat=$_POST['hora_mat'];


  $sql_d = "SELECT dieta FROM dat_ordenes_med WHERE id_atencion='$id_atencion' order by id_ord_med DESC limit 1";
                            $result_d = $conexion->query($sql_d);
                            while ($row_d = $result_d->fetch_assoc()) {
                              $tipodieta_mat=$row_d['dieta'];
                            }
                           

if(isset($_POST['quemadura_mat'])){$quemadura_mat=$_POST['quemadura_mat'];}else{ $quemadura_mat='';}
if(isset($_POST['heridap_mat'])){$heridap_mat=$_POST['heridap_mat'];}else{ $heridap_mat='';}
if(isset($_POST['enfisema_mat'])){$enfisema_mat=$_POST['enfisema_mat'];}else{ $enfisema_mat='';}
if(isset($_POST['ulcpre_mat'])){$ulcpre_mat=$_POST['ulcpre_mat'];}else{ $ulcpre_mat='';}
if(isset($_POST['dermoabra_mat'])){$dermoabra_mat=$_POST['dermoabra_mat'];}else{ $dermoabra_mat='';}
if(isset($_POST['hematoma_mat'])){$hematoma_mat=$_POST['hematoma_mat'];}else{ $hematoma_mat='';}
if(isset($_POST['ciano_mat'])){$ciano_mat=$_POST['ciano_mat'];}else{ $ciano_mat='';}
if(isset($_POST['rash_mat'])){$rash_mat=$_POST['rash_mat'];}else{ $rash_mat='';}
if(isset($_POST['fracturas_mat'])){$fracturas_mat=$_POST['fracturas_mat'];}else{ $fracturas_mat='';}
if(isset($_POST['herquir_mat'])){$herquir_mat=$_POST['herquir_mat'];}else{ $herquir_mat='';}
if(isset($_POST['equimosis_mat'])){$equimosis_mat=$_POST['equimosis_mat'];}else{ $equimosis_mat='';}
if(isset($_POST['funprev_mat'])){$funprev_mat=$_POST['funprev_mat'];}else{ $funprev_mat='';}

if(isset($_POST['fron_mat'])){$fron_mat=$_POST['fron_mat'];}else{ $fron_mat='';}
if(isset($_POST['malar_mat'])){$malar_mat=$_POST['malar_mat'];}else{ $malar_mat='';}
if(isset($_POST['mandi_mat'])){$mandi_mat=$_POST['mandi_mat'];}else{ $mandi_mat='';}
if(isset($_POST['delto_mat'])){$delto_mat=$_POST['delto_mat'];}else{ $delto_mat='';}
if(isset($_POST['pecto_mat'])){$pecto_mat=$_POST['pecto_mat'];}else{ $pecto_mat='';}
if(isset($_POST['esternal_mat'])){$esternal_mat=$_POST['esternal_mat'];}else{ $esternal_mat='';}
if(isset($_POST['antebrazo_mat'])){$antebrazo_mat=$_POST['antebrazo_mat'];}else{ $antebrazo_mat='';}
if(isset($_POST['muñeca_mat'])){$muñeca_mat=$_POST['muñeca_mat'];}else{ $muñeca_mat='';}
if(isset($_POST['manopal_mat'])){$manopal_mat=$_POST['manopal_mat'];}else{ $manopal_mat='';}
if(isset($_POST['muslo_mat'])){$muslo_mat=$_POST['muslo_mat'];}else{ $muslo_mat='';}
if(isset($_POST['rodilla_mat'])){$rodilla_mat=$_POST['rodilla_mat'];}else{ $rodilla_mat='';}

if(isset($_POST['pierna_mat'])){$pierna_mat=$_POST['pierna_mat'];}else{ $pierna_mat='';}
if(isset($_POST['pirnasal_mat'])){$pirnasal_mat=$_POST['pirnasal_mat'];}else{ $pirnasal_mat='';}
if(isset($_POST['maxsup_mat'])){$maxsup_mat=$_POST['maxsup_mat'];}else{ $maxsup_mat='';}
if(isset($_POST['menton_mat'])){$menton_mat=$_POST['menton_mat'];}else{ $menton_mat='';}
if(isset($_POST['acromial_mat'])){$acromial_mat=$_POST['acromial_mat'];}else{ $acromial_mat='';}
if(isset($_POST['brazo_mat'])){$brazo_mat=$_POST['brazo_mat'];}else{ $brazo_mat='';}
if(isset($_POST['plicodo_mat'])){$plicodo_mat=$_POST['plicodo_mat'];}else{ $plicodo_mat='';}
if(isset($_POST['abdomen_mat'])){$abdomen_mat=$_POST['abdomen_mat'];}else{ $abdomen_mat='';}
if(isset($_POST['regingui_mat'])){$regingui_mat=$_POST['regingui_mat'];}else{ $regingui_mat='';}
if(isset($_POST['regpub_mat'])){$regpub_mat=$_POST['regpub_mat'];}else{ $regpub_mat='';}
if(isset($_POST['pdedo_mat'])){$pdedo_mat=$_POST['pdedo_mat'];}else{ $pdedo_mat='';}
if(isset($_POST['sdedo_mat'])){$sdedo_mat=$_POST['sdedo_mat'];}else{ $sdedo_mat='';}
if(isset($_POST['tdedo_mat'])){$tdedo_mat=$_POST['tdedo_mat'];}else{ $tdedo_mat='';}
if(isset($_POST['cdedo_mat'])){$cdedo_mat=$_POST['cdedo_mat'];}else{ $cdedo_mat='';}
if(isset($_POST['qdedo_mat'])){$qdedo_mat=$_POST['qdedo_mat'];}else{ $qdedo_mat='';}
if(isset($_POST['tobi_mat'])){$tobi_mat=$_POST['tobi_mat'];}else{ $tobi_mat='';}
if(isset($_POST['piedor_mat'])){$piedor_mat=$_POST['piedor_mat'];}else{ $piedor_mat='';}
if(isset($_POST['parie_mat'])){$parie_mat=$_POST['parie_mat'];}else{ $parie_mat='';}

if(isset($_POST['occi_mat'])){$occi_mat=$_POST['occi_mat'];}else{ $occi_mat='';}
if(isset($_POST['nuca_mat'])){$nuca_mat=$_POST['nuca_mat'];}else{ $nuca_mat='';}
if(isset($_POST['brazo2_mat'])){$brazo2_mat=$_POST['brazo2_mat'];}else{ $brazo2_mat='';}
if(isset($_POST['codo2_mat'])){$codo2_mat=$_POST['codo2_mat'];}else{ $codo2_mat='';}
if(isset($_POST['antebrazo2_mat'])){$antebrazo2_mat=$_POST['antebrazo2_mat'];}else{ $antebrazo2_mat='';}
if(isset($_POST['muñeca2_mat'])){$muñeca2_mat=$_POST['muñeca2_mat'];}else{ $muñeca2_mat='';}
if(isset($_POST['manodor_mat'])){$manodor_mat=$_POST['manodor_mat'];}else{ $manodor_mat='';}
if(isset($_POST['plipop_mat'])){$plipop_mat=$_POST['plipop_mat'];}else{ $plipop_mat='';}
if(isset($_POST['pierna2_mat'])){$pierna2_mat=$_POST['pierna2_mat'];}else{ $pierna2_mat='';}
if(isset($_POST['pieplan_mat'])){$pieplan_mat=$_POST['pieplan_mat'];}else{ $pieplan_mat='';}
if(isset($_POST['cuellopos_mat'])){$cuellopos_mat=$_POST['cuellopos_mat'];}else{ $cuellopos_mat='';}
if(isset($_POST['reginter_mat'])){$reginter_mat=$_POST['reginter_mat'];}else{ $reginter_mat='';}
if(isset($_POST['regesca_mat'])){$regesca_mat=$_POST['regesca_mat'];}else{ $regesca_mat='';}
if(isset($_POST['reginfra_mat'])){$reginfra_mat=$_POST['reginfra_mat'];}else{ $reginfra_mat='';}
if(isset($_POST['lumbar_mat'])){$lumbar_mat=$_POST['lumbar_mat'];}else{ $lumbar_mat='';}
if(isset($_POST['gluteo_mat'])){$gluteo_mat=$_POST['gluteo_mat'];}else{ $gluteo_mat='';}
if(isset($_POST['muslo2_mat'])){$muslo2_mat=$_POST['muslo2_mat'];}else{ $muslo2_mat='';}
if(isset($_POST['talon2_mat'])){$talon2_mat=$_POST['talon2_mat'];}else{ $talon2_mat='';}

if(isset($_POST['ramsay_mat'])){$ramsay_mat=$_POST['ramsay_mat'];}else{ $ramsay_mat='';}

$apecular_mat=$_POST['apecular_mat'];
$respmot_mat=$_POST['respmot_mat'];
$respver_mat=$_POST['respver_mat'];


$totalcaidas_mat=0;
$totnorton_mat=0;

$caidprev_mat=$_POST['caidprev_mat'];
$medcaidas_mat=$_POST['medcaidas_mat'];
$defsens_mat=$_POST['defsens_mat'];
$edomental_mat=$_POST['edomental_mat'];
$deambula_mat=$_POST['deambula_mat'];
$totalcaidas_mat=$caidprev_mat+$medcaidas_mat+$defsens_mat+$edomental_mat+$deambula_mat;


//$clasriesg_mat=$_POST['clasriesg_mat'];
$nomenfermera_mat=$_POST['nomenfermera_mat'];
$riesgcaida_mat=$_POST['riesgcaida_mat'];

$edofisico_mat=$_POST['edofisico_mat'];
$edomentalnor_mat=$_POST['edomentalnor_mat'];
$actividad_mat=$_POST['actividad_mat'];
$movilidad_mat=$_POST['movilidad_mat'];
$incont_mat=$_POST['incont_mat'];
$totnorton_mat=$edofisico_mat+$edomentalnor_mat+$actividad_mat+$movilidad_mat+$incont_mat;


$nomenfnorton_mat=$_POST['nomenfnorton_mat'];
$acriesg_mat=$_POST['acriesg_mat'];


//checkbox ultimos
if(isset($_POST['manregtera_mat'])){$manregtera_mat=$_POST['manregtera_mat'];}else{ $manregtera_mat='';}
if(isset($_POST['patsexual_mat'])){$patsexual_mat=$_POST['patsexual_mat'];}else{ $patsexual_mat='';}
if(isset($_POST['detglucion_mat'])){$detglucion_mat=$_POST['detglucion_mat'];}else{ $detglucion_mat='';}
if(isset($_POST['nutridefecto_mat'])){$nutridefecto_mat=$_POST['nutridefecto_mat'];}else{ $nutridefecto_mat='';}
if(isset($_POST['nutriexc_mat'])){$nutriexc_mat=$_POST['nutriexc_mat'];}else{ $nutriexc_mat='';}
if(isset($_POST['voliqui_mat'])){$voliqui_mat=$_POST['voliqui_mat'];}else{ $voliqui_mat='';}
if(isset($_POST['defivoliq_mat'])){$defivoliq_mat=$_POST['defivoliq_mat'];}else{ $defivoliq_mat='';}
if(isset($_POST['exvoliqui_mat'])){$exvoliqui_mat=$_POST['exvoliqui_mat'];}else{ $exvoliqui_mat='';}
if(isset($_POST['desliqui_mat'])){$desliqui_mat=$_POST['desliqui_mat'];}else{ $desliqui_mat='';}
if(isset($_POST['sinpostra_mat'])){$sinpostra_mat=$_POST['sinpostra_mat'];}else{ $sinpostra_mat='';}
if(isset($_POST['risinpostra_mat'])){$risinpostra_mat=$_POST['risinpostra_mat'];}else{ $risinpostra_mat='';}
if(isset($_POST['temor_mat'])){$temor_mat=$_POST['temor_mat'];}else{ $temor_mat='';}
if(isset($_POST['ansiedad_mat'])){$ansiedad_mat=$_POST['ansiedad_mat'];}else{ $ansiedad_mat='';}
if(isset($_POST['ansmuerte_mat'])){$ansmuerte_mat=$_POST['ansmuerte_mat'];}else{ $ansmuerte_mat='';}
if(isset($_POST['afronine_mat'])){$afronine_mat=$_POST['afronine_mat'];}else{ $afronine_mat='';}
if(isset($_POST['disre_mat'])){$disre_mat=$_POST['disre_mat'];}else{ $disre_mat='';}
if(isset($_POST['disma_mat'])){$disma_mat=$_POST['disma_mat'];}else{ $disma_mat='';}
if(isset($_POST['eliuri_mat'])){$eliuri_mat=$_POST['eliuri_mat'];}else{ $eliuri_mat='';}
if(isset($_POST['returi_mat'])){$returi_mat=$_POST['returi_mat'];}else{ $returi_mat='';}
if(isset($_POST['inconuri_mat'])){$inconuri_mat=$_POST['inconuri_mat'];}else{ $inconuri_mat='';}
if(isset($_POST['inconurifun_mat'])){$inconurifun_mat=$_POST['inconurifun_mat'];}else{ $inconurifun_mat='';}
if(isset($_POST['inconfecal_mat'])){$inconfecal_mat=$_POST['inconfecal_mat'];}else{ $inconfecal_mat='';}
if(isset($_POST['diarrea_mat'])){$diarrea_mat=$_POST['diarrea_mat'];}else{ $diarrea_mat='';}
if(isset($_POST['estreñ_mat'])){$estreñ_mat=$_POST['estreñ_mat'];}else{ $estreñ_mat='';}

if(isset($_POST['riestreñ_mat'])){$riestreñ_mat=$_POST['riestreñ_mat'];}else{ $riestreñ_mat='';}
if(isset($_POST['detgas_mat'])){$detgas_mat=$_POST['detgas_mat'];}else{ $detgas_mat='';}
if(isset($_POST['sufesp_mat'])){$sufesp_mat=$_POST['sufesp_mat'];}else{ $sufesp_mat='';}
if(isset($_POST['intrat_mat'])){$intrat_mat=$_POST['intrat_mat'];}else{ $intrat_mat='';}
if(isset($_POST['detpasue_mat'])){$detpasue_mat=$_POST['detpasue_mat'];}else{ $detpasue_mat='';}
if(isset($_POST['depri_mat'])){$depri_mat=$_POST['depri_mat'];}else{ $depri_mat='';}
if(isset($_POST['detmov_mat'])){$detmov_mat=$_POST['detmov_mat'];}else{ $detmov_mat='';}
if(isset($_POST['detmovcama_mat'])){$detmovcama_mat=$_POST['detmovcama_mat'];}else{ $detmovcama_mat='';}
if(isset($_POST['detdeam_mat'])){$detdeam_mat=$_POST['detdeam_mat'];}else{ $detdeam_mat='';}
if(isset($_POST['defaut_mat'])){$defaut_mat=$_POST['defaut_mat'];}else{ $defaut_mat='';}
if(isset($_POST['deficitbaño_mat'])){$deficitbaño_mat=$_POST['deficitbaño_mat'];}else{ $deficitbaño_mat='';}

if(isset($_POST['defialim_mat'])){$defialim_mat=$_POST['defialim_mat'];}else{ $defialim_mat='';}
if(isset($_POST['defiwc_mat'])){$defiwc_mat=$_POST['defiwc_mat'];}else{ $defiwc_mat='';}
if(isset($_POST['fatiga_mat'])){$fatiga_mat=$_POST['fatiga_mat'];}else{ $fatiga_mat='';}
if(isset($_POST['discardiaco_mat'])){$discardiaco_mat=$_POST['discardiaco_mat'];}else{ $discardiaco_mat='';}
if(isset($_POST['detres_mat'])){$detres_mat=$_POST['detres_mat'];}else{ $detres_mat='';}
if(isset($_POST['patresine_mat'])){$patresine_mat=$_POST['patresine_mat'];}else{ $patresine_mat='';}
if(isset($_POST['resventi_mat'])){$resventi_mat=$_POST['resventi_mat'];}else{ $resventi_mat='';}
if(isset($_POST['pertinular_mat'])){$pertinular_mat=$_POST['pertinular_mat'];}else{ $pertinular_mat='';}
if(isset($_POST['riesinfecc_mat'])){$riesinfecc_mat=$_POST['riesinfecc_mat'];}else{ $riesinfecc_mat='';}
if(isset($_POST['mucoral_mat'])){$mucoral_mat=$_POST['mucoral_mat'];}else{ $mucoral_mat='';}
if(isset($_POST['rieslesion_mat'])){$rieslesion_mat=$_POST['rieslesion_mat'];}else{ $rieslesion_mat='';}

if(isset($_POST['lesperio_mat'])){$lesperio_mat=$_POST['lesperio_mat'];}else{ $lesperio_mat='';}
if(isset($_POST['riesgocai_mat'])){$riesgocai_mat=$_POST['riesgocai_mat'];}else{ $riesgocai_mat='';}
if(isset($_POST['riestrau_mat'])){$riestrau_mat=$_POST['riestrau_mat'];}else{ $riestrau_mat='';}
if(isset($_POST['intecutanea_mat'])){$intecutanea_mat=$_POST['intecutanea_mat'];}else{ $intecutanea_mat='';}
if(isset($_POST['intetis_mat'])){$intetis_mat=$_POST['intetis_mat'];}else{ $intetis_mat='';}
if(isset($_POST['dentincion_mat'])){$dentincion_mat=$_POST['dentincion_mat'];}else{ $dentincion_mat='';}
if(isset($_POST['asfix_mat'])){$asfix_mat=$_POST['asfix_mat'];}else{ $asfix_mat='';}
if(isset($_POST['riesasp_mat'])){$riesasp_mat=$_POST['riesasp_mat'];}else{ $riesasp_mat='';}
if(isset($_POST['limvias_mat'])){$limvias_mat=$_POST['limvias_mat'];}else{ $limvias_mat='';}
if(isset($_POST['neuroperi_mat'])){$neuroperi_mat=$_POST['neuroperi_mat'];}else{ $neuroperi_mat='';}
if(isset($_POST['violdir_mat'])){$violdir_mat=$_POST['violdir_mat'];}else{ $violdir_mat='';}

if(isset($_POST['violauto_mat'])){$violauto_mat=$_POST['violauto_mat'];}else{ $violauto_mat='';}
if(isset($_POST['riesuic_mat'])){$riesuic_mat=$_POST['riesuic_mat'];}else{ $riesuic_mat='';}
if(isset($_POST['riesintox_mat'])){$riesintox_mat=$_POST['riesintox_mat'];}else{ $riesintox_mat='';}
if(isset($_POST['tempercorp_mat'])){$tempercorp_mat=$_POST['tempercorp_mat'];}else{ $tempercorp_mat='';}
if(isset($_POST['termoine_mat'])){$termoine_mat=$_POST['termoine_mat'];}else{ $termoine_mat='';}
if(isset($_POST['hipo_mat'])){$hipo_mat=$_POST['hipo_mat'];}else{ $hipo_mat='';}
if(isset($_POST['hiper_mat'])){$hiper_mat=$_POST['hiper_mat'];}else{ $hiper_mat='';}
if(isset($_POST['transens_mat'])){$transens_mat=$_POST['transens_mat'];}else{ $transens_mat='';}
if(isset($_POST['condef_mat'])){$condef_mat=$_POST['condef_mat'];}else{ $condef_mat='';}
if(isset($_POST['confagu_mat'])){$confagu_mat=$_POST['confagu_mat'];}else{ $confagu_mat='';}
if(isset($_POST['confcro_mat'])){$confcro_mat=$_POST['confcro_mat'];}else{ $confcro_mat='';}

if(isset($_POST['detmem_mat'])){$detmem_mat=$_POST['detmem_mat'];}else{ $detmem_mat='';}
if(isset($_POST['propen_mat'])){$propen_mat=$_POST['propen_mat'];}else{ $propen_mat='';}
if(isset($_POST['comver_mat'])){$comver_mat=$_POST['comver_mat'];}else{ $comver_mat='';}
if(isset($_POST['dolagudo_mat'])){$dolagudo_mat=$_POST['dolagudo_mat'];}else{ $dolagudo_mat='';}
if(isset($_POST['dolcronico_mat'])){$dolcronico_mat=$_POST['dolcronico_mat'];}else{ $dolcronico_mat='';}
if(isset($_POST['nauseas_mat'])){$nauseas_mat=$_POST['nauseas_mat'];}else{ $nauseas_mat='';}
if(isset($_POST['aislasoc_mat'])){$aislasoc_mat=$_POST['aislasoc_mat'];}else{ $aislasoc_mat='';}
if(isset($_POST['idenper_mat'])){$idenper_mat=$_POST['idenper_mat'];}else{ $idenper_mat='';}
if(isset($_POST['dperanza_mat'])){$dperanza_mat=$_POST['dperanza_mat'];}else{ $dperanza_mat='';}
if(isset($_POST['riesgsol_mat'])){$riesgsol_mat=$_POST['riesgsol_mat'];}else{ $riesgsol_mat='';}
if(isset($_POST['bajaauto_mat'])){$bajaauto_mat=$_POST['bajaauto_mat'];}else{ $bajaauto_mat='';}

if(isset($_POST['imgcor_mat'])){$imgcor_mat=$_POST['imgcor_mat'];}else{ $imgcor_mat='';}
if(isset($_POST['incapadulto_mat'])){$incapadulto_mat=$_POST['incapadulto_mat'];}else{ $incapadulto_mat='';}
if(isset($_POST['desemrol_mat'])){$desemrol_mat=$_POST['desemrol_mat'];}else{ $desemrol_mat='';}

//$notenferm_mat=$_POST['notenferm_mat'];



//marcaje
$mara=$_POST['mara'];
$marb=$_POST['marb']; 
$marc=$_POST['marc'];   
$mard=$_POST['mard']; 
$mare=$_POST['mare'];  
$marf=$_POST['marf']; 
$marg=$_POST['marg']; 
$marh=$_POST['marh']; 

$pie=$_POST['pie'];
$pied=$_POST['pied']; 
$tod=$_POST['tod'];   
$toi=$_POST['toi']; 
$rodi=$_POST['rodi'];  
$rodd=$_POST['rodd']; 
$musloi=$_POST['musloi']; 
$muslod=$_POST['muslod']; 
$inglei=$_POST['inglei'];
$iabdomen=$_POST['iabdomen']; 
$ddi=$_POST['ddi'];   
$ddidos=$_POST['ddidos']; 
$dditres=$_POST['dditres'];  
$ddicuatro=$_POST['ddicuatro']; 
$ddicinco=$_POST['ddicinco']; 
$ddoderu=$_POST['ddoderu']; 
$palmai=$_POST['palmai'];
$muñei=$_POST['munei']; 
$brazi=$_POST['brazi'];   
$brazci=$_POST['brazci']; 
$homi=$_POST['homi'];  
$peci=$_POST['peci']; 
$pecti=$_POST['pecti']; 
$pectd=$_POST['pectd']; 
$cvi=$_POST['cvi'];
$dedodos=$_POST['dedodos']; 
$dedotres=$_POST['dedotres'];   
$dedocuatro=$_POST['dedocuatro']; 
$dedocincoo=$_POST['dedocincoo'];  
$palmad=$_POST['palmad']; 
$muñecad=$_POST['munecad']; 
$derbraz=$_POST['derbraz']; 
$annbraz=$_POST['annbraz']; 
$cconder=$_POST['cconder'];   
$hombrod=$_POST['hombrod']; 
$mandiderr=$_POST['mandiderr'];  
$mandicentroo=$_POST['mandicentroo'];
$mandiizqui=$_POST['mandiizqui']; 
$mejderecha=$_POST['mejderecha'];
$narizc=$_POST['narizc']; 
$frenteizquierda=$_POST['frenteizquierda'];   
$frentederecha=$_POST['frentederecha']; 

$nuevo=$_POST['nuevo']; 
$nuevo1=$_POST['nuevo1']; 
$nuevo2=$_POST['nuevo2']; 
$nuevo3=$_POST['nuevo3']; 
$nuevo4=$_POST['nuevo4']; 
$nuevo5=$_POST['nuevo5']; 
$nuevo6=$_POST['nuevo6']; 
$nuevo7=$_POST['nuevo7']; 

//espalda
$plantapiea=$_POST['plantapiea'];  
$plantapieader=$_POST['plantapieader'];
$tobilloati=$_POST['tobilloati']; 
$tobilloatd=$_POST['tobilloatd'];
$ptiatras=$_POST['ptiatras'];  
$ptdatras=$_POST['ptdatras'];
$pierespaldai=$_POST['pierespaldai']; 
$pierespaldad=$_POST['pierespaldad'];
$musloatrasiz=$_POST['musloatrasiz'];  
$musloatrasder=$_POST['musloatrasder'];
$glutiz=$_POST['glutiz']; 
$glutder=$_POST['glutder'];
$cinturaiz=$_POST['cinturaiz'];  
$cinturader=$_POST['cinturader'];
$costilliz=$_POST['costilliz']; 
$costillder=$_POST['costillder'];
$espaldarribaiz=$_POST['espaldarribaiz'];  
$espaldaarribader=$_POST['espaldaarribader'];
$espaldaalta=$_POST['espaldaalta']; 
$dorsaliz=$_POST['dorsaliz'];
$dorsalder=$_POST['dorsalder'];  
$munecaatrasiz=$_POST['munecaatrasiz'];
$munecaatrasder=$_POST['munecaatrasder']; 
$antebiesp=$_POST['antebiesp'];
$antebdesp=$_POST['antebdesp'];
$casicodoi=$_POST['casicodoi'];
$casicododer=$_POST['casicododer'];
$brazalti=$_POST['brazalti'];
$brazaltder=$_POST['brazaltder'];
$cuellatrasb=$_POST['cuellatrasb'];
$cuellatrasmedio=$_POST['cuellatrasmedio'];
$cabedorsalm=$_POST['cabedorsalm'];
$cabealtaizqu=$_POST['cabealtaizqu'];
$cabezaaltader=$_POST['cabezaaltader'];
$espizq=$_POST['espizq'];
$espder=$_POST['espder'];
$coxis=$_POST['coxis'];

$fechareporte=$_POST['fechareporte'];

$tiposlabo=$_POST['tiposlabo'];


//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

if ($hora_mat == '1' || $hora_mat == '2' || $hora_mat == '3' || $hora_mat == '4' || $hora_mat == '5' || $hora_mat == '6' || $hora_mat == '7') {
   // Restamos un día a la fecha actual
   $yesterday = date('Y-m-d H:i', strtotime('-1 day')) ; 
} else { 
   $yesterday = date("Y-m-d H:i"); 
}

if ($hora_mat == '8' || $hora_mat == '9' || $hora_mat == '10' || $hora_mat == '11' || $hora_mat == '12' || $hora_mat == '13') {
$turno="MATUTINO";
}else if ($hora_mat == '14'|| $hora_mat == '15' || $hora_mat == '16' || $hora_mat == '17' || $hora_mat == '18' || $hora_mat == '19' || $hora_mat == '20' || $hora_mat == '21') {
$turno="VESPERTINO";
}else if ($hora_mat == '22' || $hora_mat == '23' || $hora_mat == '24' || $hora_mat == '1' || $hora_mat == '2' || $hora_mat == '3' || $hora_mat == '4' || $hora_mat == '5' || $hora_mat == '6' || $hora_mat == '7') {
$turno="NOCTURNO";
}




//date_default_timezone_set('America/Guatemala');
$fe = date("Y-m-d H:i:s");


  $ingresar = mysqli_query($conexion, 'INSERT INTO enf_reg_clin (id_atencion,fecha_mat,hora_mat,turno,tipodieta_mat,quemadura_mat,heridap_mat,enfisema_mat,ulcpre_mat,dermoabra_mat,hematoma_mat,ciano_mat,rash_mat,fracturas_mat,herquir_mat,equimosis_mat,funprev_mat,fron_mat,malar_mat,mandi_mat,delto_mat,pecto_mat,esternal_mat,antebrazo_mat,muñeca_mat,manopal_mat,muslo_mat,rodilla_mat,pierna_mat,pirnasal_mat,maxsup_mat,menton_mat,acromial_mat,brazo_mat,plicodo_mat,abdomen_mat,regingui_mat,regpub_mat,pdedo_mat,sdedo_mat,tdedo_mat,cdedo_mat,qdedo_mat,tobi_mat,piedor_mat,parie_mat,occi_mat,nuca_mat,brazo2_mat,codo2_mat,antebrazo2_mat,muñeca2_mat,manodor_mat,plipop_mat,pierna2_mat,pieplan_mat,cuellopos_mat,reginter_mat,regesca_mat,reginfra_mat,lumbar_mat,gluteo_mat,muslo2_mat,talon2_mat,ramsay_mat,apecular_mat,respmot_mat,respver_mat,caidprev_mat,medcaidas_mat,defsens_mat,edomental_mat,deambula_mat,totalcaidas_mat,nomenfermera_mat,riesgcaida_mat,edofisico_mat,edomentalnor_mat,actividad_mat,movilidad_mat,incont_mat,totnorton_mat,nomenfnorton_mat,acriesg_mat,manregtera_mat,patsexual_mat,detglucion_mat,nutridefecto_mat,nutriexc_mat,voliqui_mat,defivoliq_mat,exvoliqui_mat,desliqui_mat,sinpostra_mat,risinpostra_mat,temor_mat,ansiedad_mat,ansmuerte_mat,afronine_mat,disre_mat,disma_mat,eliuri_mat,returi_mat,inconuri_mat,inconurifun_mat,inconfecal_mat,diarrea_mat,estreñ_mat,riestreñ_mat,detgas_mat,sufesp_mat,intrat_mat,detpasue_mat,depri_mat,detmov_mat,detmovcama_mat,detdeam_mat,defaut_mat,deficitbaño_mat,defialim_mat,defiwc_mat,fatiga_mat,discardiaco_mat,detres_mat,patresine_mat,resventi_mat,pertinular_mat,riesinfecc_mat,mucoral_mat,rieslesion_mat,lesperio_mat,riesgocai_mat,riestrau_mat,intecutanea_mat,intetis_mat,dentincion_mat,asfix_mat,riesasp_mat,limvias_mat,neuroperi_mat,violdir_mat,violauto_mat,riesuic_mat,riesintox_mat,tempercorp_mat,termoine_mat,hipo_mat,hiper_mat,transens_mat,condef_mat,confagu_mat,confcro_mat,detmem_mat,propen_mat,comver_mat,dolagudo_mat,dolcronico_mat,nauseas_mat,aislasoc_mat,idenper_mat,dperanza_mat,riesgsol_mat,bajaauto_mat,imgcor_mat,incapadulto_mat,desemrol_mat,id_usua,enf_fecha,mara,marb,marc,mard,mare,marf,marg,marh,pie,pied,tod,toi,rodi,rodd,musloi,muslod,inglei,iabdomen,ddi,ddidos,dditres,ddicuatro,ddicinco,ddoderu,palmai,muñei,brazi,brazci,homi,peci,pecti,pectd,cvi,dedodos,dedotres,dedocuatro,dedocincoo,palmad,muñecad,derbraz,annbraz,cconder,hombrod,mandiderr,mandicentroo,mandiizqui,mejderecha,narizc,frenteizquierda,frentederecha,plantapiea,plantapieader,tobilloati,tobilloatd,ptiatras,ptdatras,pierespaldai,pierespaldad,musloatrasiz,musloatrasder,glutiz,glutder,cinturaiz,cinturader,costilliz,costillder,espaldarribaiz,espaldaarribader,espaldaalta,dorsaliz,dorsalder,munecaatrasiz,munecaatrasder,antebiesp,antebdesp,casicodoi,casicododer,brazalti,brazaltder,cuellatrasb,cuellatrasmedio,cabedorsalm,cabealtaizqu,cabezaaltader,nuevo,nuevo1,nuevo2,nuevo3,nuevo4,nuevo5,nuevo6,nuevo7,espizq,espder,coxis,fecha_registro,tiposlabo) values (' . $id_atencion . ' ,"' . $fechareporte . '","' . $hora_mat . '","' . $turno . '","' . $tipodieta_mat . '","' . $quemadura_mat . '","' . $heridap_mat . '","' . $enfisema_mat . '","' . $ulcpre_mat . '","' . $dermoabra_mat . '","' . $hematoma_mat . '","' . $ciano_mat . '","' . $rash_mat . '","' . $fracturas_mat . '","' . $herquir_mat . '","' . $equimosis_mat . '","' . $funprev_mat . '","' . $fron_mat . '","' . $malar_mat . '","' . $mandi_mat . '","' . $delto_mat . '","' . $pecto_mat . '","' . $esternal_mat . '","' . $antebrazo_mat . '","' . $muñeca_mat . '","' . $manopal_mat . '","' . $muslo_mat . '","' . $rodilla_mat . '","' . $pierna_mat . '","' . $pirnasal_mat . '","' . $maxsup_mat . '","' . $menton_mat . '","' . $acromial_mat . '","' . $brazo_mat . '","' . $plicodo_mat . '","' . $abdomen_mat . '","' . $regingui_mat . '","' . $regpub_mat . '","' . $pdedo_mat . '","' . $sdedo_mat . '","' . $tdedo_mat . '","' . $cdedo_mat . '","' . $qdedo_mat . '","' . $tobi_mat . '","' . $piedor_mat . '","' . $parie_mat . '","' . $occi_mat . '","' . $nuca_mat . '","' . $brazo2_mat . '","' . $codo2_mat . '","' . $antebrazo2_mat . '","' . $muñeca2_mat . '","' . $manodor_mat . '","' . $plipop_mat . '","' . $pierna2_mat . '","' . $pieplan_mat . '","' . $cuellopos_mat . '","' . $reginter_mat . '","' . $regesca_mat . '","' . $reginfra_mat . '","' . $lumbar_mat . '","' . $gluteo_mat . '","' . $muslo2_mat . '","' . $talon2_mat . '","' . $ramsay_mat . '","' . $apecular_mat . '","' . $respmot_mat . '","' . $respver_mat . '","' . $caidprev_mat . '","' . $medcaidas_mat . '","' . $defsens_mat . '","' . $edomental_mat . '","' . $deambula_mat . '","' . $totalcaidas_mat . '","' . $nomenfermera_mat . '","' . $riesgcaida_mat . '","' . $edofisico_mat . '","' . $edomentalnor_mat . '","' . $actividad_mat . '","' . $movilidad_mat . '","' . $incont_mat . '","' . $totnorton_mat . '","' . $nomenfnorton_mat . '","' . $acriesg_mat . '","' . $manregtera_mat . '","' . $patsexual_mat . '","' . $detglucion_mat . '","' . $nutridefecto_mat . '","' . $nutriexc_mat . '","' . $voliqui_mat . '","' . $defivoliq_mat . '","' . $exvoliqui_mat . '","' . $desliqui_mat . '","' . $sinpostra_mat . '","' . $risinpostra_mat . '","' . $temor_mat . '","' . $ansiedad_mat . '","' . $ansmuerte_mat . '","' . $afronine_mat . '","' . $disre_mat . '","' . $disma_mat . '","' . $eliuri_mat . '","' . $returi_mat . '","' . $inconuri_mat . '","' . $inconurifun_mat . '","' . $inconfecal_mat . '","' . $diarrea_mat . '","' . $estreñ_mat . '","' . $riestreñ_mat . '","' . $detgas_mat . '","' . $sufesp_mat . '","' . $intrat_mat . '","' . $detpasue_mat . '","' . $depri_mat . '","' . $detmov_mat . '","' . $detmovcama_mat . '","' . $detdeam_mat . '","' . $defaut_mat . '","' . $deficitbaño_mat . '","' . $defialim_mat . '","' . $defiwc_mat . '","' . $fatiga_mat . '","' . $discardiaco_mat .'","' . $detres_mat .'","' . $patresine_mat .'","' . $resventi_mat .'","' . $pertinular_mat .'","' . $riesinfecc_mat .'","' . $mucoral_mat .'","' . $rieslesion_mat .'","' . $lesperio_mat .'","' . $riesgocai_mat .'","' . $riestrau_mat .'","' . $intecutanea_mat .'","' . $intetis_mat .'","' . $dentincion_mat .'","' . $asfix_mat .'","' . $riesasp_mat .'","' . $limvias_mat .'","' . $neuroperi_mat .'","' . $violdir_mat .'","' . $violauto_mat .'","' . $riesuic_mat .'","' . $riesintox_mat .'","' . $tempercorp_mat .'","' . $termoine_mat .'","' . $hipo_mat .'","' . $hiper_mat .'","' . $transens_mat .'","' . $condef_mat .'","' . $confagu_mat .'","' . $confcro_mat .'","' . $detmem_mat .'","' . $propen_mat .'","' . $comver_mat .'","' . $dolagudo_mat .'","' . $dolcronico_mat .'","' . $nauseas_mat .'","' . $aislasoc_mat .'","' . $idenper_mat .'","' . $dperanza_mat .'","' . $riesgsol_mat .'","' . $bajaauto_mat .'","' . $imgcor_mat .'","' . $incapadulto_mat .'","' . $desemrol_mat .'",' . $id_usua .',"' . $fe .'","' . $mara .'","' . $marb .'","' . $marc .'","' . $mard .'","' . $mare .'","' . $marf .'","' . $marg .'","' . $marh .'","' . $pie .'","' . $pied .'","' . $tod .'","' . $toi .'","' . $rodi .'","' . $rodd .'","' . $musloi .'","' . $muslod .'","' . $inglei .'","' . $iabdomen .'","' . $ddi .'","' . $ddidos .'","' . $dditres .'","' . $ddicuatro .'","' . $ddicinco .'","' . $ddoderu .'","' . $palmai .'","' . $muñei .'","' . $brazi .'","' . $brazci .'","' . $homi .'","' . $peci .'","' . $pecti .'","' . $pectd .'","' . $cvi .'","' . $dedodos .'","' . $dedotres .'","' . $dedocuatro .'","' . $dedocincoo .'","' . $palmad .'","' . $muñecad .'","' . $derbraz .'","' . $annbraz .'","' . $cconder .'","' . $hombrod .'","' . $mandiderr .'","' . $mandicentroo .'","' . $mandiizqui .'","' . $mejderecha .'","' . $narizc .'","' . $frenteizquierda .'","' . $frentederecha .'","' . $plantapiea .'","' . $plantapieader .'","' . $tobilloati .'","' . $tobilloatd .'","' . $ptiatras .'","' . $ptdatras .'","' . $pierespaldai .'","' . $pierespaldad .'","' . $musloatrasiz .'","' . $musloatrasder .'","' . $glutiz .'","' . $glutder .'","' . $cinturaiz .'","' . $cinturader .'","' . $costilliz .'","' . $costillder .'","' . $espaldarribaiz .'","' . $espaldaarribader .'","' . $espaldaalta .'","' . $dorsaliz .'","' . $dorsalder .'","' . $munecaatrasiz .'","' . $munecaatrasder .'","' . $antebiesp .'","' . $antebdesp .'","' . $casicodoi .'","' . $casicododer .'","' . $brazalti .'","' . $brazaltder .'","' . $cuellatrasb .'","' . $cuellatrasmedio .'","' . $cabedorsalm .'","' . $cabealtaizqu .'","' . $cabezaaltader .'","' . $nuevo .'","' . $nuevo1 .'","' . $nuevo2 .'","' . $nuevo3 .'","' . $nuevo4 .'","' . $nuevo5 .'","' . $nuevo6 .'","' . $nuevo7 .'","' . $espizq .'","' . $espder .'","' . $coxis .'","' . $yesterday .'","'.$tiposlabo.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

header('location: reg_clin.php');


