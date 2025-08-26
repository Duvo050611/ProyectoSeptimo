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

    <!--------------------------
    | Your Page Content Here |
    -------------------------->

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
                 <tr><strong><center>REGISTRO DE MEDICAMENTOS CARRO ROJO DE TERAPIA INTENSIVA</center></strong>
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
            <?php   $sql_aseg = "SELECT aseg from dat_ingreso where id_atencion =$id_atencion";
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

<!-- inicio seccion de medicamentos -->

<div class="container">
  <form action="" method="POST" id="medicamentos">
      <div class="row">
        <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="7"><center><h5><strong>MEDICAMENTOS Y MATERIALES CARRO ROJO DE TERAPIA INTENSIVA</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th><center>Medicamento/Material</center></th>
      <th scope="col"><center>Dosis</center></th>
      <th scope="col"><center>Vía</center></th>
      <th scope="col"><center>Frecuencia</center></th>
      <th><center>Cantidad</center></th>
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><select data-live-search="true" name="med" id="mibuscador" style="width : 100%; heigth : 100%">
        <option value="">Seleccionar</option>
         <?php
         $sql = "SELECT * FROM material_rojo2, stock_rojo2 where material_rojo2.material_controlado = 'NO' AND material_rojo2.material_id = stock_rojo2.item_id and stock_rojo2.stock_qty != 0 ORDER BY material_rojo2.material_nombre ASC";
              $result = $conexion->query($sql);
               while ($row_datos = $result->fetch_assoc()) {
                 echo "<option value='" . $row_datos['material_id'] . "'>" . $row_datos['material_nombre'] . "</option>";
                }
          ?></select></td>
      <td><input type="text" name="dosis" class="form-control"></td>
      <td><input type="text" name="via" class="form-control"></td>
      <td><input type="text" name="frec" class="form-control"></td>
      <td><input type="number" min="1" step="1" class="form-control" name="qty" required=""></td>
      <td><input type="submit" name="btnagregar" class="btn btn-block btn-success" value="Agregar"></td>
    </tr>
  </tbody>
</table>
     </div>
     
    </form>

<!-- termino seccion de medicamentos-->


</div>


<?php

    if (isset($_POST['btnagregar'])) {
        include "../../conexionbd.php";
        $usuario = $_SESSION['login'];
        $id_usua= $usuario['id_usua'];
        $id_atencion = $_SESSION['pac'];
        $item_id =  mysqli_real_escape_string($conexion, (strip_tags($_POST["med"], ENT_QUOTES)));
        $dosis =  mysqli_real_escape_string($conexion, (strip_tags($_POST["dosis"], ENT_QUOTES)));
        $via =  mysqli_real_escape_string($conexion, (strip_tags($_POST["via"], ENT_QUOTES)));
        $frec =  mysqli_real_escape_string($conexion, (strip_tags($_POST["frec"], ENT_QUOTES)));
            //$hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES))); //Escanpando caracteres
        $cart_uniquid = uniqid();
        $qty =  mysqli_real_escape_string($conexion, (strip_tags($_POST["qty"], ENT_QUOTES))); //Escanpando caracteres
            
        date_default_timezone_set('America/Guatemala');
        $fecha_actual = date("Y-m-d H:i:s");
        $sql_pac = "SELECT p.Id_exp, di.id_atencion FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";
        $result_pac = $conexion->query($sql_pac);
        while ($row_pac = $result_pac->fetch_assoc()) {
            $paciente1 = $row_pac['Id_exp'];
        }

        $sql_aseg = "SELECT aseg from dat_ingreso where id_atencion =$id_atencion";
        $result_aseg = $conexion->query($sql_aseg);
        while ($row_aseg = $result_aseg->fetch_assoc()) {
             $at=$row_aseg['aseg']; 
        }
        $resultadot = $conexion ->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$at'")or die($conexion->error);
        while($filat = mysqli_fetch_array($resultadot)){ 
            $tr=$filat["tip_precio"];
        }

        $sql_stock = "SELECT * FROM stock_rojo2 s where $item_id = s.item_id ";
            //echo $sql_stock;
        $result_stock = $conexion->query($sql_stock);
        while ($row_stock = $result_stock->fetch_assoc()) {
              $stock_id = $row_stock['stock_id'];
              $stock_qty = $row_stock['stock_qty'];
        }

        $sql_stock = "SELECT * FROM material_rojo2 where material_id=$item_id ";
            //echo $sql_stock;
        $result_stock = $conexion->query($sql_stock);
        while ($row_stock = $result_stock->fetch_assoc()) {
              $mat_nom = $row_stock['material_nombre'];
              if ($tr==1) $precio = $row_stock['material_precio'];
              if ($tr==2) $precio = $row_stock['material_precio2'];
              if ($tr==3) $precio = $row_stock['material_precio3'];
        }
            // echo $stock_qty - $qty;
        if (($stock_qty - $qty) >= 0) {
            $sql2 = "INSERT INTO cart_rojo2(material_id,cart_qty,cart_price,cart_stock_id,id_usua,cart_uniqid,paciente,cart_fecha)VALUES($item_id,$qty,$precio,$stock_id,$id_usua,'$cart_uniquid', $id_atencion,'$fecha_actual');";
            //  echo $sql2;
            $result_cart = $conexion->query($sql2);
            $stock = $stock_qty - $qty;
            $sql3 = "UPDATE stock_rojo2 set stock_qty=$stock where stock_id = $stock_id";
            $result3 = $conexion->query($sql3);
        }
        else {
                
                echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
                echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
                echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
                echo '<script>
                      $(document).ready(function() {
                          swal({
                              title: "Verificar existencias en carro rojo de Terapia Intensiva", 
                              type: "success",
                              confirmButtonText: "ACEPTAR"
                          }, function(isConfirm) { 
                              if (isConfirm) {
                                window.location.href = "nav_med_rojo2.php";
                              }
                          });
                      });
                </script>';   
             
        }
        $existe = "NO";
       
        $sql_cartid = "SELECT * FROM cart_rojo2 where paciente=$id_atencion ORDER BY cart_id DESC LIMIT 1 ";
            //echo $sql_cartid;
        $result_cartid = $conexion->query($sql_cartid);
        while ($row_cartid = $result_cartid->fetch_assoc()) {
              $cart_id = $row_cartid['cart_id'];
              $existe = "SI";
        }
        if ($existe === "SI") { 
    
        date_default_timezone_set('America/Guatemala');
        $fecha_actual = date("Y-m-d H:i:s");
        $ingresar2 = mysqli_query($conexion, 'INSERT INTO medicamentos_rojo2 (cart_id,id_atencion,id_usua,dosis,material_id,mat_nom,via,frecuencia,cantidad,fecha) values ('.$cart_id.',' . $id_atencion . ',' . $id_usua .',"' . $dosis . '",' . $item_id . ',"' . $mat_nom . '","' . $via . '","' . $frec . '",' . $qty .',"'.$fecha_actual.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

           echo '<script type="text/javascript">window.location.href = "nav_med_rojo2.php";</script>';
        }
    }

     ?>
    <h4>MEDICAMENTOS / MATERIALES</h4>
    <div class="container">
       <div class="table-responsive">
            <!--<table id="myTable" class="table table-striped table-hover">-->

            <table class="table table-bordered table-striped" id="mytable">

            <thead class="thead" style="background-color: #2b2d7f;color:white;">
            <tr>
                <th>#</th>
                <th>Nombre del material</th>
                <th>Cantidad</th>
                <th></th>
            </tr>
            </thead>
        <tbody>
            <?php
            include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_atencion = $_SESSION['pac'];

            $sql_pac = "SELECT p.*, di.*, di.* FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

            $result_pac = $conexion->query($sql_pac);

            while ($row_pac = $result_pac->fetch_assoc()) {
                $id_exp = $row_pac['Id_exp'];
            }

            $resultado2 = $conexion->query("SELECT * from paciente p, dat_ingreso di, cart_rojo2 c, material_rojo2 i
                where di.id_atencion = c.paciente and p.Id_exp = di.Id_exp and i.material_id = c.material_id AND c.paciente=$id_atencion
                ORDER BY c.cart_id ASC") or die($conexion->error);
            $no = 1;
            $total = 0;
            while ($row = $resultado2->fetch_assoc()) {
                $id_usua = $row['id_usua'];
                $sql4 = "SELECT id_usua, nombre, papell,sapell FROM reg_usuarios where id_usua = $id_usua ";
                $result4 = $conexion->query($sql4);
                while ($row_usua = $result4->fetch_assoc()) {
                  $solicitante = $row_usua['papell'];
                }

                $subtotal = $row['material_precio'] * $row['cart_qty'];
                $total += $subtotal;
                echo '<tr>'
                  . '<td>' .  $no . '</td>'
                  . '<td>' . $row['material_nombre'] . '</td>'
                  . '<td>' . $row['cart_qty'] . '</td>'
                  . '<td> 
                  <a type="submit" class="btn btn-danger btn-sm" href="manipula_rojo2.php?q=del_car&cart_stock_id=' . $row['cart_stock_id'] . '&cart_qty=' . $row['cart_qty'] . '&paciente=' . $id_exp . '&id_atencion=' . $id_atencion. '&cart_id=' . $row['cart_id'] . '"><span class = "fa fa-trash-alt"></span></a>
                  </td>';
                echo '</tr>';
                $no++;
            }
            ?>
        </tbody>
            </table>
            <center>
            <div class="col-md-2">
              <center>
                <?php
                echo '<a type="submit" class="btn btn-success btn-block" href="manipula_rojo2.php?q=comf_cart&paciente=' . $id_exp. '&id_atencion=' . $id_atencion. '&id_usua=' . $usuario['id_usua'] . '&id_cart=' . $row['cart_id'] . '"><span>Confirmar</span></a>';
                ?>
              </center>
            </div>
            </center>
         </div> 
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#mibuscador2').select2();
    });
</script>


</body>

</html>