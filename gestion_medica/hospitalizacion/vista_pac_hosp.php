<?php
session_start();
require_once "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
include ("../header_medico.php");


$usuario = $_SESSION['login'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function(){
            $("#search").keyup(function(){
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function() {
                    if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>
    <title>Menu Gestión Médica </title>
    <link rel="shortcut icon" href="logp.png">
</head>



<body>
 <div class="container">
        <div class="row">
            <div class="col">

<h2><strong>PACIENTE </strong></h2>
    <hr>
<?php

require_once "../../conexionbd.php";

$bisiesto=false;
$resultado1 = $conexion->query("SELECT paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.alergias, dat_ingreso.motivo_atn, dat_ingreso.id_usua as id_med, dat_ingreso.fecha, dat_ingreso.id_atencion, dat_ingreso.Id_exp
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=".$_SESSION['hospital'].' order by dat_ingreso.id_atencion DESC' ) or die($conexion->error);
  $id_atencion = $_SESSION['hospital'];
?>
  <?php
    while ($f1 = mysqli_fetch_array($resultado1)) {
        $id_med=$f1['id_med'];
        $area=$f1['area'];
        $religion=$f1['religion'];
        $tip_san=$f1['tip_san'];
        $alergias=$f1['alergias'];
        $sexo = $f1['sexo'];

?>

<div class="container">

 <div class="row">
    <div class="col-sm-5">
       Expediente: <td><strong><?php echo $f1['Id_exp']; ?></strong></td>
       Paciente:
<td><strong><?php echo $f1['papell']; ?></strong></td>
<td><strong><?php echo $f1['sapell']; ?></strong></td>
<td><strong><?php echo $f1['nom_pac']; ?></strong></td><br>

Género : <td><strong><?php echo $sexo; ?></strong></td><br>

 <?php
    $d="";
    $sql_motd = "SELECT diagprob_i from dat_not_ingreso where id_atencion=$id_atencion ORDER by id_not_ingreso DESC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    }
    $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by id_ne DESC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } ?>
    <?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by id_atencion DESC LIMIT 1 ";
    $result_mot = $conexion->query($sql_mot);
    while ($row_mot = $result_mot->fetch_assoc()) {
    $m=$row_mot['motivo_atn'];
    }


        echo '<td> Diagnóstico: <strong>' . $d .'</strong></td><br>';
        echo '<td> Alergias: <strong><font color=red>' . $alergias.'</font></strong></td>';
    ?>

    </div>
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

$fecha_actual = date("Y-m-d");
$fecha_nac=$f1['fecnac'];
$fecha_de_nacimiento =strval($fecha_nac);

// separamos en partes las fechas
$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
$array_actual = explode ( "-", $fecha_actual );

$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días
$ano_nac = $array_actual[0];
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
    <div class="col">
<?php $date = date_create($f1['fecha']);
$date1 = date_create($f1['fecnac']);

  echo   'Fecha de Ingreso: <td><strong>'.date_format($date, "d/m/Y").'</strong></td> <br>'.
    'Fecha de nacimiento: <td><strong>'.date_format($date1, "d/m/Y").'</strong></td><br>'.


      'Edad : <td><strong>'; if($anos > "0" ){
   echo $anos." Años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." Meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." Días";
}

echo '</strong></td><br>';
                    }

   echo '</div>';
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=" .$_SESSION['hospital']) or die($conexion->error);
while ($f2 = mysqli_fetch_array($resultado2)) {

echo  '<div class="col">';

 if(isset($f2)){
    $cama=$f2['num_cama'].' '.$f2['tipo'];
  }else{
    $cama='Sin Cama';
  }
/*echo 'Habitación: <td><strong>'. $cama.'</strong></td>'; */


echo ' <div class="row">'.
      '<div class="col-sm">'.
      '<label>Área: </label><strong> '.$area. '<p>'.'</strong>';
echo 'Habitación: <td><strong>'. $cama.'</strong></td>'.
      '</div>'.
      '</div>';
}

echo '</div>'.
'</div>';

$select_doc = $conexion->query("SELECT * from reg_usuarios WHERE id_usua=$id_med") or die($conexion->error);
while ($row = mysqli_fetch_array($select_doc)) {
$doctor=$row['papell'];
}

 echo ' <div class="row">'.
      '<div class="col-sm-5">'.
      '<label>Médico Tratante: </label><strong> '.$doctor.'</strong>'.
      '</div>'.
      '<div class="col-sm">'.
      '<label>Religión: </label><strong> '.$religion.'</strong>'.
      '</div>'.
       '<div class="col-sm">'.
      '<label>Tipo de sangre: </label><strong> '.$tip_san.'</strong>'.
      '</div>'.

      '</div>';


  ?>
  <!-- Main content -->
      <section class="content">
        <!-- CONTENIDOO -->
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->


          <!-- Wrapper for slides -->

                   <?php
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_simg=$f['id_simg'];
?>
          <!-- Wrapper for slides -->
          <div class="carousel-inner">
            <div class="item active">
            <center><td class="fondo"><img src="../../configuracion/admin/img5/<?php echo $f['img_cuerpo']?>" alt="portada" class="img-fluid" width="600"></td></center>
            </div>
          </div>
          <?php
}
?>

          <!-- Left and right controls -->
          <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>

      </section><!-- /.content -->
       </div>
        </div>
         </div>
          </div>
           </div>
      <footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>
</div>



<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>
</html>