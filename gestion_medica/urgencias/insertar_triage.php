<?php
//se establece una conexion a la base de datos
include '../../conexionbd.php';
//si se han enviado datos
$id_usu = $_GET['id_usu'];

$diab= "";
$h_arterial= "";	
$enf_card_pulm= "";
$cancer= "";	
$emb= "";	
$otro= "";	

    if(isset($_POST['diab'])){
	$diab=$_POST['diab'];
	}else{
		$diab= "";
		
	}
	if(isset($_POST['h_arterial']) ){
	$h_arterial=$_POST['h_arterial'];
	}else{
		$h_arterial= "";	
	}

	if(isset($_POST['enf_card_pulm']) ){
	$enf_card_pulm=$_POST['enf_card_pulm'];
	}else{
		$enf_card_pulm= "";
	}
	if(isset($_POST['cancer']) ){
	$cancer=$_POST['cancer'];
	}else{
		$cancer= "";	
	}

	if(isset($_POST['emb']) ){
	$emb=$_POST['emb'];
	}else{
		$emb= "";	
	}
	if(isset($_POST['otro']) ){
	$otro=$_POST['otro'];
	}else{
		$otro= "";	
	}
	
if (isset($_POST['id_atencion'])and 
    isset($_POST['p_sistolica'])and 
isset($_POST['p_diastolica']) and 
isset($_POST['f_card']) and 
isset($_POST['f_resp']) and 
isset($_POST['temp']) and 
isset($_POST['sat_oxigeno']) and 
isset($_POST['peso']) and 
isset($_POST['talla']) and 
isset($_POST['niv_dolor'])and
isset($_POST['val_total']) and 
isset($_POST['edo_clin']) and 
isset($_POST['destino']) and
isset($_POST['urgencia'])){

$id_atencion=($_POST['id_atencion']);
$p_sistolica=($_POST['p_sistolica']);
$p_diastolica=($_POST['p_diastolica']);
$f_card=($_POST['f_card']);
$f_resp=($_POST['f_resp']);
$temp=($_POST['temp']);
$sat_oxigeno=($_POST['sat_oxigeno']);
$peso=($_POST['peso']);
$talla=($_POST['talla']);
$niv_dolor=($_POST['niv_dolor']);
/*
$diab=($_POST['diab']);
$h_arterial=($_POST['h_arterial']);
$enf_card_pulm=($_POST['enf_card_pulm']);
$cancer=($_POST['cancer']);
$emb=($_POST['emb']);*/
$otro=($_POST['otro']);
$val_total=($_POST['val_total']);
$edo_clin=($_POST['edo_clin']);
$imp_diag=($_POST['imp_diag']);
$destino=($_POST['destino']);
$urgencia=($_POST['urgencia']);
$id_cam = $_POST['habitacion'];




if(isset($_POST['adic_cu'])){
    $adic_cu = $_POST['adic_cu'];
  }else{
    $adic_cu = "";
  }

  if(isset($_POST['tab_cu'])){
    $tab_cu = $_POST['tab_cu'];
  }else{
    $tab_cu = "";
  }

  if(isset($_POST['alco_cu'])){
    $alco_cu = $_POST['alco_cu'];
  }else{
    $alco_cu = "";
  }

  

if(isset($_POST['quir_cu'])){
    $quir_cu = $_POST['quir_cu'];
}else{
    $quir_cu = "";
}

if(isset($_POST['aler_cu'])){
    $aler_cu = $_POST['aler_cu'];
}else{
    $aler_cu = "";
}

if(isset($_POST['trau_cu'])){
    $trau_cu = $_POST['trau_cu'];
}else{
        $trau_cu = "";
}

if(isset($_POST['trans_cu'])){
    $trans_cu = $_POST['trans_cu'];
}else{
        $trans_cu = "";
    }

    if(isset($_POST['diab_pa'])){
    $diab_pa = $_POST['diab_pa'];
}else{
        $diab_pa = "";
    }

    if(isset($_POST['diab_ma'])){
    $diab_ma = $_POST['diab_ma'];
}else{
        $diab_ma = "";
    }

    if(isset($_POST['diab_ab'])){
    $diab_ab = $_POST['diab_ab'];
}else{
        $diab_ab = "";
    }

 if(isset($_POST['hip_pa'])){
    $hip_pa = $_POST['hip_pa'];
}else{
        $hip_pa = "";
    }

 if(isset($_POST['hip_ma'])){
    $hip_ma = $_POST['hip_ma'];
}else{
        $hip_ma = "";
    }

     if(isset($_POST['hip_ab'])){
    $hip_ab = $_POST['hip_ab'];
}else{
        $hip_ab = "";
    }

    if(isset($_POST['can_pa'])){
    $can_pa = $_POST['can_pa'];
}else{
        $can_pa = "";
    }
    if(isset($_POST['can_ma'])){
    $can_ma = $_POST['can_ma'];
}else{
        $can_ma = "";
    }
   
    if(isset($_POST['can_ab'])){
    $can_ab = $_POST['can_ab'];
}else{
        $can_ab = "";
    }

        if(isset($_POST['hc_desc_hom'])){
    $hc_desc_hom = $_POST['hc_desc_hom'];
}else{
        $hc_desc_hom = "";
    }

// consulta
  if($_POST['diab_pa']==!null){

    //$Id_exp = ($_POST['Id_exp']);
    //  $fec_hc = ($_POST['fec_hc']);

if(isset($_POST['diab_pa'])){$diab_pa=$_POST['diab_pa'];}else{ $diab_pa='';}
if(isset($_POST['diab_ma'])){$diab_ma=$_POST['diab_ma'];}else{ $diab_ma='';}
if(isset($_POST['diab_ab'])){$diab_ab=$_POST['diab_ab'];}else{ $diab_ab='';}
if(isset($_POST['diab_pa'])){$diab_pa=$_POST['diab_pa'];}else{ $diab_pa='';}
if(isset($_POST['hip_pa'])){$hip_pa=$_POST['hip_pa'];}else{ $hip_pa='';}
if(isset($_POST['hip_ma'])){$hip_ma=$_POST['hip_ma'];}else{ $hip_ma='';}
if(isset($_POST['hip_ab'])){$hip_ab=$_POST['hip_ab'];}else{ $hip_ab='';}
if(isset($_POST['can_pa'])){$can_pa=$_POST['can_pa'];}else{ $can_pa='';}
if(isset($_POST['can_ma'])){$can_ma=$_POST['can_ma'];}else{ $can_ma='';}
if(isset($_POST['can_ab'])){$can_ab=$_POST['can_ab'];}else{ $can_ab='';}
if(isset($_POST['trans_cu'])){$trans_cu=$_POST['trans_cu'];}else{ $trans_cu='';}
if(isset($_POST['aler_cu'])){$aler_cu=$_POST['aler_cu'];}else{ $aler_cu='';}
if(isset($_POST['adic_cu'])){$adic_cu=$_POST['adic_cu'];}else{ $adic_cu='';}
if(isset($_POST['hc_men'])){$hc_men=$_POST['hc_men'];}else{ $hc_men='';}

if(isset($_POST['hc_ritmo'])){$hc_ritmo=$_POST['hc_ritmo'];}else{ $hc_ritmo='';}
if(isset($_POST['gestas_cu'])){$gestas_cu=$_POST['gestas_cu'];}else{ $gestas_cu='';}
if(isset($_POST['partos_cu'])){$partos_cu=$_POST['partos_cu'];}else{ $partos_cu='';}
if(isset($_POST['ces_cu'])){$ces_cu=$_POST['ces_cu'];}else{ $ces_cu='';} 

if(isset($_POST['abo_cu'])){$abo_cu=$_POST['abo_cu'];}else{ $abo_cu='';}
if(isset($_POST['fecha_fur'])){$fecha_fur=$_POST['fecha_fur'];}else{ $fecha_fur='';}
if(isset($_POST['proc_cu'])){$proc_cu=$_POST['proc_cu'];}else{ $proc_cu='';}
if(isset($_POST['dis_cu'])){$dis_cu=$_POST['dis_cu'];}else{ $dis_cu='';} 
if(isset($_POST['otro_cu'])){$otro_cu = $_POST['otro_cu'];}else{$otro_cu = "";}

   // $motcon_cu = ($_POST['motcon_cu']);
    $trau_cu = ($_POST['trau_cu']);
    
    $quir_cu = ($_POST['quir_cu']);
    $pad_cu = ($_POST['pad_cu']);
    $exp_cu = ($_POST['exp_cu']);
    $diag_cu = ($_POST['diag_cu']);
    $diag2 = ($_POST['diag2']);
$des_diag = ($_POST['des_diag']);
   
    $med_cu = ($_POST['med_cu']);
    $anproc_cu = ($_POST['anproc_cu']);
    $trat_cu = ($_POST['trat_cu']);
    $do_cu = ($_POST['do_cu']);
  
$edo_clin=($_POST['edo_clin']);

    //$dest_cu = ($_POST['dest_cu']);
    $despatol = ($_POST['despatol']);
    $resultado1 = $conexion->query("select paciente.*, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=id_atencion" ) or die($conexion->error);
   while ($f1 = mysqli_fetch_array($resultado1)) {
    $tipo_sangrey=$f1['tip_san'];
   }
  //date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
$ingresar6 = mysqli_query($conexion, 'insert into dat_c_urgen (id_atencion,diab_pa,diab_ma,diab_ab,hip_pa,hip_ma,hip_ab,can_pa,can_ma,can_ab,motcon_cu,trau_cu,trans_cu,adic_cu,tab_cu,alco_cu,otro_cu,quir_cu,aler_cu,pad_cu,exp_cu,diag_cu,hc_men,hc_ritmo,gestas_cu,partos_cu,ces_cu,abo_cu,fecha_fur,hc_desc_hom,proc_cu,med_cu,anproc_cu,trat_cu,do_cu,dis_cu,fecha_urgen,id_usua,despatol,diag2,des_diag) values ("'.$id_atencion.'"," ' . $diab_pa . '" ," ' . $diab_ma . '" ," ' . $diab_ab . '" ," ' . $hip_pa . '" ," ' . $hip_ma . '" ," ' . $hip_ab . '" , "' . $can_pa . '" ,"' . $can_ma . '" ,"' . $can_ab . '" ,"' . $edo_clin . '", "' . $trau_cu  . '" , "' . $trans_cu . '" , "' . $adic_cu . ' " ,"' . $tab_cu . ' ","' . $alco_cu . ' ","' . $otro_cu . ' ", "' . $quir_cu . ' " ,"' . $aler_cu . ' ", "' . $pad_cu . ' " , "' . $exp_cu . ' " , "' . $diag_cu . ' ", "' . $hc_men . ' " , "' . $hc_ritmo . ' ","' . $gestas_cu . ' ","' . $partos_cu . ' ","' . $ces_cu . ' ","' . $abo_cu . ' ","' . $fecha_fur . ' ","' . $hc_desc_hom . ' ", "' . $proc_cu . ' ","' . $med_cu . ' " , "' . $anproc_cu . ' " , "' . $trat_cu . ' " , "' . $do_cu . ' " , "' . $dis_cu . ' ","' . $fecha_actual . ' ", ' . $id_usu . ',"' . $despatol . '","' . $diag2 . '", "'.$des_diag.'" ) ')or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

$insertar=mysqli_query($conexion,'INSERT INTO recetaurgen(id_atencion,id_usua,receta_urgen,fecha_recurgen) values ('.$id_atencion.','.$id_usu.',"'.$med_cu.'","'.$fecha_actual.'")')  or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

//diagnosticos tabla
   $diag=mysqli_query($conexion,'insert into diag_pac(Id_exp,id_usua,diag_paciente,fecha) values ('.$id_atencion.','.$id_usu.',"'.$diag_cu.'","'.$fecha_actual.'")') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));
  }

// separador
//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");


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


 if(isset($_POST['sig'])){
    $p_sistolica=0;
    $p_diastolica=0;
    $f_card=0;
    $f_resp=0;
    $temp=0;
    $sat_oxigeno=0;
    }else{
        $p_sistolica=$_POST['p_sistolica'];
         $p_diastolica=$_POST['p_diastolica'];
         $f_card=$_POST['f_card'];
         $f_resp=$_POST['f_resp'];
         $temp=$_POST['temp'];
         $sat_oxigeno=$_POST['sat_oxigeno'];
    }

    $ingresar=mysqli_query($conexion,'insert into triage(id_atencion,p_sistolica,p_diastolica,f_card,f_resp,temp,sat_oxigeno,peso,talla,niv_dolor,diab,h_arterial,enf_card_pulm,cancer,emb,otro,val_total,edo_clin,imp_diag,urgencia,fecha_t,destino,id_usua) values
    ("'.$id_atencion.'","'.$p_sistolica.'","'.$p_diastolica.'","'.$f_card.'","'.$f_resp.'","'.$temp.'","'.$sat_oxigeno.'","'.$peso.'","'.$talla.'","'.$niv_dolor.'","'.$diab.'","'.$h_arterial.'","'.$enf_card_pulm.'","'.$cancer.'","'.$emb.'","'.$otro.'","'.$val_total.'","'.$edo_clin.'","'.$imp_diag.'","'.$urgencia.'","'.$fecha_actual.'","'.$destino.'",' . $id_usu . ')') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));

$select="SELECT * FROM triage order by id_triage DESC LIMIT 1";
    $result=$conexion->query($select);
    while ($row=$result->fetch_assoc()) {
    $id_triage=$row['id_triage'];
    }
$fecha_actual3 = date("Y-m-d H:i:s");
$fecha_actual2 = date("Y-m-d");
$ingresar=mysqli_query($conexion,'insert into signos_vitales(id_atencion,id_usua,fecha,p_sistol,p_diastol,fcard,fresp,temper,satoxi,peso,talla,niv_dolor,tipo,fecha_registro) values
    ("'.$id_atencion.'",' . $id_usu . ',"'.$fecha_actual2.'","'.$p_sistolica.'","'.$p_diastolica.'","'.$f_card.'","'.$f_resp.'","'.$temp.'","'.$sat_oxigeno.'","'.$peso.'","'.$talla.'","'.$niv_dolor.'","OBSERVACIÓN","'.$fecha_actual3.'")') or die ('<p>Error al registrar</p><br>'.mysqli_error($conexion));


    $ingresar=mysqli_query($conexion,'UPDATE dat_ingreso SET area = "'.$destino.'" WHERE dat_ingreso.id_atencion = "'.$id_atencion.'" ');


      if(isset($_POST['habitacion'])){
      	$id_cam = $_POST['habitacion'];
          //// update de  camas id_atencion
      $sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion= $id_atencion WHERE id = $id_cam";
      $result = $conexion->query($sql2);
    
  
  	$sql3 = "UPDATE dat_ingreso SET cama='1' WHERE id_atencion = $id_atencion";
      $result = $conexion->query($sql3);
      
}

$select="SELECT * FROM recetaurgen order by id_rec_urgen DESC LIMIT 1";
    $result=$conexion->query($select);
    while ($row=$result->fetch_assoc()) {
    $id_rec_urgen=$row['id_rec_urgen'];
    }

echo '<script >window.open("pdf_triage.php?tri='.$id_triage.'&id='.$id_atencion.'&id_med='.$id_usu.'&rec='.$id_rec_urgen.' " , "RECETA AMBULATORIA", "width=1000, height=1000")</script>'.
 '<script type="text/javascript">window.location.href ="vista_usuario_triage.php" ;</script>';
    //redireccion
 

}//si no se enviaron datos

else{
    header ('location: ./vista_usuario_triage.php');
}
?>

