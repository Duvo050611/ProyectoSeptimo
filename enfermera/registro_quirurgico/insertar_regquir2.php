<?php
session_start();
include '../../conexionbd.php';
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];




//date_default_timezone_set('America/Guatemala');
$fecha = date("Y-m-d");

$fecha_reporte=$_POST['fecha_reporte'];
$hora=$_POST['hora'];
$asepsia=$_POST['asepsia'];


$in_isq=$_POST['in_isq'];
$ter_isq=$_POST['ter_isq'];
$in_ren=$_POST['in_ren'];

$ter_ren=$_POST['ter_ren'];

$elect=$_POST['elect'];
$pos=$_POST['pos'];
$ant=$_POST['ant'];
$areaquir=$_POST['areaquir'];
$tip_cir=$_POST['tip_cir'];
$pipat=$_POST['pipat'];

$pdiast=$_POST['pdiast'];
$psist=$_POST['psist'];
$f_card=$_POST['f_card'];
$f_resp=$_POST['f_resp'];
$temp=$_POST['temp'];
$spo2=$_POST['spo2'];


$insul=$_POST['insul'];
$cist=$_POST['cist'];
$dispo=$_POST['dispo'];
$cal=$_POST['cal'];
$qins=$_POST['qins'];
$viapar=$_POST['viapar'];

$tipan=$_POST['tipan'];
$nom_enf_tipan=$_POST['nom_enf_tipan'];

if(isset($_POST['glice'])){$glice=$_POST['glice'];}else{ $glice=0;}
if(isset($_POST['viapar'])){$viapar=$_POST['viapar'];}else{ $viapar=0;}
if(isset($_POST['hemod'])){$hemod=$_POST['hemod'];}else{ $hemod=0;}
if(isset($_POST['egotro'])){$egotro=$_POST['egotro'];}else{ $egotro=0;}


if(isset($_POST['diu'])){$diu=$_POST['diu'];}else{ $diu=0;}
if(isset($_POST['eva'])){$eva=$_POST['eva'];}else{ $eva=0;}
if(isset($_POST['sang'])){$sang=$_POST['sang'];}else{ $sang=0;}
if(isset($_POST['vom'])){$vom=$_POST['vom'];}else{ $vom=0;}
if(isset($_POST['aspboc'])){$aspboc=$_POST['aspboc'];}else{ $aspboc=0;}
if(isset($_POST['gast'])){$gast=$_POST['gast'];}else{ $gast=0;}
if(isset($_POST['dren'])){$dren=$_POST['dren'];}else{ $dren=0;}
//if(isset($_POST['perd'])){$perd=$_POST['perd'];}else{ $perd=0;}
$egpar_t=$diu+$eva+$sang+$vom+$aspboc+$gast+$dren+$perd;

$caidas=$_POST['caidas'];
$medi=$_POST['medi'];
$defic=$_POST['defic'];
$estement=$_POST['estement'];
$deamb=$_POST['deamb'];
$total=$caidas+$medi+$defic+$estement+$deamb;
$classresg=$_POST['classresg'];
$nom_enf=$_POST['nom_enf'];
$interv_m=$_POST['interv_m'];

if(isset($_POST['quem_m'])){$quem_m=$_POST['quem_m'];}else{ $quem_m='';}
if(isset($_POST['uls_m'])){$uls_m=$_POST['uls_m'];}else{ $uls_m='';}
if(isset($_POST['nec_m'])){$nec_m=$_POST['nec_m'];}else{ $nec_m='';}
if(isset($_POST['her_m'])){$her_m=$_POST['her_m'];}else{ $her_m='';}
if(isset($_POST['tub_m'])){$tub_m=$_POST['tub_m'];}else{ $tub_m='';}
if(isset($_POST['der_m'])){$der_m=$_POST['der_m'];}else{ $der_m='';}
if(isset($_POST['ras_m'])){$ras_m=$_POST['ras_m'];}else{ $ras_m='';}
if(isset($_POST['eq_m'])){$eq_m=$_POST['eq_m'];}else{ $eq_m='';}
if(isset($_POST['enf_m'])){$enf_m=$_POST['enf_m'];}else{ $enf_m='';}
if(isset($_POST['ema_m'])){$ema_m=$_POST['ema_m'];}else{ $ema_m='';}
if(isset($_POST['frac_m'])){$frac_m=$_POST['frac_m'];}else{ $frac_m='';}
if(isset($_POST['acc_m'])){$acc_m=$_POST['acc_m'];}else{ $acc_m='';}
if(isset($_POST['pete_m'])){$pete_m=$_POST['pete_m'];}else{ $pete_m='';}
if(isset($_POST['ede_m'])){$ede_m=$_POST['ede_m'];}else{ $ede_m='';}
if(isset($_POST['fron_m'])){$fron_m=$_POST['fron_m'];}else{ $fron_m='';}
if(isset($_POST['mal_m'])){$mal_m=$_POST['mal_m'];}else{ $mal_m='';}
if(isset($_POST['man_m'])){$man_m=$_POST['man_m'];}else{ $man_m='';}
if(isset($_POST['del_m'])){$del_m=$_POST['del_m'];}else{ $del_m='';}
if(isset($_POST['pec_m'])){$pec_m=$_POST['pec_m'];}else{ $pec_m='';}
if(isset($_POST['est_m'])){$est_m=$_POST['est_m'];}else{ $est_m='';}
if(isset($_POST['ant_m'])){$ant_m=$_POST['ant_m'];}else{ $ant_m='';}
if(isset($_POST['mu_m'])){$mu_m=$_POST['mu_m'];}else{ $mu_m='';}
if(isset($_POST['mano_m'])){$mano_m=$_POST['mano_m'];}else{ $mano_m='';}
if(isset($_POST['mus_m'])){$mus_m=$_POST['mus_m'];}else{ $mus_m='';}
if(isset($_POST['rod_m'])){$rod_m=$_POST['rod_m'];}else{ $rod_m='';}
if(isset($_POST['pier_m'])){$pier_m=$_POST['pier_m'];}else{ $pier_m='';}
if(isset($_POST['pri_m'])){$pri_m=$_POST['pri_m'];}else{ $pri_m='';}
if(isset($_POST['max_m'])){$max_m=$_POST['max_m'];}else{ $max_m='';}
if(isset($_POST['men_m'])){$men_m=$_POST['men_m'];}else{ $men_m='';}
if(isset($_POST['ac_m'])){$ac_m=$_POST['ac_m'];}else{ $ac_m='';}
if(isset($_POST['bra_m'])){$bra_m=$_POST['bra_m'];}else{ $bra_m='';}
if(isset($_POST['pli_m'])){$pli_m=$_POST['pli_m'];}else{ $pli_m='';}
if(isset($_POST['abd_m'])){$abd_m=$_POST['abd_m'];}else{ $abd_m='';}
if(isset($_POST['reg_m'])){$reg_m=$_POST['reg_m'];}else{ $reg_m='';}
if(isset($_POST['pub_m'])){$pub_m=$_POST['pub_m'];}else{ $pub_m='';}
if(isset($_POST['pde_m'])){$pde_m=$_POST['pde_m'];}else{ $pde_m='';}
if(isset($_POST['sde_m'])){$sde_m=$_POST['sde_m'];}else{ $sde_m='';}
if(isset($_POST['tde_m'])){$tde_m=$_POST['tde_m'];}else{ $tde_m='';}
if(isset($_POST['cde_m'])){$cde_m=$_POST['cde_m'];}else{ $cde_m='';}
if(isset($_POST['qde_m'])){$qde_m=$_POST['qde_m'];}else{ $qde_m='';}
if(isset($_POST['tob_m'])){$tob_m=$_POST['tob_m'];}else{ $tob_m='';}
if(isset($_POST['pie_m'])){$pie_m=$_POST['pie_m'];}else{ $pie_m='';}
if(isset($_POST['par_m'])){$par_m=$_POST['par_m'];}else{ $par_m='';}
if(isset($_POST['occ_m'])){$occ_m=$_POST['occ_m'];}else{ $occ_m='';}
if(isset($_POST['nuca_m'])){$nuca_m=$_POST['nuca_m'];}else{ $nuca_m='';}
if(isset($_POST['braz_m'])){$braz_m=$_POST['braz_m'];}else{ $braz_m='';}
if(isset($_POST['codo_m'])){$codo_m=$_POST['codo_m'];}else{ $codo_m='';}
if(isset($_POST['ante_m'])){$ante_m=$_POST['ante_m'];}else{ $ante_m='';}
if(isset($_POST['mune_m'])){$mune_m=$_POST['mune_m'];}else{ $mune_m='';}
if(isset($_POST['mano2_m'])){$mano2_m=$_POST['mano2_m'];}else{ $mano2_m='';}
if(isset($_POST['plieg_m'])){$plieg_m=$_POST['plieg_m'];}else{ $plieg_m='';}
if(isset($_POST['piern_m'])){$piern_m=$_POST['piern_m'];}else{ $piern_m='';}
if(isset($_POST['piep_m'])){$piep_m=$_POST['piep_m'];}else{ $piep_m='';}
if(isset($_POST['cuello_m'])){$cuello_m=$_POST['cuello_m'];}else{ $cuello_m='';}
if(isset($_POST['regin_m'])){$regin_m=$_POST['regin_m'];}else{ $regin_m='';}
if(isset($_POST['regesc_m'])){$regesc_m=$_POST['regesc_m'];}else{ $regesc_m='';}
if(isset($_POST['reginf_m'])){$reginf_m=$_POST['reginf_m'];}else{ $reginf_m='';}
if(isset($_POST['lum_m'])){$lum_m=$_POST['lum_m'];}else{ $lum_m='';}
if(isset($_POST['glut_m'])){$glut_m=$_POST['glut_m'];}else{ $glut_m='';}
if(isset($_POST['musl_m'])){$musl_m=$_POST['musl_m'];}else{ $musl_m='';}
if(isset($_POST['talon_m'])){$talon_m=$_POST['talon_m'];}else{ $talon_m='';}

$ingrecup=$_POST['ingrecup'];
if(isset($_POST['dol1'])){$dol1=$_POST['dol1'];}else{ $dol1='';}
if(isset($_POST['dol2'])){$dol2=$_POST['dol2'];}else{ $dol2='';}
if(isset($_POST['dol3'])){$dol3=$_POST['dol3'];}else{ $dol3='';}
if(isset($_POST['dol4'])){$dol4=$_POST['dol4'];}else{ $dol4='';}
if(isset($_POST['dol5'])){$dol5=$_POST['dol5'];}else{ $dol5='';}
$egrecup=$_POST['egrecup'];

if(isset($_POST['medol'])){$medol=$_POST['medol'];}else{ $medol='';}

if(isset($_POST['oxi'])){$oxi=$_POST['oxi'];}else{ $oxi='NO';}
if(isset($_POST['con'])){$con=$_POST['con'];}else{ $con='NO';}
if(isset($_POST['muc'])){$muc=$_POST['muc'];}else{ $muc='NO';}
if(isset($_POST['vent'])){$vent=$_POST['vent'];}else{ $vent='NO';}
if(isset($_POST['est'])){$est=$_POST['est'];}else{ $est='NO';}

if(isset($_POST['cito'])){$cito=$_POST['cito'];}else{ $cito='';}
if(isset($_POST['yeso'])){$yeso=$_POST['yeso'];}else{ $yeso='';}
if(isset($_POST['herquir'])){$herquir=$_POST['herquir'];}else{ $herquir='';}
if(isset($_POST['quema'])){$quema=$_POST['quema'];}else{ $quema='';}
if(isset($_POST['ext'])){$ext=$_POST['ext'];}else{ $ext='';}
if(isset($_POST['riesg'])){$riesg=$_POST['riesg'];}else{ $riesg='';}
if(isset($_POST['prec'])){$prec=$_POST['prec'];}else{ $prec='';}
if(isset($_POST['dena'])){$dena=$_POST['dena'];}else{ $dena='';}
if(isset($_POST['bloq'])){$bloq=$_POST['bloq'];}else{ $bloq='';}
if(isset($_POST['movil'])){$movil=$_POST['movil'];}else{ $movil='';}
if(isset($_POST['const'])){$const=$_POST['const'];}else{ $const='';}

$imagen=$_POST['imagen'];
$incidentes=$_POST['incidentes'];

$horac=$_POST['horac'];
$horaas=$_POST['horaas'];


$not_preop=$_POST['not_preop'];
$nom_enf_preop=$_POST['nom_enf_preop'];
$not_trans=$_POST['not_trans'];
$nom_enf_trans=$_POST['nom_enf_trans'];
$not_post=$_POST['not_post'];
$nom_enf_post=$_POST['nom_enf_post'];

//$des_quir=$_POST['des_quir'];


//marcaje
if(isset($_POST['mara'])){$mara=$_POST['mara'];}else{ $mara='';}
if(isset($_POST['marb'])){$marb=$_POST['marb'];}else{ $marb='';}
if(isset($_POST['marc'])){$marc=$_POST['marc'];}else{ $marc='';}
if(isset($_POST['mard'])){$mard=$_POST['mard'];}else{ $mard='';}
if(isset($_POST['mare'])){$mare=$_POST['mare'];}else{ $mare='';}
if(isset($_POST['marf'])){$marf=$_POST['marf'];}else{ $marf='';}
if(isset($_POST['marg'])){$marg=$_POST['marg'];}else{ $marg='';}
if(isset($_POST['marh'])){$marh=$_POST['marh'];}else{ $marh='';}
if(isset($_POST['pie'])){$pie=$_POST['pie'];}else{ $pie='';}
if(isset($_POST['pied'])){$pied=$_POST['pied'];}else{ $pied='';}
if(isset($_POST['tod'])){$tod=$_POST['tod'];}else{ $tod='';}
if(isset($_POST['toi'])){$toi=$_POST['toi'];}else{ $toi='';}
if(isset($_POST['rodi'])){$rodi=$_POST['rodi'];}else{ $rodi='';}
if(isset($_POST['rodd'])){$rodd=$_POST['rodd'];}else{ $rodd='';}
if(isset($_POST['musloi'])){$musloi=$_POST['musloi'];}else{ $musloi='';}
if(isset($_POST['muslod'])){$muslod=$_POST['muslod'];}else{ $muslod='';}
if(isset($_POST['inglei'])){$inglei=$_POST['inglei'];}else{ $inglei='';}
if(isset($_POST['iabdomen'])){$iabdomen=$_POST['iabdomen'];}else{ $iabdomen='';}
if(isset($_POST['ddi'])){$ddi=$_POST['ddi'];}else{ $ddi='';}

if(isset($_POST['ddidos'])){$ddidos=$_POST['ddidos'];}else{ $ddidos='';} 
if(isset($_POST['dditres'])){$dditres=$_POST['dditres'];}else{ $dditres='';} 
if(isset($_POST['ddicuatro'])){$ddicuatro=$_POST['ddicuatro'];}else{ $ddicuatro='';} 
if(isset($_POST['ddicinco'])){$ddicinco=$_POST['ddicinco'];}else{ $ddicinco='';} 
if(isset($_POST['ddoderu'])){$ddoderu=$_POST['ddoderu'];}else{ $ddoderu='';} 
if(isset($_POST['palmai'])){$palmai=$_POST['palmai'];}else{ $palmai='';} 
if(isset($_POST['munei'])){$munei=$_POST['munei'];}else{ $munei='';} 
if(isset($_POST['brazi'])){$brazi=$_POST['brazi'];}else{ $brazi='';} 
if(isset($_POST['brazci'])){$brazci=$_POST['brazci'];}else{ $brazci='';} 
if(isset($_POST['homi'])){$homi=$_POST['homi'];}else{ $homi='';} 
if(isset($_POST['peci'])){$peci=$_POST['peci'];}else{ $peci='';} 
if(isset($_POST['pecti'])){$pecti=$_POST['pecti'];}else{ $pecti='';} 
if(isset($_POST['pectd'])){$pectd=$_POST['pectd'];}else{ $pectd='';} 
if(isset($_POST['cvi'])){$cvi=$_POST['cvi'];}else{ $cvi='';} 

if(isset($_POST['dedodos'])){$dedodos=$_POST['dedodos'];}else{ $dedodos='';} 
if(isset($_POST['dedotres'])){$dedotres=$_POST['dedotres'];}else{ $dedotres='';} 
if(isset($_POST['dedocuatro'])){$dedocuatro=$_POST['dedocuatro'];}else{ $dedocuatro='';} 
if(isset($_POST['dedocincoo'])){$dedocincoo=$_POST['dedocincoo'];}else{ $dedocincoo='';}

if(isset($_POST['palmad'])){$palmad=$_POST['palmad'];}else{ $palmad='';}
if(isset($_POST['munecad'])){$munecad=$_POST['munecad'];}else{ $munecad='';}
if(isset($_POST['derbraz'])){$derbraz=$_POST['derbraz'];}else{ $derbraz='';}
if(isset($_POST['annbraz'])){$annbraz=$_POST['annbraz'];}else{ $annbraz='';}
if(isset($_POST['cconder'])){$cconder=$_POST['cconder'];}else{ $cconder='';}
if(isset($_POST['hombrod'])){$hombrod=$_POST['hombrod'];}else{ $hombrod='';}
if(isset($_POST['mandiderr'])){$mandiderr=$_POST['mandiderr'];}else{ $mandiderr='';}
if(isset($_POST['mandicentroo'])){$mandicentroo=$_POST['mandicentroo'];}else{ $mandicentroo='';}
if(isset($_POST['mandiizqui'])){$mandiizqui=$_POST['mandiizqui'];}else{ $mandiizqui='';}
if(isset($_POST['mejderecha'])){$mejderecha=$_POST['mejderecha'];}else{ $mejderecha='';}
if(isset($_POST['narizc'])){$narizc=$_POST['narizc'];}else{ $narizc='';}
if(isset($_POST['frenteizquierda'])){$frenteizquierda=$_POST['frenteizquierda'];}else{ $frenteizquierda='';}
if(isset($_POST['frentederecha'])){$frentederecha=$_POST['frentederecha'];}else{ $frentederecha='';}

if(isset($_POST['nuevo1'])){$nuevo1=$_POST['nuevo1'];}else{$nuevo1='';}
if(isset($_POST['nuevo2'])){$nuevo2=$_POST['nuevo2'];}else{$nuevo2='';}
if(isset($_POST['nuevo3'])){$nuevo3=$_POST['nuevo3'];}else{$nuevo3='';}
if(isset($_POST['nuevo4'])){$nuevo4=$_POST['nuevo4'];}else{$nuevo4='';}
if(isset($_POST['nuevo5'])){$nuevo5=$_POST['nuevo5'];}else{$nuevo5='';}
if(isset($_POST['nuevo6'])){$nuevo6=$_POST['nuevo6'];}else{$nuevo6='';}
if(isset($_POST['nuevo7'])){$nuevo7=$_POST['nuevo7'];}else{$nuevo7='';}
if(isset($_POST['nuevo8'])){$nuevo8=$_POST['nuevo8'];}else{$nuevo8='';}













 
//espalda
if(isset($_POST['plantapiea'])){$plantapiea=$_POST['plantapiea'];}else{ $plantapiea='';}
if(isset($_POST['plantapieader'])){$plantapieader=$_POST['plantapieader'];}else{ $plantapieader='';}
if(isset($_POST['tobilloati'])){$tobilloati=$_POST['tobilloati'];}else{ $tobilloati='';}
if(isset($_POST['tobilloatd'])){$tobilloatd=$_POST['tobilloatd'];}else{ $tobilloatd='';}
if(isset($_POST['ptiatras'])){$ptiatras=$_POST['ptiatras'];}else{ $ptiatras='';}
if(isset($_POST['ptdatras'])){$ptdatras=$_POST['ptdatras'];}else{ $ptdatras='';}
if(isset($_POST['pierespaldai'])){$pierespaldai=$_POST['pierespaldai'];}else{ $pierespaldai='';}
if(isset($_POST['pierespaldad'])){$pierespaldad=$_POST['pierespaldad'];}else{ $pierespaldad='';}
if(isset($_POST['musloatrasiz'])){$musloatrasiz=$_POST['musloatrasiz'];}else{ $musloatrasiz='';}
if(isset($_POST['musloatrasder'])){$musloatrasder=$_POST['musloatrasder'];}else{ $musloatrasder='';}
if(isset($_POST['glutiz'])){$glutiz=$_POST['glutiz'];}else{ $glutiz='';}
if(isset($_POST['glutder'])){$glutder=$_POST['glutder'];}else{ $glutder='';}
if(isset($_POST['cinturaiz'])){$cinturaiz=$_POST['cinturaiz'];}else{ $cinturaiz='';}
if(isset($_POST['cinturader'])){$cinturader=$_POST['cinturader'];}else{ $cinturader='';}
if(isset($_POST['costilliz'])){$costilliz=$_POST['costilliz'];}else{ $costilliz='';}
if(isset($_POST['costillder'])){$costillder=$_POST['costillder'];}else{ $costillder='';}


if(isset($_POST['espaldarribaiz'])){$espaldarribaiz=$_POST['espaldarribaiz'];}else{ $espaldarribaiz='';}
if(isset($_POST['espaldaarribader'])){$espaldaarribader=$_POST['espaldaarribader'];}else{ $espaldaarribader='';}
if(isset($_POST['espaldaalta'])){$espaldaalta=$_POST['espaldaalta'];}else{ $espaldaalta='';}
if(isset($_POST['dorsaliz'])){$dorsaliz=$_POST['dorsaliz'];}else{ $dorsaliz='';}
if(isset($_POST['dorsalder'])){$dorsalder=$_POST['dorsalder'];}else{ $dorsalder='';}
if(isset($_POST['munecaatrasiz'])){$munecaatrasiz=$_POST['munecaatrasiz'];}else{ $munecaatrasiz='';}
if(isset($_POST['munecaatrasder'])){$munecaatrasder=$_POST['munecaatrasder'];}else{ $munecaatrasder='';}
if(isset($_POST['antebiesp'])){$antebiesp=$_POST['antebiesp'];}else{ $antebiesp='';}
if(isset($_POST['antebdesp'])){$antebdesp=$_POST['antebdesp'];}else{ $antebdesp='';}
if(isset($_POST['casicodoi'])){$casicodoi=$_POST['casicodoi'];}else{ $casicodoi='';}
if(isset($_POST['casicododer'])){$casicododer=$_POST['casicododer'];}else{ $casicododer='';}
if(isset($_POST['brazalti'])){$brazalti=$_POST['brazalti'];}else{ $brazalti='';}
if(isset($_POST['brazaltder'])){$brazaltder=$_POST['brazaltder'];}else{ $brazaltder='';}

if(isset($_POST['cuellatrasb'])){$cuellatrasb=$_POST['cuellatrasb'];}else{ $cuellatrasb='';}
if(isset($_POST['cuellatrasmedio'])){$cuellatrasmedio=$_POST['cuellatrasmedio'];}else{ $cuellatrasmedio='';}
if(isset($_POST['cabedorsalm'])){$cabedorsalm=$_POST['cabedorsalm'];}else{ $cabedorsalm='';}
if(isset($_POST['cabealtaizqu'])){$cabealtaizqu=$_POST['cabealtaizqu'];}else{ $cabealtaizqu='';}
if(isset($_POST['cabezaaltader'])){$cabezaaltader=$_POST['cabezaaltader'];}else{ $cabezaaltader='';}

if(isset($_POST['espizq'])){$espizq=$_POST['espizq'];}else{ $espizq='';}
if(isset($_POST['espder'])){$espder=$_POST['espder'];}else{ $espder='';}
if(isset($_POST['coxis'])){$coxis=$_POST['coxis'];}else{ $coxis='';}


//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("d-m-Y H:i:s");

if(isset($_POST['p_medico'])){$p_medico=$_POST['p_medico'];}else{ $p_medico='';}
if(isset($_POST['dispo_p'])){$dispo_p=$_POST['dispo_p'];}else{ $dispo_p='';}
if(isset($_POST['diagyodc'])){$diagyodc=$_POST['diagyodc'];}else{ $diagyodc='';}
if(isset($_POST['p_anato'])){$p_anato=$_POST['p_anato'];}else{ $p_anato='';}
if(isset($_POST['tipo_de_i'])){$tipo_de_i=$_POST['tipo_de_i'];}else{ $tipo_de_i='';}
if(isset($_POST['sitio_ob'])){$sitio_ob=$_POST['sitio_ob'];}else{ $sitio_ob='';}
if(isset($_POST['estudios_obser'])){$estudios_obser=$_POST['estudios_obser'];}else{ $estudios_obser='';}

if(isset($_POST['p_a'])){$p_a=$_POST['p_a'];}else{ $p_a='';}
if(isset($_POST['s_a'])){$s_a=$_POST['s_a'];}else{ $s_a='';}
if(isset($_POST['t_a'])){$t_a=$_POST['t_a'];}else{ $t_a='';}

if(isset($_POST['otros_asep'])){$otros_asep=$_POST['otros_asep'];}else{$otros_asep='';}
if(isset($_POST['cir_prog'])){$cir_prog=$_POST['cir_prog'];}else{$cir_prog='';}

$ingresarsignos = mysqli_query($conexion, 'INSERT INTO signos_vitales (id_atencion,id_usua,fecha,p_sistol,p_diastol,fcard,fresp,temper) values (' . $id_atencion . ' , ' . $id_usua . ',"'.$fecha_actual.'","' . $psist . '","' . $pdiast . '","' . $f_card . '","' . $f_resp . '","' . $temp . '")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
echo mysqli_query($conexion,$ingresarsignos);
if($dispo_p=="estudio patologico"){


	$ingresarestudios = mysqli_query($conexion, 'INSERT INTO notificaciones_pato (id_atencion,id_usua,fecha,hora,p_medico,dispo_p,diagyodc,p_anato,tipo_de_i,sitio_ob,estudios_obser,fech_estno) values (' . $id_atencion . ' , ' . $id_usua . ',"'.$fecha.'","' . $hora . '","' . $p_medico . '","' . $dispo_p . '","' . $diagyodc . '","' . $p_anato . '","' . $tipo_de_i . '","' . $sitio_ob . '","' . $estudios_obser . '","'.$fecha_actual.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
echo mysqli_query($conexion,$ingresarestudios);
$ingresar = mysqli_query($conexion, 'INSERT INTO enf_quirurgico (id_atencion,id_usua,fecha,hora,in_isq,ter_isq,in_ren,ter_ren,elect,pos,ant,areaquir,tip_cir,pipat,pdiast,psist,f_card,f_resp,temp,spo2,diu,eva,sang,vom,aspboc,gast,dren,egpar_t,caidas,medi,defic,estement,deamb,total,classresg,nom_enf,interv_m,quem_m,uls_m,nec_m,her_m,tub_m,der_m,ras_m,eq_m,enf_m,ema_m,frac_m,acc_m,pete_m,ede_m,fron_m,mal_m,man_m,del_m,pec_m,est_m,ant_m,mu_m,mano_m,mus_m,rod_m,pier_m,pri_m,max_m,men_m,ac_m,bra_m,pli_m,abd_m,reg_m,pub_m,pde_m,sde_m,tde_m,cde_m,qde_m,tob_m,pie_m,par_m,occ_m,nuca_m,braz_m,codo_m,ante_m,mune_m,mane_m,plieg_m,piern_m,piep_m,cuello_m,regin_m,regesc_m,reginf_m,lum_m,glut_m,musl_m,talon_m,ingrecup,dol1,dol2,dol3,dol4,dol5,egrecup,medol,oxi,con,muc,vent,est,cito,yeso,herquir,quema,ext,riesg,prec,dena,bloq,movil,const,not_preop,nom_enf_preop,not_trans,nom_enf_trans,not_post,nom_enf_post,tipan,nom_enf_tipan,viapar,hemod,glice,imagen,incidentes,mara,marb,marc,mard,mare,marf,marg,marh,pie,pied,tod,toi,rodi,rodd,musloi,muslod,inglei,iabdomen,ddi,ddidos,dditres,ddicuatro,ddicinco,ddoderu,palmai,munei,brazi,brazci,homi,peci,pecti,pectd,cvi,dedodos,dedotres,dedocuatro,dedocincoo,palmad,munecad,derbraz,annbraz,cconder,hombrod,mandiderr,mandicentroo,mandiizqui,mejderecha,narizc,frenteizquierda,frentederecha,plantapiea,plantapieader,tobilloati,tobilloatd,ptiatras,ptdatras,pierespaldai,pierespaldad,musloatrasiz,musloatrasder,glutiz,glutder,cinturaiz,cinturader,costilliz,costillder,espaldarribaiz,espaldaarribader,espaldaalta,dorsaliz,dorsalder,munecaatrasiz,munecaatrasder,antebiesp,antebdesp,casicodoi,casicododer,brazalti,brazaltder,cuellatrasb,cuellatrasmedio,cabedorsalm,cabealtaizqu,cabezaaltader,egotro,p_medico,dispo_p,diagyodc,p_anato,tipo_de_i,sitio_ob,estudios_obser,p_a,s_a,t_a,nuevo1,nuevo2,nuevo3,nuevo4,nuevo5,nuevo6,nuevo7,nuevo8,espizq,espder,coxis,asepsia,horac,horaas,otros_asep,cir_prog) values (' . $id_atencion . ' ,' . $id_usua . ',"'.$fecha_reporte.'","'.$hora.'","'.$in_isq.'","'.$ter_isq.'","'.$in_ren.'","'.$ter_ren.'","'.$elect.'","'.$pos.'","'.$ant.'","'.$areaquir.'","'.$tip_cir.'","'.$pipat.'","'.$pdiast.'","'.$psist.'","'.$f_card.'","'.$f_resp.'","'.$temp.'","'.$spo2.'","'.$diu.'","'.$eva.'","'.$sang.'","'.$vom.'","'.$aspboc.'","'.$gast.'","'.$dren.'","'.$egpar_t.'","'.$caidas.'","'.$medi.'","'.$defic.'","'.$estement.'","'.$deamb.'","'.$total.'","'.$classresg.'","'.$nom_enf.'","'.$interv_m.'","'.$quem_m.'","'.$uls_m.'","'.$nec_m.'","'.$her_m.'","'.$tub_m.'","'.$der_m.'","'.$ras_m.'","'.$eq_m.'","'.$enf_m.'","'.$ema_m.'","'.$frac_m.'","'.$acc_m.'","'.$pete_m.'","'.$ede_m.'","'.$fron_m.'","'.$mal_m.'","'.$man_m.'","'.$del_m.'","'.$pec_m.'","'.$est_m.'","'.$ant_m.'","'.$mu_m.'","'.$mano_m.'","'.$mus_m.'","'.$rod_m.'","'.$pier_m.'","'.$pri_m.'","'.$max_m.'","'.$men_m.'","'.$ac_m.'","'.$bra_m.'","'.$pli_m.'","'.$abd_m.'","'.$reg_m.'","'.$pub_m.'","'.$pde_m.'","'.$sde_m.'","'.$tde_m.'","'.$cde_m.'","'.$qde_m.'","'.$tob_m.'","'.$pie_m.'","'.$par_m.'","'.$occ_m.'","'.$nuca_m.'","'.$braz_m.'","'.$codo_m.'","'.$ante_m.'","'.$mune_m.'","'.$mano2_m.'","'.$plieg_m.'","'.$piern_m.'","'.$piep_m.'","'.$cuello_m.'","'.$regin_m.'","'.$regesc_m.'","'.$reginf_m.'","'.$lum_m.'","'.$glut_m.'","'.$musl_m.'","'.$talon_m.'","'.$ingrecup.'","'.$dol1.'","'.$dol2.'","'.$dol3.'","'.$dol4.'","'.$dol5.'","'.$egrecup.'","'.$medol.'","'.$oxi.'","'.$con.'","'.$muc.'","'.$vent.'","'.$est.'","'.$cito.'","'.$yeso.'","'.$herquir.'","'.$quema.'","'.$ext.'","'.$riesg.'","'.$prec.'","'.$dena.'","'.$bloq.'","'.$movil.'","'.$const.'","'.$not_preop.'","'.$nom_enf_preop.'","'.$not_trans.'","'.$nom_enf_trans.'","'.$not_post.'","'.$nom_enf_post.'","'.$tipan.'","'.$nom_enf_tipan.'","'.$viapar.'","'.$hemod.'","'.$glice.'","'.$imagen.'","'.$incidentes.'","' . $mara .'","' . $marb .'","' . $marc .'","' . $mard .'","' . $mare .'","' . $marf .'","' . $marg .'","' . $marh .'","' . $pie .'","' . $pied .'","' . $tod .'","' . $toi .'","' . $rodi .'","' . $rodd .'","' . $musloi .'","' . $muslod .'","' . $inglei .'","' . $iabdomen .'","' . $ddi .'","' . $ddidos .'","' . $dditres .'","' . $ddicuatro .'","' . $ddicinco .'","' . $ddoderu .'","' . $palmai .'","' . $munei .'","' . $brazi .'","' . $brazci .'","' . $homi .'","' . $peci .'","' . $pecti .'","' . $pectd .'","' . $cvi .'","' . $dedodos .'","' . $dedotres .'","' . $dedocuatro .'","' . $dedocincoo .'","' . $palmad .'","' . $munecad .'","' . $derbraz .'","' . $annbraz .'","' . $cconder .'","' . $hombrod .'","' . $mandiderr .'","' . $mandicentroo .'","' . $mandiizqui .'","' . $mejderecha .'","' . $narizc .'","' . $frenteizquierda .'","' . $frentederecha .'","' . $plantapiea .'","' . $plantapieader .'","' . $tobilloati .'","' . $tobilloatd .'","' . $ptiatras .'","' . $ptdatras .'","' . $pierespaldai .'","' . $pierespaldad .'","' . $musloatrasiz .'","' . $musloatrasder .'","' . $glutiz .'","' . $glutder .'","' . $cinturaiz .'","' . $cinturader .'","' . $costilliz .'","' . $costillder .'","' . $espaldarribaiz .'","' . $espaldaarribader .'","' . $espaldaalta .'","' . $dorsaliz .'","' . $dorsalder .'","' . $munecaatrasiz .'","' . $munecaatrasder .'","' . $antebiesp .'","' . $antebdesp .'","' . $casicodoi .'","' . $casicododer .'","' . $brazalti .'","' . $brazaltder .'","' . $cuellatrasb .'","' . $cuellatrasmedio .'","' . $cabedorsalm .'","' . $cabealtaizqu .'","' . $cabezaaltader .'","' . $egotro .'","' . $p_medico . '","' . $dispo_p . '","' . $diagyodc . '","' . $p_anato . '","' . $tipo_de_i . '","' . $sitio_ob . '","' . $estudios_obser . '","' . $p_a . '","' . $s_a . '","' . $t_a . '","' . $nuevo1 . '","' . $nuevo2 . '","' . $nuevo3 . '","' . $nuevo4 . '","' . $nuevo5 . '","' . $nuevo6 . '","' . $nuevo7 . '","' . $nuevo8 . '","' . $espizq . '","' . $espder . '","' . $coxis . '","' . $asepsia . '","' . $horac . '","' . $horaas . '","' . $otros_asep . '","' . $cir_prog . '") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
//,"'.$fecha_actual.'","'.$insul.'","'.$cist.'","'.$dispo.'","'.$cal.'","'.$qins.'","'.$viapar.'"

$sql2 = "UPDATE dat_ingreso SET area = 'HOSPITALIZACION' WHERE id_atencion = $id_atencion";
        $result = $conexion->query($sql2);

/*if(isset($_POST['des_quir'])){
      	$id_cam = $_POST['des_quir'];
          //// update de  camas id_atencion
      $sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion= $id_atencion WHERE id = $id_cam";
      $result = $conexion->query($sql2);
    
  
  	$sql3 = "UPDATE dat_ingreso SET cama='1' WHERE id_atencion = $id_atencion";
      $result = $conexion->query($sql3);
      
}*/
echo mysqli_query($conexion,$ingresar);

$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='$id_usua'") or die($conexion->error);
$usuario = $_SESSION['login'];
while($f = mysqli_fetch_array($resultado)){   
     $id_us=$f['papell'];
}
$fechactr = date("Y-m-d H:i");
$ingresar2 = mysqli_query($conexion, 'INSERT INTO control_enf (nom_enf,id_usua,id_atencion,nota,fecha) values ("' . $id_us . '",' . $id_usua . ' ,' . $id_atencion . ',"Nota preoperatoria","' . $fechactr . '") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
echo mysqli_query($conexion,$ingresar2);

}else{
	$ingresar = mysqli_query($conexion, 'INSERT INTO enf_quirurgico (id_atencion,id_usua,fecha,hora,in_isq,ter_isq,in_ren,ter_ren,elect,pos,ant,areaquir,tip_cir,pipat,pdiast,psist,f_card,f_resp,temp,spo2,diu,eva,sang,vom,aspboc,gast,dren,egpar_t,caidas,medi,defic,estement,deamb,total,classresg,nom_enf,interv_m,quem_m,uls_m,nec_m,her_m,tub_m,der_m,ras_m,eq_m,enf_m,ema_m,frac_m,acc_m,pete_m,ede_m,fron_m,mal_m,man_m,del_m,pec_m,est_m,ant_m,mu_m,mano_m,mus_m,rod_m,pier_m,pri_m,max_m,men_m,ac_m,bra_m,pli_m,abd_m,reg_m,pub_m,pde_m,sde_m,tde_m,cde_m,qde_m,tob_m,pie_m,par_m,occ_m,nuca_m,braz_m,codo_m,ante_m,mune_m,mane_m,plieg_m,piern_m,piep_m,cuello_m,regin_m,regesc_m,reginf_m,lum_m,glut_m,musl_m,talon_m,ingrecup,dol1,dol2,dol3,dol4,dol5,egrecup,medol,oxi,con,muc,vent,est,cito,yeso,herquir,quema,ext,riesg,prec,dena,bloq,movil,const,not_preop,nom_enf_preop,not_trans,nom_enf_trans,not_post,nom_enf_post,tipan,nom_enf_tipan,viapar,hemod,glice,imagen,incidentes,mara,marb,marc,mard,mare,marf,marg,marh,pie,pied,tod,toi,rodi,rodd,musloi,muslod,inglei,iabdomen,ddi,ddidos,dditres,ddicuatro,ddicinco,ddoderu,palmai,munei,brazi,brazci,homi,peci,pecti,pectd,cvi,dedodos,dedotres,dedocuatro,dedocincoo,palmad,munecad,derbraz,annbraz,cconder,hombrod,mandiderr,mandicentroo,mandiizqui,mejderecha,narizc,frenteizquierda,frentederecha,plantapiea,plantapieader,tobilloati,tobilloatd,ptiatras,ptdatras,pierespaldai,pierespaldad,musloatrasiz,musloatrasder,glutiz,glutder,cinturaiz,cinturader,costilliz,costillder,espaldarribaiz,espaldaarribader,espaldaalta,dorsaliz,dorsalder,munecaatrasiz,munecaatrasder,antebiesp,antebdesp,casicodoi,casicododer,brazalti,brazaltder,cuellatrasb,cuellatrasmedio,cabedorsalm,cabealtaizqu,cabezaaltader,egotro,p_medico,dispo_p,diagyodc,p_anato,tipo_de_i,sitio_ob,estudios_obser,p_a,s_a,t_a,nuevo1,nuevo2,nuevo3,nuevo4,nuevo5,nuevo6,nuevo7,nuevo8,espizq,espder,coxis,asepsia,horac,horaas,otros_asep,cir_prog,nociru) values (' . $id_atencion . ' ,' . $id_usua . ',"'.$fecha.'","'.$hora.'","'.$in_isq.'","'.$ter_isq.'","'.$in_ren.'","'.$ter_ren.'","'.$elect.'","'.$pos.'","'.$ant.'","'.$areaquir.'","'.$tip_cir.'","'.$pipat.'","'.$pdiast.'","'.$psist.'","'.$f_card.'","'.$f_resp.'","'.$temp.'","'.$spo2.'","'.$diu.'","'.$eva.'","'.$sang.'","'.$vom.'","'.$aspboc.'","'.$gast.'","'.$dren.'","'.$egpar_t.'","'.$caidas.'","'.$medi.'","'.$defic.'","'.$estement.'","'.$deamb.'","'.$total.'","'.$classresg.'","'.$nom_enf.'","'.$interv_m.'","'.$quem_m.'","'.$uls_m.'","'.$nec_m.'","'.$her_m.'","'.$tub_m.'","'.$der_m.'","'.$ras_m.'","'.$eq_m.'","'.$enf_m.'","'.$ema_m.'","'.$frac_m.'","'.$acc_m.'","'.$pete_m.'","'.$ede_m.'","'.$fron_m.'","'.$mal_m.'","'.$man_m.'","'.$del_m.'","'.$pec_m.'","'.$est_m.'","'.$ant_m.'","'.$mu_m.'","'.$mano_m.'","'.$mus_m.'","'.$rod_m.'","'.$pier_m.'","'.$pri_m.'","'.$max_m.'","'.$men_m.'","'.$ac_m.'","'.$bra_m.'","'.$pli_m.'","'.$abd_m.'","'.$reg_m.'","'.$pub_m.'","'.$pde_m.'","'.$sde_m.'","'.$tde_m.'","'.$cde_m.'","'.$qde_m.'","'.$tob_m.'","'.$pie_m.'","'.$par_m.'","'.$occ_m.'","'.$nuca_m.'","'.$braz_m.'","'.$codo_m.'","'.$ante_m.'","'.$mune_m.'","'.$mano2_m.'","'.$plieg_m.'","'.$piern_m.'","'.$piep_m.'","'.$cuello_m.'","'.$regin_m.'","'.$regesc_m.'","'.$reginf_m.'","'.$lum_m.'","'.$glut_m.'","'.$musl_m.'","'.$talon_m.'","'.$ingrecup.'","'.$dol1.'","'.$dol2.'","'.$dol3.'","'.$dol4.'","'.$dol5.'","'.$egrecup.'","'.$medol.'","'.$oxi.'","'.$con.'","'.$muc.'","'.$vent.'","'.$est.'","'.$cito.'","'.$yeso.'","'.$herquir.'","'.$quema.'","'.$ext.'","'.$riesg.'","'.$prec.'","'.$dena.'","'.$bloq.'","'.$movil.'","'.$const.'","'.$not_preop.'","'.$nom_enf_preop.'","'.$not_trans.'","'.$nom_enf_trans.'","'.$not_post.'","'.$nom_enf_post.'","'.$tipan.'","'.$nom_enf_tipan.'","'.$viapar.'","'.$hemod.'","'.$glice.'","'.$imagen.'","'.$incidentes.'","' . $mara .'","' . $marb .'","' . $marc .'","' . $mard .'","' . $mare .'","' . $marf .'","' . $marg .'","' . $marh .'","' . $pie .'","' . $pied .'","' . $tod .'","' . $toi .'","' . $rodi .'","' . $rodd .'","' . $musloi .'","' . $muslod .'","' . $inglei .'","' . $iabdomen .'","' . $ddi .'","' . $ddidos .'","' . $dditres .'","' . $ddicuatro .'","' . $ddicinco .'","' . $ddoderu .'","' . $palmai .'","' . $munei .'","' . $brazi .'","' . $brazci .'","' . $homi .'","' . $peci .'","' . $pecti .'","' . $pectd .'","' . $cvi .'","' . $dedodos .'","' . $dedotres .'","' . $dedocuatro .'","' . $dedocincoo .'","' . $palmad .'","' . $munecad .'","' . $derbraz .'","' . $annbraz .'","' . $cconder .'","' . $hombrod .'","' . $mandiderr .'","' . $mandicentroo .'","' . $mandiizqui .'","' . $mejderecha .'","' . $narizc .'","' . $frenteizquierda .'","' . $frentederecha .'","' . $plantapiea .'","' . $plantapieader .'","' . $tobilloati .'","' . $tobilloatd .'","' . $ptiatras .'","' . $ptdatras .'","' . $pierespaldai .'","' . $pierespaldad .'","' . $musloatrasiz .'","' . $musloatrasder .'","' . $glutiz .'","' . $glutder .'","' . $cinturaiz .'","' . $cinturader .'","' . $costilliz .'","' . $costillder .'","' . $espaldarribaiz .'","' . $espaldaarribader .'","' . $espaldaalta .'","' . $dorsaliz .'","' . $dorsalder .'","' . $munecaatrasiz .'","' . $munecaatrasder .'","' . $antebiesp .'","' . $antebdesp .'","' . $casicodoi .'","' . $casicododer .'","' . $brazalti .'","' . $brazaltder .'","' . $cuellatrasb .'","' . $cuellatrasmedio .'","' . $cabedorsalm .'","' . $cabealtaizqu .'","' . $cabezaaltader .'","' . $egotro .'","' . $p_medico . '","' . $dispo_p . '","' . $diagyodc . '","' . $p_anato . '","' . $tipo_de_i . '","' . $sitio_ob . '","' . $estudios_obser . '","' . $p_a . '","' . $s_a . '","' . $t_a . '","' . $nuevo1 . '","' . $nuevo2 . '","' . $nuevo3 . '","' . $nuevo4 . '","' . $nuevo5 . '","' . $nuevo6 . '","' . $nuevo7 . '","' . $nuevo8 . '","' . $espizq . '","' . $espder . '","' . $coxis . '","' . $asepsia . '","' . $horac . '","' . $horaas . '","' . $otros_asep . '","' . $cir_prog . '","2") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
//,"'.$fecha_actual.'","'.$insul.'","'.$cist.'","'.$dispo.'","'.$cal.'","'.$qins.'","'.$viapar.'"



//$sql2 = "UPDATE dat_ingreso SET area = 'HOSPITALIZACION' WHERE id_atencion = $id_atencion";
        //$result = $conexion->query($sql2);

echo mysqli_query($conexion,$ingresar);
/*if(isset($_POST['des_quir'])){
      	$id_cam = $_POST['des_quir'];
          //// update de  camas id_atencion
      $sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion= $id_atencion WHERE id = $id_cam";
      $result = $conexion->query($sql2);
    
  
  	$sql3 = "UPDATE dat_ingreso SET cama='1' WHERE id_atencion = $id_atencion";
      $result = $conexion->query($sql3);
      
}*/


$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='$id_usua'") or die($conexion->error);
$usuario = $_SESSION['login'];
while($f = mysqli_fetch_array($resultado)){   
     $id_us=$f['papell'];
}

$fechactr = date("Y-m-d H:i");
$ingresar2 = mysqli_query($conexion, 'INSERT INTO control_enf (nom_enf,id_usua,id_atencion,nota,fecha) values ("' . $id_us . '",' . $id_usua . ' ,' . $id_atencion . ',"Nota preoperatoria","' . $fechactr . '") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
echo mysqli_query($conexion,$ingresar2);
}




