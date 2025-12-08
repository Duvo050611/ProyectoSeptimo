<?php
session_start();
require_once '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();include "../header_enfermera.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);

?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <!--  Bootstrap  -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

  <!---
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable tbody tr"), function() {
          if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
            $(this).hide();
          else
            $(this).show();
        });
      });
    });
  </script>
  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search_dep").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable tbody tr"), function() {
          if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
            $(this).hide();
          else
            $(this).show();
        });
      });
    });
  </script>
  <title>Registro de medicamentos</title>
  <style type="text/css">
    #contenido{
        display: none;
    }
     #contenido3{
        display: none;
    }
     #contenido4{
        display: none;
    }

</style>
 <style>
    td.fondo {
      background-color: info;
    }
  </style>
  <style>
    td.fondo2 {
      background-color: green !important;
    }
  </style>
    <style>
        table, table th, table td {
            color: #ffffff; /* Cambia el color aquí */
        }
    </style>

</head>

<body>
  <section class="content container-fluid">

    <?php
    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];

      $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias,p.folio FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

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
        $alergias = $row_pac['alergias'];
        $folio = $row_pac['folio'];
      }

      $sql_pac = "SELECT * FROM  dat_ingreso WHERE id_atencion =$id_atencion";

          $result_pac = $conexion->query($sql_pac);

          while ($row_pac = $result_pac->fetch_assoc()) {
            $fingreso = $row_pac['fecha'];
             $fegreso = $row_pac['fec_egreso'];
             $alta_med = $row_pac['alta_med'];
             $alta_adm = $row_pac['alta_adm'];
             $activo = $row_pac['activo'];
             $valida = $row_pac['valida'];
          }

if($alta_med=='SI' && $alta_adm=='SI' && $activo=='NO' && $valida=='SI'){
    
    $sql_est = "SELECT DATEDIFF('$fegreso', '$fingreso') as estancia FROM dat_ingreso where id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
          $estancia = $row_est['estancia'];
       
      }
}else{
    
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
}

     function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

//date_default_timezone_set('America/Guatemala');
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
<div class="container ">
        <button type="button" class="btn btn-danger" onclick="history.back()">REGRESAR</button>
        <hr> 
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                 <tr><strong><center>CONSULTAR SOLUCIONES/AMINAS DEL PACIENTE</center></strong>
            </div>
           
         
 <font size="2">
         <div class="container">
  <div class="row">
    <div class="col-sm-6">
  
      Expediente: <strong><?php echo $folio?> </strong>
   
     Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm">
      Área: <strong><?php echo $area ?></strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm">
      Fecha de ingreso: <strong><?php echo date_format($date, "d-m-Y") ?></strong>
    </div>
  </div>
</div></font>

 <font size="2">
     <div class="container">
  <div class="row">
    <div class="col-sm">
       <?php $date1 = date_create($pac_fecnac);
   ?>
      Fecha de nacimiento: <strong><?php echo date_format($date1, "d-m-Y") ?></strong>
    </div>
    <div class="col-sm">
      Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
    </div>
   
      <div class="col-sm">
      Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    <div class="col-sm">
      Tiempo estancia: <strong><?php echo $estancia ?> Dias</strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
   <div class="col-sm-3">
      Edad: <strong><?php if($anos > "0" ){
   echo $anos." años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." dias";
}
?></strong>
    </div>
    <div class="col-sm-3">
Peso: <strong><?php $sql_vit = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_vit = $conexion->query($sql_vit);
while ($row_vit = $result_vit->fetch_assoc()) {
$peso=$row_vit['peso'];
}if(!isset($peso)){
    $peso=0;
}   echo $peso;?></strong>
    </div>
   <div class="col-sm">
      Talla: <strong><?php $sql_vitt =" SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_vitt = $conexion->query($sql_vitt); 
while ($row_vitt = $result_vitt->fetch_assoc()) {
$talla=$row_vitt['talla'];
}
if(!isset($talla)){
    $talla=0;
}   echo $talla;?></strong>
    </div>
    <div class="col-sm">
      Género: <strong><?php echo $pac_sexo ?></strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
    <div class="col-sm-3">
      Alergias: <strong><?php echo $alergias ?></strong>
    </div>
    <div class="col-sm-6">
      Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud DESC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
      
     <div class="col-sm">
    Aseguradora: <strong><?php $sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
                                                                                  $result_aseg = $conexion->query($sql_aseg);
                                                                                  while ($row_aseg = $result_aseg->fetch_assoc()) {
                                                                                    echo $row_aseg['aseg'];
                                                                                  } ?></strong>
    </div>
  </div>
</div>
</font>
  <font size="2">
 <div class="col-sm-4">
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
       echo '<td> Diagnóstico: <strong>' . $d .'</strong></td>';
    } else{
          echo '<td"> Motivo de atención: <strong>' . $m .'</strong></td>';
    }?>
    </div>
  </font>

        <?php
      } else {
        echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
      }
        ?>
        </div>
<body>
  <hr>


  <div class="container">
  <form action="" method="POST" id="medicamentos">
      <div class="row">
        <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="9"><center><h5><strong>REGISTRAR SOLUCIONES/AMINAS ADMINISTRADAS AL PACIENTE</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      
      <th scope="col-4"><center>Inicio</center></th>
       <th scope="col-4"><center>ml/hrs</center></th>
      <th scope="col-4"><center>Término</center></th>
       <th scope="col-4"><center>Fecha término</center></th>
      <th scope="col-4"><center>Soluciones/Aminas</center></th>
      <th scope="col-1"><center>Tipo catéter</center></th>
      <!--<th scope="col"><center>Sitio</center></th>-->
      <th scope="col"><center>Volumen total</center></th>
    
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
     <!-- <td class="col-sm-2">
        <select class="form-control " name="tipo" required="">
        <option value="">Seleccionar tipo</option>
         <option value="HOSPITALIZACION">HOSPITALIZACIÓN</option>
  <option value="QUIRÓFANO">QUIROFANO</option>
  <option value="CUIDADOS INTENSIVOS">CUIDADOS INTENSIVOS</option>
  <option value="URGENCIAS">URGENCIAS</option>
        </select>
      </td>-->
      <td>
          <input type="time" name="hora_i" class="form-control">
    
      </td>
       <td>
       <input type="text" name="pvc" class="form-control">
   
      </td>
       <td>
       <input type="time" name="hora_t" class="form-control">
      </td>
      <td>
       <input type="date" name="fecha_termino" class="form-control">
      </td>
      <td> 
    <input type="text" class="form-control" name="sol" required="">
      </td>
      <td><input type="text" name="tcate" class="form-control" ></td>
      <!--<td><input type="text" name="sitio" class="form-control" ></td>-->
       <td><input type="text" name="vol" class="form-control" ></td>
      <td><input type="submit" name="btnagregar" class="btn btn-block btn-success btn-sm" value="AGREGAR"></td>
    </tr>
  </tbody>
</table>
<?php $fecha_actual = date("Y-m-d"); ?>
<div class="container">
  <div class="row">
    <div class="col-sm-2">
      <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" name="sol_fecha" value="<?php echo $fecha_actual ?>" class="form-control">
    </div>
    
  </div>
</div>
     </div>
     
    </form>
</div>

  <?php
  if (isset($_POST['btnagregar'])) {
      $usuario = $_SESSION['login'];
      $id_usua = $usuario['id_usua'];
      $id_atencion = $_SESSION['pac'];

      // ELIMINADO: $tipo - ya que no existe en el formulario
      $hora_i = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_i"], ENT_QUOTES)));
      $hora_t = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_t"], ENT_QUOTES)));
      $sol = mysqli_real_escape_string($conexion, (strip_tags($_POST["sol"], ENT_QUOTES)));
      $tcate = mysqli_real_escape_string($conexion, (strip_tags($_POST["tcate"], ENT_QUOTES)));
      $sol_fecha = mysqli_real_escape_string($conexion, (strip_tags($_POST["sol_fecha"], ENT_QUOTES)));
      $vol = mysqli_real_escape_string($conexion, (strip_tags($_POST["vol"], ENT_QUOTES)));
      $pvc = mysqli_real_escape_string($conexion, (strip_tags($_POST["pvc"], ENT_QUOTES)));
      $fecha_termino = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_termino"], ENT_QUOTES)));

      $fecha_actual = date("Y-m-d");

      // CORREGIDO: Formato de fecha/hora válido para MySQL
      // Opción 1: Solo 24 horas sin AM/PM
      $fechahora = date("Y-m-d H:i:s");

      // Opción 2: Si quieres 12 horas con AM/PM (pero MySQL no lo acepta directamente)
      // $fechahora = date("Y-m-d h:i:s A");

      // Query corregida - usando $area en lugar de $tipo
      $ingresar9 = mysqli_query($conexion,
              'INSERT INTO sol_enf 
        (id_atencion, id_usua, tipo, hora_i, hora_t, sol, tcate,sitio, vol, sol_fecha, pvc, fecha_registro, fecha_termino) 
        VALUES 
        (' . $id_atencion . ', ' . $id_usua . ', "' . $area . '", "' . $hora_i . '", "' . $hora_t . '", "' . $sol . '", "' . $tcate . '", "' . $tcate . '", "' .$vol . '", "' . $sol_fecha . '", "' . $pvc . '", "' . $fechahora . '", "' . $fecha_termino . '")'
      ) or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

      echo '<script type="text/javascript">window.location.href = "soluciones.php";</script>';
  }
  ?>

<div class="container-fluid">
          <div class="col col-12">

            <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="BUSCAR...">
            </div>
            
               <?php


?>
            <div class="table-responsive">

            <table class="table table-bordered table-striped" id="mytable">
                <thead class="thead bg-navy">
              
                    <th scope="col">Pdf</th>
                    <th scope="col">Fecha de registro</th>
                    <th scope="col">Fecha de reporte</th>
                    <th scope="col">Hora inicio</th> 
                    <th scope="col">PVC</th> 
                    <th scope="col">Hora término</th> 
                    <th scope="col">Fecha término</th> 
                    <th scope="col">Soluciones</th>
                    <th scope="col">Tipo catéter</th>
                    <!--<th scope="col">Sitio</th>-->
                    <th scope="col">Volumen total</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Registró</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Eliminar</th>
                </tr>
                </thead>
                <tbody>

<?php
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from sol_enf m , reg_usuarios r WHERE m.id_atencion=$id_atencion and m.id_usua=r.id_usua ORDER BY id_sol_enf DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
while($f = mysqli_fetch_array($resultado)){   

    $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE P.Id_exp=DI.Id_exp and DI.id_atencion=$id_atencion") or die($conexion->error);

            
              while ($row = $resultado2->fetch_assoc()) {             
    ?>
                    <tr>
                        <td class="fondo"><a href="../soluciones/pdf_soluciones.php?id_ord=<?php echo $f['id_sol_enf'];?>&id_atencion=<?php echo $f['id_atencion'];?>&id_usua=<?php echo $usuario['id_usua']?>&fecha=<?php echo $f['sol_fecha'];?>&id_exp=<?php echo $row['Id_exp'];?>" target="_blank"><button type="button" class="btn btn-danger"> <i class="fa fa-file-pdf-o" style="font-size:28px"  aria-hidden="true"></i> </button></a></td>
                          <td class="fondo"><strong><?php $dater=date_create($f['fecha_registro']); echo date_format($dater,"d/m/Y H:i a");?></strong></td>
                        <td class="fondo"><strong><?php $date=date_create($f['sol_fecha']); echo date_format($date,"d/m/Y");?></strong></td>
                        <td class="fondo"><strong><?php echo $f['hora_i'];?></strong></td>
                         <td class="fondo"><strong><?php echo $f['pvc'];?></strong></td>
                         <td class="fondo"><strong><?php echo $f['hora_t'];?></strong></td>
                         <td class="fondo"><strong><?php $datete=date_create($f['fecha_termino']); echo date_format($datete,"d/m/Y");?></strong></td>
                         <td class="fondo"><strong><?php echo $f['sol'];?></strong></td>
                         <td class="fondo"><strong><?php echo $f['tcate'];?></strong></td>
                        <!-- <td class="fondo"><strong><?php echo $f['sitio'];?></strong></td>-->
                         <td class="fondo"><strong><?php echo $f['vol'];?></strong></td>
                       
                        <td class="fondo"><strong><?php echo $f['tipo'];?></strong></td>
                        <td class="fondo"><strong><?php echo $f['papell'].' '.$f['sapell']?></strong></td>

<?php if($usuario['id_usua']==$f['id_usua']){
?>
<td><a href="edit_sol.php?id_sol_enf=<?php echo $f['id_sol_enf'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button></td>
<?php }else{
?>
<td>
<div class="alert alert-danger alert-sm" role="alert">
<i class="fa fa-power-off" aria-hidden="true" title="Sin acceso"></i>
</div></td>
<?php }?>
<td><a href="el_sol.php?id_sol_enf=<?php echo $f['id_sol_enf'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-danger">X </button></td>

                    </tr>
                    <?php
                }
                 }
                ?>
                
                </tbody>
              
            </table>
            </div>

        </div>
    </div>
</div>
</div>
</div>
</div>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
<script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/main.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador').select2();
    });
</script>
</body>
</html>