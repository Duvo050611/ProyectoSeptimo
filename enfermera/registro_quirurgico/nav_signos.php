<?php
session_start();
//include "../../conexionbd.php";
include "../header_enfermera.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];
?>
<!DOCTYPE html>
<html>

<head>
   <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>

    <link rel="stylesheet" type="text/css" href="css/select2.css">
   
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


   
        <script>
            // Write on keyup event of keyword input element
            $(document).ready(function () {
                $("#search").keyup(function () {
                    _this = this;
                    // Show only matching TR, hide rest of them
                    $.each($("#mytable tbody tr"), function () {
                        if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                            $(this).hide();
                        else
                            $(this).show();
                    });
                });
            });
        </script>
   
    <title>DETALLE DE LA CUENTA</title>
    <style>
        hr.new4 {
            border: 1px solid red;
        }
    </style>
</head>

<body>
<section class="content container-fluid">

    <?php

    include "../../conexionbd.php";

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

      ///inicio bisiesto
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
      <div class="container">
        <div class="content">
          
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
                 <tr><strong><center>REGISTRO CLÍNICO DE ENFERMERÍA QUIRÚRGICO<br>CONTROL DE TEXTILES</center></strong>
        </div>
         <hr>
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
      
$result_vit = $conexion->query($sql_vit);                                                                                    while ($row_vit = $result_vit->fetch_assoc()) {
    $peso=$row_vit['peso'];

} if (!isset($peso)){
    $peso=0;
   
}   echo $peso;?></strong>
    </div>
  
      <div class="col-sm">
      Talla: <strong><?php $sql_vitt =" SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_vitt = $conexion->query($sql_vitt);                                                                                    while ($row_vitt = $result_vitt->fetch_assoc()) {
    $talla=$row_vitt['talla'];
 
} if (!isset($talla)){
    
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
      Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
    
    
   <div class="col-sm">
     <label class="control-label">Aseguradora: </label><strong>  &nbsp; 
                  <?php $sql_aseg = "SELECT aseg from dat_ingreso where id_atencion =$id_atencion";
                     $result_aseg = $conexion->query($sql_aseg);
                      while ($row_aseg = $result_aseg->fetch_assoc()) {
                          echo $row_aseg['aseg'];
                          $at=$row_aseg['aseg'];
}
 

$resultadot = $conexion ->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$at'")or die($conexion->error);
     while($filat = mysqli_fetch_array($resultadot)){ 
$tr=$filat["tip_precio"];
echo ' '.$tr;
                                                                                  } ?></strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
<div class="container">
  <div class="row">
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
   
  </div>
</div></font>
<hr>
<div class="container-fluid">
<div class="container">
  <div class="row">
    <div>
    <a class="btn btn-outline-primary btn-sm" href="vista_enf_quirurgico.php">NOTA</a>
    </div>
    <div>&nbsp;
    <a class="btn btn-outline-primary btn-sm active" href="nav_signos.php">SIGNOS VITALES</a>
    </div>
    <div>
    &nbsp;
    <a class="btn btn-outline-primary  btn-sm" href="nav_textiles.php">CONTROL DE TEXTILES</a>
    </div>
    <div>
        &nbsp;
    <a class="btn btn-outline-primary   btn-sm" href="nav_cate.php">CATÉTERES</a>
    </div>
    <div>
        &nbsp;
      <a href="nav_med.php" class="btn btn-outline-primary  btn-sm">MEDICAMENTOS/EQUIPOS</a>
    </div>

     <div>
        &nbsp;
      <a href="nav_rec.php" class="btn btn-outline-primary  btn-sm">NOTA DE RECUPERACIÓN</a>
    </div><p>
    <div>
         &nbsp;
      <a href="../ordenes_medico/ordenes_quir.php" class="btn btn-outline-primary  btn-sm">CUIDADOS </a>
    </div>
    <div>
         &nbsp;
      <a href="nav_med_rojo.php" class="btn btn-outline-danger  btn-sm">CARRO ROJO </a>
    </div>
  </div>
</div>
</div><hr>
<!--INICIO MEDICAMENTOS,ETC-->
   
<!-- inicio seccion de medicamentos -->
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>SIGNOS VITALES</center></strong>
</div>
<div class="container">
  <form action="" method="POST">
 <div class="row">
    
  </div>
  
<div class="container"> 
  <div class="row">
    <div class="col-sm-3">
<?php 
$id_quir_graff=0;
$resultado3 = $conexion->query("SELECT * from dat_quir_grafico WHERE id_atencion=$id_atencion ORDER BY id_quir_graf Desc") or die($conexion->error);

                while($f3 = mysqli_fetch_array($resultado3)){
$id_quir_graff=$f3['id_quir_graf'];
                }
                   
if($id_quir_graff==null){
    
    ?>
    <br><br>
<strong> Registro Inicial:</strong> <?php echo 1; ?>
<?php }

else if($id_quir_graff>0){
$resultado3 = $conexion->query("SELECT * from dat_quir_grafico WHERE id_atencion=$id_atencion ORDER BY cuenta Desc limit 1") or die($conexion->error);

                while($f3 = mysqli_fetch_array($resultado3)){
$cuenta=$f3['cuenta'];
                }
                ?>
<br><br>
          <strong> Siguiente registro:</strong> <?php echo $cuenta+1; ?>

          <?php } ?>

      <!--<strong><br>Hora:</strong>
      <select class="form-control" name="hora" required>
  <option value=""><strong>Seleccionar una hora</strong></option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
  <option value="6">6</option>
  <option value="7">7</option>
  <option value="8">8</option>
  <option value="9">9</option>
  <option value="10">10</option>
  <option value="11">11</option>
  <option value="12">12</option>
  <option value="13">13</option>
  <option value="14">14</option>
  <option value="15">15</option>
  <option value="16">16</option>
  <option value="17">17</option>
  <option value="18">18</option>
  <option value="19">19</option>
  <option value="20">20</option>
  <option value="21">21</option>
  <option value="22">22</option>
  <option value="23">23</option>
  <option value="24">24</option>
  <option value="25">25</option>
  <option value="26">26</option>
  <option value="27">27</option>
  <option value="28">28</option>
  <option value="29">29</option>
  <option value="30">30</option>
  <option value="31">31</option>
  <option value="32">32</option>
  <option value="33">33</option>
  <option value="34">34</option>
  <option value="35">35</option>
  <option value="36">36</option>
  <option value="37">37</option>
  <option value="38">38</option>
  <option value="39">39</option>
  <option value="40">40</option>
  <option value="41">41</option>
  <option value="42">42</option>
  <option value="43">43</option>
  <option value="44">44</option>
  <option value="45">45</option>
  <option value="46">46</option>
  <option value="47">47</option>
  <option value="48">48</option>
</select>-->
    </div>
    <div class="col-sm-3"><br><center><strong>Presión arterial:</strong></center>
     <div class="row">
  <div class="col losInputTAM"><input type="text" class="form-control" id="sist" name="sistg" required="" ></div> /
  <div class="col losInputTAM"><input type="text" class="form-control" id="diast" name="diastg" required=""></div>
 
</div>
    </div>
    <div class="col-sm-3">
      <strong><br>Frecuencia cardiaca</strong>:<input type="text" class="form-control" name="fcardg" required="">
    </div>
     <div class="col-sm-3"><br>
      <strong>Saturación oxigeno</strong>:<input type="text" class="form-control" name="satg" required="" >
    </div>
    
   
   
  </div>
</div>
<hr>

<center>
                       <div class="form-group col-6">
                            <button type="submit" class="btn btn-success" name="sig">Agregar</button>
                             <a href="ver_grafica.php" role="button" class="btn btn-warning">Ver gráfica</a>
                           

                     
                        </div>
</center>
                        <hr>
                        </form>
</div>


<?php
          if (isset($_POST['sig'])) {


$resultado3 = $conexion->query("SELECT * from dat_quir_grafico WHERE id_atencion=$id_atencion ORDER BY id_quir_graf Desc") or die($conexion->error);

                while($f3 = mysqli_fetch_array($resultado3)){
$id_quir_graff=$f3['id_quir_graf'];
                }
                   
if($id_quir_graff==null){
 include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
            $hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES)));
            $sistg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["sistg"], ENT_QUOTES)));
            $diastg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["diastg"], ENT_QUOTES)));
            $fcardg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fcardg"], ENT_QUOTES)));
            $satg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["satg"], ENT_QUOTES)));
            



date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
$ingresar2 = mysqli_query($conexion, 'INSERT INTO dat_quir_grafico (id_atencion,id_usua,hora,sistg,diastg,fcardg,satg,fecha_g,cuenta) values (' . $id_atencion . ',' . $id_usua .',1,"' . $sistg . '","' . $diastg . '","' . $fcardg . '","' . $satg . '","'.$fecha_actual.'",1) ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

           echo '<script type="text/javascript">window.location.href = "nav_signos.php";</script>';
}else if($id_quir_graff>0){
    include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
            $hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES)));
            $sistg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["sistg"], ENT_QUOTES)));
            $diastg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["diastg"], ENT_QUOTES)));
            $fcardg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fcardg"], ENT_QUOTES)));
            $satg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["satg"], ENT_QUOTES)));
            $resultado3 = $conexion->query("SELECT * from dat_quir_grafico WHERE id_atencion=$id_atencion ORDER BY cuenta Desc limit 1") or die($conexion->error);

                while($f3 = mysqli_fetch_array($resultado3)){
$cuenta=$f3['cuenta'];
                }
            $cuenta++;
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
$ingresar2 = mysqli_query($conexion, 'INSERT INTO dat_quir_grafico (id_atencion,id_usua,hora,sistg,diastg,fcardg,satg,fecha_g,cuenta) values (' . $id_atencion . ',' . $id_usua .',"'.$cuenta.'","' . $sistg . '","' . $diastg . '","' . $fcardg . '","' . $satg . '","'.$fecha_actual.'","'.$cuenta.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

           echo '<script type="text/javascript">window.location.href = "nav_signos.php";</script>';
}

           
          }
          ?>
         
    <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from dat_quir_grafico WHERE id_atencion=$id_atencion ORDER BY cuenta ASC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>
            <div class="table-responsive">
<br>
            <table class="table table-bordered table-striped" id="mytable">
                <thead class="thead bg-navy">
                <tr>
                  <th scope="col">No.</th>
                    <th scope="col">Fecha</th>
                     <th scope="col">Hora</th>
                    <th scope="col">Presión arterial</th>
                    <th scope="col">Frecuencia cardiaca</th>
                    <th scope="col">Saturación de Oxígeno</th>
                  <th scope="col">Eliminar</th>
                  
                </tr>
                </thead>
                <tbody>

                <?php
                $no=1;
                while($f = mysqli_fetch_array($resultado)){
                   
                    ?>

                    <tr>
                      <td><strong><?php  echo $no?></strong></td>
                        <td><strong><?php $date=date_create($f['fecha_g']); echo date_format($date,"d/m/Y H:i:s");?></strong></td>
                        <td><strong><?php echo $f['hora'];?></strong></td>
                        <td><strong><?php echo $f['sistg'];?>/<?php echo $f['diastg'];?></strong></td>
                        <td><strong><?php echo $f['fcardg'];?></strong></td>
                        <td><strong><?php echo $f['satg'];?> %</strong></td>
                        <td><a href="el_signos_quir.php?id_quir_graf=<?php echo $f['id_quir_graf'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-danger">X </button></td>
                    
                       <?php $no++; ?>
                    </tr>
                    <?php
                }

                ?>
                </tbody>
              
            </table>
            </div>

            <?php
            } else {
                echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
            }
            ?>
        </div>
    </div>
</section>
</div>

<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador2').select2();
    });
</script>


</body>

</html>