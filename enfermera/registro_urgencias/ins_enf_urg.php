<?php
session_start();
include '../../conexionbd.php';
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];

//date_default_timezone_set('America/Guatemala');

$obse=$_POST['obse'];
$ex=$_POST['ex'];
$com=$_POST['com'];
$dtipo=$_POST['dtipo'];
$peri=$_POST['peri'];
$evac=$_POST['evac'];
$frecu=$_POST['frecu'];
$ulte=$_POST['ulte'];
$tipou=$_POST['tipou'];
$no=$_POST['no'];
$inst=$_POST['inst'];

$h_p=$_POST['h_p'];
$h_pd=$_POST['h_pd'];


                           
$tdieta=$_POST['dieta'];

if(isset($_POST['mucosa'])){$mucosa=$_POST['mucosa'];}else{ $mucosa='';}
if(isset($_POST['dientes'])){$dientes=$_POST['dientes'];}else{ $dientes='';}
 foreach($_POST['dientes'] as $dientesr) {
     $dientesra.= $dientesr.",";
    }
if(isset($_POST['cabeza'])){$cabeza=$_POST['cabeza'];}else{ $cabeza='';}
if(isset($_POST['orejas'])){$orejas=$_POST['orejas'];}else{ $orejas='';}
if(isset($_POST['ojos'])){$ojos=$_POST['ojos'];}else{ $ojos='';}
if(isset($_POST['higiene'])){$higiene=$_POST['higiene'];}else{ $higiene='';}
if(isset($_POST['col'])){$col=$_POST['col'];}else{ $col='';}
if(isset($_POST['datag'])){$datag=$_POST['datag'];}else{ $datag='';}
if(isset($_POST['dati'])){$dati=$_POST['dati'];}else{ $dati='';}
if(isset($_POST['cas'])){$cas=$_POST['cas'];}else{ $cas='';}
if(isset($_POST['rep'])){$rep=$_POST['rep'];}else{ $rep='';}
if(isset($_POST['penc'])){$penc=$_POST['penc'];}else{ $penc='';}
if(isset($_POST['agre'])){$agre=$_POST['agre'];}else{ $agre='';}
if(isset($_POST['origen'])){$origen=$_POST['origen'];}else{ $origen='';}
if(isset($_POST['aluc'])){$aluc=$_POST['aluc'];}else{ $aluc='';}
if(isset($_POST['tip'])){$tip=$_POST['tip'];}else{ $tip='';}
if(isset($_POST['idea'])){$idea=$_POST['idea'];}else{ $idea='';}
if(isset($_POST['ideah'])){$ideah=$_POST['ideah'];}else{ $ideah='';}
if(isset($_POST['edoan'])){$edoan=$_POST['edoan'];}else{ $edoan='';}


if(isset($_POST['res'])){$res=$_POST['res'];}else{ $res='';}
 foreach($_POST['res'] as $resc) {
     $rescr.= $resc.",";
    }


if(isset($_POST['disvo'])){$disvo=$_POST['disvo'];}else{ $disvo='';}
if(isset($_POST['abd'])){$abd=$_POST['abd'];}else{ $abd='';}
if(isset($_POST['ultev'])){$ultev=$_POST['ultev'];}else{ $ultev='';}

if(isset($_POST['uri'])){$uri=$_POST['uri'];}else{ $uri='';}
 foreach($_POST['uri'] as $urir) {
     $urira.= $urir.",";
    }


$eseva=$_POST['eseva'];
$tder=$_POST['tder'];
$tizq=$_POST['tizq'];
$sder=$_POST['sder'];
$sizq=$_POST['sizq'];
$solp=0;
$voral=0;
$totingreso=0;
$solp=$_POST['solp'];
$voral=$_POST['voral'];

$totingreso=$solp+$voral;
$vomi=0;
$orina=0;
$evc=0;
$ot=0;
$totegreso=0;
$vomi=$_POST['vomi'];
$orina=$_POST['orina'];
$evc=$_POST['evc'];
$ot=$_POST['ot'];

$totegreso=$vomi+$orina+$evc+$ot;


$balance=0;
$balance=$totingreso-$totegreso;


$notenf=$_POST['notenf'];
$tra=$_POST['tra'];
$hor=$_POST['hor'];
$entrega=$_POST['entrega'];
$pdi=$_POST['pdi'];
$psi=$_POST['psi'];
$fcu=$_POST['fcu'];
$spoo=$_POST['spoo'];
$edocia=$_POST['edocia'];
$reci=$_POST['reci'];

$hora_gp=$_POST['hora_gp'];
$glic=$_POST['glic'];

$hora_glas=$_POST['hora_glas'];

$apecular=$_POST['apecular'];
$rmotora=$_POST['rmotora'];
$rverbal=$_POST['rverbal'];


$totglas=$apecular+$rmotora+$rverbal;
      
$sol_es=$_POST['sol_es'];
$sol_gab=$_POST['sol_gab']; 
$datet=$_POST['datet'];   
$frere=$_POST['frere']; 
$tempera=$_POST['tempera'];  
$hrsignos=$_POST['hrsignos']; 
//$tipo=$_POST['tipo']; 

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
$observaciones=$_POST['observaciones'];


$espizq=$_POST['espizq'];
$espder=$_POST['espder'];
$coxis=$_POST['coxis'];

$nuevo=$_POST['nuevo']; 
$nuevo1=$_POST['nuevo1']; 
$nuevo2=$_POST['nuevo2']; 
$nuevo3=$_POST['nuevo3']; 
$nuevo4=$_POST['nuevo4']; 
$nuevo5=$_POST['nuevo5']; 
$nuevo6=$_POST['nuevo6']; 
$nuevo7=$_POST['nuevo7'];

$hora_estoma=$_POST['hora_estoma']; 
$estoma=$_POST['estoma'];


//date_default_timezone_set('America/Guatemala');
$fe = date("Y-m-d H:i:s");

$ingresar = mysqli_query($conexion, 'INSERT INTO enf_reg_urg (id_atencion,id_usua,tdieta,mucosa,dientes,cabeza,orejas,ojos,higiene,col,obse,datag,dati,ex,cas,rep,penc,agre,origen,aluc,tip,idea,ideah,edoan,com,res,disvo,dtipo,peri,evac,abd,frecu,ulte,ultev,uri,tipou,no,inst,eseva,tder,tizq,sder,sizq,hora_gp,glic,solp,voral,totingreso,vomi,orina,evc,ot,totegreso,balance,notenf,tra,hor,entrega,pdi,psi,fcu,spoo,edocia,reci,enfur_fecha,datet,frere,sol_es,tempera,sol_gab,mara,marb,marc,mard,mare,marf,marg,marh,h_p,h_pd,pie,pied,tod,toi,rodi,rodd,musloi,muslod,inglei,iabdomen,ddi,ddidos,dditres,ddicuatro,ddicinco,ddoderu,palmai,muñei,brazi,brazci,homi,peci,pecti,pectd,cvi,dedodos,dedotres,dedocuatro,dedocincoo,palmad,muñecad,derbraz,annbraz,cconder,hombrod,mandiderr,mandicentroo,mandiizqui,mejderecha,narizc,frenteizquierda,frentederecha,plantapiea,plantapieader,tobilloati,tobilloatd,ptiatras,ptdatras,pierespaldai,pierespaldad,musloatrasiz,musloatrasder,glutiz,glutder,cinturaiz,cinturader,costilliz,costillder,espaldarribaiz,espaldaarribader,espaldaalta,dorsaliz,dorsalder,munecaatrasiz,munecaatrasder,antebiesp,antebdesp,casicodoi,casicododer,brazalti,brazaltder,cuellatrasb,cuellatrasmedio,cabedorsalm,cabealtaizqu,cabezaaltader,observaciones,espizq,espder,coxis,nuevo,nuevo1,nuevo2,nuevo3,nuevo4,nuevo5,nuevo6,nuevo7,hora_estoma,estoma) values (' . $id_atencion . ',' . $id_usua .',"' . $tdieta .'","' . $mucosa .'","' . $dientesra .'","' . $cabeza .'","' . $orejas .'","' . $ojos .'","' . $higiene .'","' . $col .'","' . $obse .'","' . $datag .'","' . $dati .'","' . $ex .'","' . $cas .'","' . $rep .'","' . $penc .'","' . $agre .'","' . $origen .'","' . $aluc .'","' . $tip .'","' . $idea .'","' . $ideah .'","' . $edoan .'","' . $com .'","' . $rescr .'","' . $disvo .'","' . $dtipo .'","' . $peri .'","' . $evac .'","' . $abd .'","' . $frecu .'","' . $ulte .'","' . $ultev .'","' . $urira .'","' . $tipou .'","' . $no .'","' . $inst .'","' . $eseva .'","' . $tder .'","' . $tizq .'","' . $sder .'","' . $sizq .'","' . $hora_gp .'","' . $glic .'","' . $solp .'","' . $voral .'","' . $totingreso .'","' . $vomi .'","' . $orina .'","' . $evc .'","' . $ot .'","' . $totegreso .'","' . $balance .'","' . $notenf .'","' . $tra .'","' . $hor .'","' . $entrega .'","' . $pdi .'","' . $psi .'","' . $fcu .'","' . $spoo .'","' . $edocia .'","' . $reci .'","' . $fe .'","' . $datet .'","' . $frere .'","' . $sol_es .'","' . $tempera .'","' . $sol_gab .'","' . $mara .'","' . $marb .'","' . $marc .'","' . $mard .'","' . $mare .'","' . $marf .'","' . $marg .'","' . $marh .'","' . $h_p .'","' . $h_pd .'","' . $pie .'","' . $pied .'","' . $tod .'","' . $toi .'","' . $rodi .'","' . $rodd .'","' . $musloi .'","' . $muslod .'","' . $inglei .'","' . $iabdomen .'","' . $ddi .'","' . $ddidos .'","' . $dditres .'","' . $ddicuatro .'","' . $ddicinco .'","' . $ddoderu .'","' . $palmai .'","' . $muñei .'","' . $brazi .'","' . $brazci .'","' . $homi .'","' . $peci .'","' . $pecti .'","' . $pectd .'","' . $cvi .'","' . $dedodos .'","' . $dedotres .'","' . $dedocuatro .'","' . $dedocincoo .'","' . $palmad .'","' . $muñecad .'","' . $derbraz .'","' . $annbraz .'","' . $cconder .'","' . $hombrod .'","' . $mandiderr .'","' . $mandicentroo .'","' . $mandiizqui .'","' . $mejderecha .'","' . $narizc .'","' . $frenteizquierda .'","' . $frentederecha .'","' . $plantapiea .'","' . $plantapieader .'","' . $tobilloati .'","' . $tobilloatd .'","' . $ptiatras .'","' . $ptdatras .'","' . $pierespaldai .'","' . $pierespaldad .'","' . $musloatrasiz .'","' . $musloatrasder .'","' . $glutiz .'","' . $glutder .'","' . $cinturaiz .'","' . $cinturader .'","' . $costilliz .'","' . $costillder .'","' . $espaldarribaiz .'","' . $espaldaarribader .'","' . $espaldaalta .'","' . $dorsaliz .'","' . $dorsalder .'","' . $munecaatrasiz .'","' . $munecaatrasder .'","' . $antebiesp .'","' . $antebdesp .'","' . $casicodoi .'","' . $casicododer .'","' . $brazalti .'","' . $brazaltder .'","' . $cuellatrasb .'","' . $cuellatrasmedio .'","' . $cabedorsalm .'","' . $cabealtaizqu .'","' . $cabezaaltader .'","' . $observaciones .'","' . $espizq .'","' . $espder .'","' . $coxis .'","' . $nuevo .'","' . $nuevo1 .'","' . $nuevo2 .'","' . $nuevo3 .'","' . $nuevo4 .'","' . $nuevo5 .'","' . $nuevo6 .'","' . $nuevo7 .'","' . $hora_estoma .'","' . $estoma .'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

  $resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=$id_atencion ORDER by id_sig DESC LIMIT 1") or die($conexion->error);
    while ($f5 = mysqli_fetch_array($resultado5)) {
 $peso=$f5['peso'];
  $talla=$f5['talla'];
}























header('location: ../lista_pacientes/vista_pac_enf.php');


