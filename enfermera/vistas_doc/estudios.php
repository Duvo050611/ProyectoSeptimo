<?php
session_start();


include "../../conexionbd.php";
include("../header_enfermera.php");
if (isset($_SESSION['pac'])) { //si existe el id mandado por metodo GET, se hara la consulta donde el id debe ser igual al id mandado por el metodo GET
    $resultado = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp  WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);
    if (mysqli_num_rows($resultado) > 0) { //se mostrara si existe mas de 1
        $f = mysqli_fetch_row($resultado);

    } else {
        header("Location: ./consulta_urgencias.php"); //te regresa a la página principal
    }
} else {
    header("Location: ./consulta_urgencias.php"); //te regresa a la página principal
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>

    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="jquery-3.1.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>



    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>

<?php  function calculaedad($fechanacimiento)
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
}?>

    <title>NOTA DE EGRESO</title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
         
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
  <center><strong>RESULTADOS DE ESTUDIOS</strong></center><p>
</div>        

<?php

    include "../../conexionbd.php";

    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];

      $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.folio, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

      $result_pac = $conexion->query($sql_pac);

      while ($row_pac = $result_pac->fetch_assoc()) {
        $pac_papell = $row_pac['papell'];
        $pac_sapell = $row_pac['sapell'];
        $pac_nom_pac = $row_pac['nom_pac'];
        $pac_dir = $row_pac['dir'];
        $pac_id_edo = $row_pac['id_edo'];
        $pac_id_mun = $row_pac['id_mun'];
        $pac_tel = $row_pac['tel'];
        $pac_fecnac = $row_pac['fecnac'];
        $pac_fecing = $row_pac['fecha'];
        $pac_tip_sang = $row_pac['tip_san'];
        $pac_sexo = $row_pac['sexo'];
        $area = $row_pac['area'];
        $alta_med = $row_pac['alta_med'];
        $id_exp = $row_pac['Id_exp'];
        $folio = $row_pac['folio'];
        $alergias = $row_pac['alergias'];
      }

      $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_now = $conexion->query($sql_now);

      while ($row_now = $result_now->fetch_assoc()) {
        $dat_now = $row_now['dat_now'];
      }

      $sql_est = "SELECT DATEDIFF( '$dat_now' , fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
        $estancia = $row_est['estancia'];
      }


    ?>

  <div class="row">
    <div class="col-sm-2">
      Expediente: <strong><?php echo $folio?> </strong>
    </div>
    <div class="col-sm-6">
     Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm-4">
      Fecha de ingreso: <strong><?php echo date_format($date, "d/m/Y H:i:s") ?></strong>
    </div>
  </div>

     
  <div class="row">
    <div class="col-sm-4">
       <?php $date1 = date_create($pac_fecnac);
   ?>
    <!-- INICIO DE FUNCION DE CALCULAR EDAD -->
<?php 

function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d");
$fecha_nac=$pac_fecnac;
$fecha_de_nacimiento =strval($fecha_nac);

// separamos en partes las fechas
$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
$array_actual = explode ( "-", $fecha_actual );

$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días

//ajuste de posible negativo en $días
if ($dias < 0)
{
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
    switch ($array_actual[1]) {
           case 1:     $dias_mes_anterior=31; break;
           case 2:     $dias_mes_anterior=31; break;
           case 3:     
               if (bisiesto($array_actual[0]))
                {
                    $dias_mes_anterior=29; break;
                } else {
                    $dias_mes_anterior=28; break;
                }
               
           case 4:     $dias_mes_anterior=31; break;
           case 5:     $dias_mes_anterior=30; break;
           case 6:     $dias_mes_anterior=31; break;
           case 7:     $dias_mes_anterior=30; break;
           case 8:     $dias_mes_anterior=31; break;
           case 9:     $dias_mes_anterior=31; break;
           case 10:     $dias_mes_anterior=30; break;
           case 11:     $dias_mes_anterior=31; break;
           case 12:     $dias_mes_anterior=30; break;
    }

    $dias=$dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses
if ($meses < 0)
{
    --$anos;
    $meses=$meses + 12;
}

//echo "<br>Tu edad es: $anos años con $meses meses y $dias días";


 ?>
 <!-- TERMINO DE FUNCION DE CALCULAR EDAD -->
      Fecha de nacimiento: <strong><?php echo date_format($date1, "d/m/Y") ?></strong>
    </div>
    <div class="col-sm-4">
      Edad: <strong><?php if($anos > "0" ){
   echo $anos." años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." días";
}
?></strong>
    </div>
    
   
      <div class="col-sm-2">
      Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    
  </div>

  <div class="row">
 <?php
$d="";
  $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i ASC LIMIT 1";
$result_motd = $conexion->query($sql_motd);
while ($row_motd = $result_motd->fetch_assoc()) {
    $d=$row_motd['diagprob_i'];
} ?>
<?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
$result_mot = $conexion->query($sql_mot);
while ($row_mot = $result_mot->fetch_assoc()) {
$m=$row_mot['motivo_atn'];
} ?>

<?php if ($d!=null) {
   echo '<div class="col-sm-8"> Diagnóstico: <strong>' . $d .'</strong></div>';
} else{
      echo '<div class="col-sm-8"> Motivo de atención: <strong>' . $m .'</strong></div>';
}?>
    <div class="col-sm">
      Días estancia: <strong><?php echo $estancia ?> Dias</strong>
    </div>
  </div>


  <div class="row">
    <div class="col-sm-4">
      Alergias: <strong><?php echo $alergias ?></strong>
    </div>
     <div class="col-sm-4">
      Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
$result_edo = $conexion->query($sql_edo);while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
    <div class="col-sm-3">
      Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
    </div>
  </div>

<?php $sql_edo = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_edo = $conexion->query($sql_edo);
while ($row_edo = $result_edo->fetch_assoc()) {
  $peso=$row_edo['peso'];
  $talla=$row_edo['talla'];
} 
if (!isset($peso)){
    $peso=0;
    $talla=0;
}?>
 
  <div class="row">
     <div class="col-sm-4">
      Peso: <strong><?php echo $peso ?></strong>
    </div>
    <div class="col-sm-3">
      Talla: <strong><?php echo $talla ?></strong>
    </div>
  </div>


</div>
        <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
      }
        ?>
    </div>
  </div>
</div>
<hr>
<div class="container">
    <div class="container">
<!--INICIO DE NOTA estudios LABO-->  
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
  <center><strong>ESTUDIOS DE LABORATORIO</strong></center><p>
</div>

       
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">

                    <thead class="thead" style="background-color: #2b2d7f; color:white;">
                    <tr>
                        <th>Fecha de solicitud</th>
                        <th>Solicitante</th>
                        <th>Estudios(s)</th>
                        <th>Resultado(s)</th>
                        <th>Fecha de resultado(s)</th>
                        <th>Atendió solicitud</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    include "../../conexionbd.php";

                    $usuario = $_SESSION['login'];
                    $id_usua_log= $usuario['id_usua'];
                    $id_atencion = $_SESSION['pac'];

                    $query_log = "SELECT * FROM reg_usuarios where id_usua= $id_usua_log ";
                    $result_log = $conexion->query($query_log);

                    while ($row_log = $result_log->fetch_assoc()) {
                        $pac_log = $row_log['papell'] . ' ' . $row_log['sapell'] . ' ' . $row_log['nombre'];
                    }


                    $query = "SELECT * FROM notificaciones_labo n, reg_usuarios u where n.realizado = 'SI' and n.id_atencion=$id_atencion and n.id_usua = u.id_usua ORDER BY fecha_resul DESC";
                    $result = $conexion->query($query);
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                    $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                    $result_pac = $conexion->query($query_pac);
                    $id_medico = $row['id_usua'];
                    $id_resp = $row['id_usua_resul'];
                    
                    $query_medi = "SELECT * FROM reg_usuarios where Id_usua = $id_medico";
                    $result_medi = $conexion->query($query_medi);

                    while ($row_medi = $result_medi->fetch_assoc()) {
                        $medico = $row_medi['papell'] . ' ' . $row_medi['sapell'];
                    }
                    $query_resp = "SELECT * FROM reg_usuarios where Id_usua = $id_resp";
                    $result_resp = $conexion->query($query_resp);

                    while ($row_resp = $result_resp->fetch_assoc()) {
                        $resp = $row_resp['papell'] . ' ' . $row_resp['sapell'];
                    }

                    while ($row_pac = $result_pac->fetch_assoc()) {
                        $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                }
                        $fecha_ord= date_create($row['fecha_ord']);
                        $fecha_res= date_create($row['fecha_resul']);
                        echo '<tr>'
                            . '<td >' . date_format($fecha_ord,'d/m/Y H:i a') . '</td>'
                            . '<td >' . $medico . '</td>'
                            . '<td > <a style="color: deepskyblue;><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> ' . $row['sol_estudios'] .'/ '. $row['det_labo'] . '</a></td>';


                        echo '<td class="fondo" style="color:white;"><center>'
                            . ' <a href="../../gestion_medica/estudios/ver_pdf.php?not_id=' . $row['not_id'] . '" title="Editar datos" class="btn btn-danger "><span class="fa fa-file-pdf-o" aria-hidden="true"></span></a>';
                        echo '</center></td>';
                        $no++;
                       echo  '<td >' . date_format($fecha_res,'d/m/Y H:i a') . '</td>'
                           . '<td >' . $resp .  '</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        
   
<!--TERMINO DE NOTA estudios LABO-->  
<hr>
<!--inicio DE NOTA estudios sangre-->  
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
  <center><strong>ESTUDIOS DE SANGRE</strong></center><p>
</div>     
 <div class="container">
     <div class="row">
          <table class="table table-bordered table-striped" id="mytable">

                    <thead class="thead" style="background-color: #2b2d7f; color:white;">
                    <tr>
                        <th>Fecha de solicitud</th>
                        <th>Solicitante</th>
                        <th>Producto(s)</th>
                        <th>Resultado(s)</th>
                        <th>Fecha de resultado(s)</th>
                        <th>Atendió solicitud</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    include "../../conexionbd.php";

                    $usuario = $_SESSION['login'];
                    $id_usua_log= $usuario['id_usua'];
                    $id_atencion = $_SESSION['hospital'];

                    $query_log = "SELECT * FROM reg_usuarios where id_usua= $id_usua_log ";
                    $result_log = $conexion->query($query_log);

                    while ($row_log = $result_log->fetch_assoc()) {
                        $pac_log = $row_log['papell'] . ' ' . $row_log['sapell'] . ' ' . $row_log['nombre'];
                    }


                    $query = "SELECT * FROM notificaciones_sangre n, reg_usuarios u where n.id_atencion=$id_atencion and n.realizado = 'SI' and n.id_usua = u.id_usua ORDER BY fecha_resul DESC";
                    $result = $conexion->query($query);
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                    
                    $id_medico = $row['id_usua'];
                    $id_resp = $row['id_usua_resul'];
                    
                    $query_medi = "SELECT * FROM reg_usuarios where Id_usua = $id_medico";
                    $result_medi = $conexion->query($query_medi);

                    while ($row_medi = $result_medi->fetch_assoc()) {
                        $medico = $row_medi['papell'] . ' ' . $row_medi['sapell'] ;
                    }
                    $query_resp = "SELECT * FROM reg_usuarios where Id_usua = $id_resp";
                    $result_resp = $conexion->query($query_resp);

                    while ($row_resp = $result_resp->fetch_assoc()) {
                        $resp = $row_resp['papell'] . ' ' . $row_resp['sapell'];
                    }




                    $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                    $result_pac = $conexion->query($query_pac);

                    while ($row_pac = $result_pac->fetch_assoc()) {
                        $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                }
                        $fecha_ord= date_create($row['fecha_ord']);
                        $fecha_res= date_create($row['fecha_resul']);
                        echo '<tr>'
                            . '<td >' . date_format($fecha_ord,'d/m/Y H:i a') . '</td>'
                            . '<td >' . $medico . '</td>'
                            . '<td ><a style="color: deepskyblue;"> ' . $row['sol_sangre'] . '</a></td>'
                            . '<td >' . $row['resultado'] . '</td>';

                        $no++;
                       echo  '<td >' . date_format($fecha_res,'d/m/Y H:i a') . '</td>'
                           . '<td >' . $resp .  '</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
     </div>
 </div>   
<!--TERMINO DE NOTA-->
<hr>
<!-- INICIO ESTUDIOS IMAGENOLOGIA-->
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
  <center><strong>ESTUDIOS DE IMAGENOLOGÍA</strong></center><p>
</div> 

       
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">
                    <thead class="thead" style="background-color: #2b2d7f; color:white;">
                    <tr>
                        <th>Fecha solicitud</th>
                        <th>Solicitante</th>
                        <th>Estudio(s)</th>
                        
                        <th>Ver estudio(s)</th>
                        
                        <th>Ver informe</th>
                        <th>Fecha resultado(s)</th>
                        <th>Atendió solicitud</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    include "../../conexionbd.php";
                    
                    $usuario = $_SESSION['login'];
                    $id_usua_log= $usuario['id_usua'];

                    $query_log = "SELECT * FROM reg_usuarios where id_usua= $id_usua_log ";
                    $result_log = $conexion->query($query_log);

                    while ($row_log = $result_log->fetch_assoc()) {
                        $pac_log = $row_log['papell'] . ' ' . $row_log['sapell'] . ' ' . $row_log['nombre'];
                    }


         
  $query = "SELECT * FROM notificaciones_imagen n, reg_usuarios u ,cat_servicios c where n.realizado = 'SI'and n.id_usua = u.id_usua and  c.id_serv= n.not_id and n.id_atencion=$id_atencion
  ORDER BY fecha_resul DESC";
                    $result = $conexion->query($query);
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                     $not_id = $row['not_id'];
                     $habi = $row['habitacion'];
                     $id_atencion = $row['id_atencion'];
                     $interpretado = $row['interpretado'];
                     $pac_imagen = $row['sol_estudios'];
                     $id_usua_resul = $row['id_usua_resul'];

                     $sql_dat_ingi = "SELECT * from cat_servicios where serv_desc='$pac_imagen'";
                     $result_dat_inga = $conexion->query($sql_dat_ingi);

                     while ($row_dat_ingu = $result_dat_inga->fetch_assoc()) {
                    //$desc = $row_dat_ingu['serv_desc'];
                    $tipins = $row_dat_ingu['tip_insumo'];
  
                    }

                     $query_log = "SELECT * FROM reg_usuarios where id_usua= $id_usua_resul ";
                     $result_log = $conexion->query($query_log);

                    while ($row_log = $result_log->fetch_assoc()) {
                        $pac_log = $row_log['papell'] . ' ' . $row_log['sapell'] . ' ' . $row_log['nombre']; }
                    
                    if ($habi <> 0)  {
                    $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                    $result_pac = $conexion->query($query_pac);
                      

                    while ($row_pac = $result_pac->fetch_assoc()) {
                        $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                }
                        $fecha_ord= date_create($row['fecha_ord']);
                        $fecha_res= date_create($row['fecha_resul']);
                        echo '<tr>'
                            . '<td >' . date_format($fecha_ord,'d/m/Y H:i a') . '</td>'
                            . '<td >' . $row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nombre'] . '</td>'
                            . '<td >' . $pac_imagen. ' ' . $tipins.'</td>'
                            . '<td class="fondo" style="color:white; font-size:30px;" ><a href="' . $row['link']  . '" target="_blank" title="Ver estudio"><center><i class="fa fa-eye" aria-hidden="true" ></i></center></a></td>';
                             //. '<td >' . $row['not_id'] . '</td>';

if($row['interpretado']=="Si"){
echo '<td class="fondo" style="color:white;"><center>'
                            . ' <a  href="../../sauxiliares/Imagenologia/dwv/tests/pacs/viewer.php?not_id=' . $not_id . '&id_atencion=' . $row['id_atencion'] . '"
                     target="_blank" title="Ver informe" class="btn btn-success"><span class="fa fa-file-pdf-o" aria-hidden="true"> <i class="fa fa-check" aria-hidden="true"></i></span></a>';
                        echo '</center></td>';

}else if($row['interpretado']=="No"){
    echo '<td class="fondo" style="color:white;"><center>'
                            . ' <a  href=""
                      title="No disponible" class="btn btn-danger"><span class="fa fa-file-pdf-o" aria-hidden="true"> <i class="fa fa-times" aria-hidden="true"></i></span></a>';
                        echo '</center></td>';
}
                        $no++;
                       echo  '<td >' . date_format($fecha_ord,'d/m/Y H:i a') . '</td>'
                           . '<td >' . $pac_log .  '</td></tr>';
                      } else  {
                            
                                $query_rec = "SELECT * FROM receta_ambulatoria where id_rec_amb = $id_atencion ";
                                $result_rec = $conexion->query($query_rec);

                                while ($row_rec = $result_rec->fetch_assoc()) {
                                    $pac = $row_rec['papell_rec'] . ' ' . $row_rec['sapell_rec'] . ' ' . $row_rec['nombre_rec'];
                                    $habitacion = "C.EXT";
                                }
                                
                            $fecha_ord= date_create($row['fecha_ord']);
                            $fecha_res= date_create($row['fecha_resul']);    
                                 echo '<tr>'

                            . '<td >' . $row['id_atencion'] . ' ' . $pac . '</td>'
                            . '<td >' . $habitacion . '</td>'
                            . '<td >' .  date_format($fecha_ord,'d/m/Y H:i a') . '</td>'
                            . '<td >' . $row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nombre'] . '</td>'
                            . '<td >' . $pac_imagen. '</td>'
                            . '<td >' . $row['realizado'] . '</td>';
                             //. '<td >' . $row['not_id'] . '</td>';

   echo '<td class="fondo" style="color:white;"><center>'
                            . ' <a  href="../../sauxiliares/Imagenologia/dwv/tests/pacs/editar_resultado.php?not_id=' . $not_id . '&id_atencion=' . $row['id_atencion'] . '"
                      title="Editar resultado" class="btn btn-danger"><span class="fa fa-edit" aria-hidden="true"></span></a>';
                        echo '</center></td>';

   echo '<td class="fondo" style="color:white;"><center>'
                            . ' <a  href="../../sauxiliares/Imagenologia/dwv/tests/pacs/viewer.php?not_id=' . $not_id . '&id_atencion=' . $row['id_atencion'] . '"
                     target="_blank" title="Ver resultado" class="btn btn-danger"><span class="fa fa-eye" aria-hidden="true"></span></a>';
                        echo '</center></td>';


                        $no++;
                       echo  '<td >' .  date_format($fecha_res,'d/m/Y H:i a'). '</td>'
                           . '<td >' . $pac_log .  '</td></tr>';
                } }  ?>
                    </tbody>
                </table>

            </div>
        
   

 <!-- TERMINO ESTUDIOS IMAGENOLOGIA-->
    <hr>
<!-- INICIO ESTUDIOS patlogia-->
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
  <center><strong>ESTUDIOS DE PATOLOGÍA</strong></center><p>
</div> 
<div class="table-responsive">
                <table class="table table-bordered table-striped" id="mytable">
                    <thead class="thead" style="background-color: #2b2d7f; color:white;">
                    <tr>
                        <th>Fecha de solicitud</th>
                        <th>Solicitante</th>
                        <th>Estudio(s)</th>
                        <th>Resultado(s)</th>
                        <th>Fecha de resultado(s)</th>
                        <th>Atendió solicitud</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    include "../../conexionbd.php";
                    $usuario = $_SESSION['login'];
                    $id_usua_log= $usuario['id_usua'];
                    $id_atencion = $_SESSION['pac'];

                    $query_log = "SELECT * FROM reg_usuarios where id_usua= $id_usua_log ";
                    $result_log = $conexion->query($query_log);

                    while ($row_log = $result_log->fetch_assoc()) {
                        $pac_log = $row_log['papell'] . ' ' . $row_log['sapell'] . ' ' . $row_log['nombre'];
                    }


         

                    $query = "SELECT * FROM notificaciones_pato n, reg_usuarios u ,cat_servicios c where n.realizado = 'SI' and n.id_atencion=$id_atencion and n.id_usua = u.id_usua and  c.serv_desc= n.dispo_p ORDER BY fecha_resul DESC";
                    $result = $conexion->query($query);
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                    $id_notp = $row['id_notp'];
                    $id_medico = $row['id_usua'];
                    $id_resp = $row['id_usua_resul'];
                    
                    $query_medi = "SELECT * FROM reg_usuarios where Id_usua = $id_medico";
                    $result_medi = $conexion->query($query_medi);

                    while ($row_medi = $result_medi->fetch_assoc()) {
                        $medico = $row_medi['papell'] . ' ' . $row_medi['sapell'];
                    }
                    $query_resp = "SELECT * FROM reg_usuarios where Id_usua = $id_resp";
                    $result_resp = $conexion->query($query_resp);

                    while ($row_resp = $result_resp->fetch_assoc()) {
                        $resp = $row_resp['papell'] . ' ' . $row_resp['sapell'];
                    }


                    $query_pac = "SELECT * FROM dat_ingreso d, paciente p where d.id_atencion = $id_atencion and d.Id_exp = p.Id_exp";
                    $result_pac = $conexion->query($query_pac);
                        $pac_imagen = $row['serv_desc'];
                    

                    while ($row_pac = $result_pac->fetch_assoc()) {
                        $pac = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
                                }
                        $fecha_ord= date_create($row['fecha_ord']);
                        $fecha_res= date_create($row['fecha_resul']);    
                        echo '<tr>'

                            . '<td >' . date_format($fecha_ord,'d/m/Y H:i a') . '</td>'
                            . '<td >' . $medico . '</td>'
                            . '<td >' . $pac_imagen. '</td>'
                            . '<td >' . $row['realizado'] . '</td>';
                            
   echo '<td class="fondo" style="color:white;"><center>'
                            . ' <a  href="../../sauxiliares/Patologia/verpdf.php?id_notp=' . $id_notp . '&id_atencion=' . $row['id_atencion'] . '"" title="Ver resultado" class="btn btn-danger"><span class="fa fa-eye" aria-hidden="true"></span></a>';
                        echo '</center></td>';


                        $no++;
                       echo  '<td >' . date_format($fecha_res,'d/m/Y H:i a') . '</td>'
                           . '<td >' . $resp .  '</td></tr>';
                    }
                
                    ?>
                    </tbody>
                </table>

            </div>
        

</div>
</div><br><br><br><br>
</div>
</th>
<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>

<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<script>
    document.oncontextmenu = function () {
        return false;
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
</script>


</body>
</html>