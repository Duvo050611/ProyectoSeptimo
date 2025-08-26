<?php
session_start();


include "../../conexionbd.php";
include("../header_medico.php");
if (isset($_SESSION['hospital'])) { //si existe el id mandado por metodo GET, se hara la consulta donde el id debe ser igual al id mandado por el metodo GET
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


    <title>NOTA DE EGRESO</title>
  <style>
    hr.new4 {
      border: .5px solid red;
    }
  </style>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
                
                <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>NOTA DE EGRESO</strong></center><p>
</div>
                <hr>

<?php

include "../../conexionbd.php";

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);

$sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = " . $_SESSION['hospital'];

      $result_now = $conexion->query($sql_now);

      while ($row_now = $result_now->fetch_assoc()) {
        $dat_now = $row_now['dat_now'];
      }

      $sql_est = "SELECT DATEDIFF( '$dat_now' , fecha) as estancia FROM dat_ingreso WHERE id_atencion =" . $_SESSION['hospital'];

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
        $estancia = $row_est['estancia'];
      }
?>
  <?php
                    while ($f1 = mysqli_fetch_array($resultado1)) {

                        ?>
                      <div class="container"> 
                        <div class="row">
      <div class="col-sm-6">
 NO.EXPEDIENTE : <td><strong><?php echo $f1['Id_exp']; ?></strong></td><br>
PACIENTE : <td><strong><?php echo $f1['papell']; ?></strong></td>
<td><strong><?php echo $f1['sapell']; ?></strong></td>
<td><strong><?php echo $f1['nom_pac']; ?></strong></td><br>
SEXO : <td><strong><?php echo $f1['sexo']; ?></strong></td><br>
   
    </div>

    <div class="col-sm-4">
<?php $date = date_create($f1['fecha']);
$date1 = date_create($f1['fecnac']);
                                     ?>
      FECHA DE INGRESO : <td><strong><?php echo date_format($date, "d/m/Y"); ?></strong></td><br>
      FECHA DE NACIMIENTO : <td><strong><?php echo date_format($date1, "d/m/Y"); ?></strong></td><br>
      EDAD : <td><strong><?php echo $f1['edad']; ?></strong></td><br>  
    <?php  
                    }
                    ?> 
    </div>

<?php
$resultado2 = $conexion->query("select * from cat_camas WHERE id_atencion=" .$_SESSION['hospital']) or die($conexion->error);
while ($f2 = mysqli_fetch_array($resultado2)) {
?>
  <div class="col">
<?php  
 if(isset($f2)){
    $cama=$f2['num_cama'];
  }else{
    $cama='Sin Cama';
  }
 ?>
HABITACIÓN : <td><strong><?php echo $cama; ?></strong></td> 

<?php
}
?>
    </div>
    
    
  </div>

</div>

<hr>
                               
                        
            </div>
            
        </div>

<div class="tab-content" id="nav-tabContent">
   <!--INICIO-->
<div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

          <form action="" method="POST">
<?php 

include "../../conexionbd.php";
$resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=" . $_SESSION ['hospital'].".ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
      $atencion=$f5['id_sig'];
    }
    ?>
    <?php
if (isset($atencion)) {
                        ?>
<?php 

include "../../conexionbd.php";
$resultado5=$conexion->query("select * from signos_vitales WHERE id_atencion=" . $_SESSION ['hospital'].".ORDER by id_sig DESC LIMIT 1") or die($conexion->error);

     while ($f5 = mysqli_fetch_array($resultado5)) {
     
    
    ?>
     <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>

<div class="container"> 
  <div class="row">
    
      
    
    <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" class="form-control" value="<?php echo $f5['p_sistol'];?>" disabled></div> /
  <div class="col"><input type="text" class="form-control" value="<?php echo $f5['p_diastol'];?>" disabled></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="text" class="form-control" value="<?php echo $f5['fcard'];?>" disabled>
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" value="<?php echo $f5['fresp'];?>" disabled>
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="text" class="form-control" value="<?php echo $f5['temper'];?>" disabled>
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text"  class="form-control" value="<?php echo $f5['satoxi'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control" value="<?php echo $f5['peso'];?>" disabled>
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" value="<?php echo $f5['talla'];?>" disabled>
    </div>
  </div>

<?php }
?>
<?php 
}else{
                        
  ?>
  <div class="thead" style="background-color: #0c675e; color: white; font-size: 19px;">
  <center><strong>SIGNOS VITALES</strong></center><p>
</div>
                <div class="container"> 
  <div class="row">
    <div class="col-sm-2"><br><center>PRESIÓN ARTERIAL:</center>
     <div class="row">
  <div class="col"><input type="text" class="form-control" name="p_sistol" ></div> /
  <div class="col"><input type="text" class="form-control" name="p_diastol"></div>
 
</div>
    </div>
    <div class="col-sm-2">
      FRECUENCIA CARDIACA:<input type="text" class="form-control" name="fcard">
    </div>
    <div class="col-sm-2">
      FRECUENCIA RESPIRATORIA:<input type="text" class="form-control" name="fresp">
    </div>
    <div class="col-sm-2">
     <br>TEMPERATURA:<input type="text" class="form-control"  name="temper">
    </div>
    <div class="col-sm-2">
     SATURACIÓN OXÍGENO:<input type="text"  class="form-control" name="satoxi">
    </div>
    <div class="col-sm-1">
     <br>PESO:<input type="text" class="form-control"  name="peso">
    </div>
    <div class="col-sm-1">
     <br>TALLA:<input type="text" class="form-control" name="talla" >
    </div>
  </div>
</div>

<?php } ?>
 <hr>
<div class="container">
    <div class="row">
        <div class="col-sm-3">
                
                
                <div class="form-group">
                    <label for="fecha"><strong>FECHA DE INGRESO:</label></strong>
                    <input type="datetime" name="fec_hc" value="<?php echo date_format($date, "d/m/Y"); ?>" class="form-control" disabled>
                </div>
            </div>




        <div class="col-sm-3">
                <?php
                date_default_timezone_set('America/Guatemala');
                $fecha_actual = date("d-m-Y H:i:s");
                ?>
                
                <div class="form-group">
                    <label for="fecha"><strong>FECHA DE EGRESO:</label></strong>
                    <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                  <label class="control-label"><strong>TIEMPO DE ESTANCIA: </label></strong>
                  <div class="col-md-6">
                    <input type="text" name="dias" class="form-control" value="<?php echo $estancia ?> días" disabled>
                  </div>
                </div>
              </div>
      </div>
    </div>
      
    <?php

include "../../conexionbd.php";

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);

 ?>
  <?php
                  
   
                    ?>    
   <?php 
$id_egreso=$_GET['id_egreso'];
$tras="SELECT * FROM dat_egreso where id_egreso=$id_egreso";
$result=$conexion->query($tras);
while ($row=$result->fetch_assoc()) {
    $id_usua=$row['id_usua'];
    if ($row['reingreso'] == "NO") {
 ?>
    <div class="container">
  <div class="row">
    <div class="col-sm-5">
      <strong>REINGRESO POR LA MISMA AFECCIÓN EN EL AÑO:</strong>
    </div>
    <div class="col-sm">
     <div class="form-check">
  <input class="form-check-input" checked type="radio" name="reingreso" id="flexRadioDisabled" value="NO">
  <label class="form-check-label" for="flexRadioDisabled">
    NO
  </label>
</div>
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="reingreso" id="flexRadioDisabled" value="SI">
  <label class="form-check-label" for="flexRadioDisabled">
    SI
  </label>
</div>
    </div>
  </div>
</div>
<hr>
<?php }elseif ($row['reingreso'] == "SI") {?>
<div class="container">
  <div class="row">
    <div class="col-sm-5">
      <strong>REINGRESO POR LA MISMA AFECCIÓN EN EL AÑO:</strong>
    </div>
    <div class="col-sm">
     <div class="form-check">
  <input class="form-check-input"  type="radio" name="reingreso" id="flexRadioDisabled" value="NO">
  <label class="form-check-label" for="flexRadioDisabled">
    NO
  </label>
</div>
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" checked type="radio" name="reingreso" id="flexRadioDisabled" value="SI">
  <label class="form-check-label" for="flexRadioDisabled">
    SI
  </label>
</div>
    </div>
  </div>
</div>
<hr>
<?php } ?>

<?php if ($row['cond'] == "MEJORIA") { ?>
        <div class="col-sm">
            <div class="form-group">
                <strong><center><label>CONDICIONES DE ALTA :</label></center></strong><br>
                <input type="radio" name="cond" checked="" value="MEJORIA" >&nbsp; <label>  MEJORIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="MÁXIMO BENEFICIO">&nbsp; <label>  MÁXIMO BENEFICIO</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="ALTA VOLUNTARIA">&nbsp; <label> ALTA VOLUNTARIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="DEFUNCIÓN">&nbsp; <label>  DEFUNCIÓN</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRANSLADO A OTRA INSTITUCIÓN">&nbsp; <label>  TRANSLADO A OTRA INSTITUCIÓN</label>
            </div>        
        </div> 
         <?php  }elseif ($row['cond'] == "MÁXIMO BENEFICIO") { ?> 
            <div class="col-sm">
            <div class="form-group">
                <strong><center><label>CONDICIONES DE ALTA :</label></center></strong><br>
                <input type="radio" name="cond" value="MEJORIA" >&nbsp; <label>  MEJORIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" checked="" name="cond" value="MÁXIMO BENEFICIO">&nbsp; <label>  MÁXIMO BENEFICIO</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="ALTA VOLUNTARIA">&nbsp; <label> ALTA VOLUNTARIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="DEFUNCIÓN">&nbsp; <label>  DEFUNCIÓN</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRANSLADO A OTRA INSTITUCIÓN">&nbsp; <label>  TRANSLADO A OTRA INSTITUCIÓN</label>
            </div>        
        </div>
         <?php  }elseif ($row['cond'] == "ALTA VOLUNTARIA") { ?> 
            <div class="col-sm">
            <div class="form-group">
                <strong><center><label>CONDICIONES DE ALTA :</label></center></strong><br>
                <input type="radio" name="cond" value="MEJORIA" >&nbsp; <label>  MEJORIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="MÁXIMO BENEFICIO">&nbsp; <label>  MÁXIMO BENEFICIO</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" checked="" name="cond" value="ALTA VOLUNTARIA">&nbsp; <label> ALTA VOLUNTARIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="DEFUNCIÓN">&nbsp; <label>  DEFUNCIÓN</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRANSLADO A OTRA INSTITUCIÓN">&nbsp; <label>  TRANSLADO A OTRA INSTITUCIÓN</label>
            </div>        
        </div>
        <?php  }elseif ($row['cond'] == "DEFUNCIÓN") { ?> 
            <div class="col-sm">
            <div class="form-group">
                <strong><center><label>CONDICIONES DE ALTA :</label></center></strong><br>
                <input type="radio" name="cond"  value="MEJORIA" >&nbsp; <label>  MEJORIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="MÁXIMO BENEFICIO">&nbsp; <label>  MÁXIMO BENEFICIO</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="ALTA VOLUNTARIA">&nbsp; <label> ALTA VOLUNTARIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" checked="" name="cond" value="DEFUNCIÓN">&nbsp; <label>  DEFUNCIÓN</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRANSLADO A OTRA INSTITUCIÓN">&nbsp; <label>  TRANSLADO A OTRA INSTITUCIÓN</label>
            </div>        
        </div>
        <?php  }elseif ($row['cond'] == "TRANSLADO A OTRA INSTITUCIÓN") { ?>
        <div class="col-sm">
            <div class="form-group">
                <strong><center><label>CONDICIONES DE ALTA :</label></center></strong><br>
                <input type="radio" name="cond" value="MEJORIA" >&nbsp; <label>  MEJORIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="MÁXIMO BENEFICIO">&nbsp; <label>  MÁXIMO BENEFICIO</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="ALTA VOLUNTARIA">&nbsp; <label> ALTA VOLUNTARIA</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="DEFUNCIÓN">&nbsp; <label>  DEFUNCIÓN</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cond" value="TRANSLADO A OTRA INSTITUCIÓN" checked="">&nbsp; <label>  TRANSLADO A OTRA INSTITUCIÓN</label>
            </div>        
        </div> 
    <?php } ?>
            <br>
    <div class="row">
        <div class="col-sm">
            <strong><label>DIAGNÓSTICO DE INGRESO :</label></strong><br>
            <textarea class="form-control" name="diag_eg" rows="5"><?php echo $row['diag_eg'] ?></textarea>
        </div>

            
      </div> 
    <div class="row">
        <div class="col-sm">
            <strong><label>DIAGNÓSTICO(S) FINAL(ES) :</label></strong><br>
            <textarea class="form-control" name="diagfinal" rows="5"><?php echo $row['diagfinal'] ?></textarea>
        </div>

        <div class="col-sm">
            <strong><label>RESUMEN DE EVOLUCIÓN Y ESTADO ACTUAL:</label></strong><br>
            <textarea class="form-control" name="res_clin" rows="5"><?php echo $row['res_clin'] ?></textarea>
        </div>
    
      </div> 
<br><br>
      <div class="row">


      </div>
  <div class="row">
          <div class="col-sm">
              <div class="form-group">
                  <strong><label>MANEJO DURANTE LA ESTANCIA HOSPITALARIA / FECHA Y HORA DE PROCEDIMIENTOS REALIZADOS : </label></strong><br>
                  <textarea class="form-control" name="manejodur" rows="5"><?php echo $row['manejodur'] ?></textarea>
              </div>
          </div>
      </div>
        <div class="row">
          <div class="col-sm">
              <div class="form-group">
                  <strong><label>PROBLEMAS CLÍNICOS PENDIENTES : </label></strong><br>
                  <textarea class="form-control" name="probclip" rows="5"><?php echo $row['probclip'] ?></textarea>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-sm">
              <div class="form-group">
                  <strong><label>CUIDADOS POSTERIORES EN EL HOGAR E INDICACIONES HIGIENICO DIETETICAS : </label></strong><br>
                  <textarea class="form-control" name="cuid" rows="5"><?php echo $row['cuid'] ?></textarea>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-sm">
              <div class="form-group">
                  <strong><label>TRATAMIENTO : </label></strong><br>
                  <textarea class="form-control" name="trat" rows="5"><?php echo $row['trat'] ?></textarea>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-sm">
              <div class="form-group">
                 <strong> <label>EXAMENES O ESTUDIOS DE SEGUMIENTO : </label></strong><br>
                 <textarea class="form-control" name="exes" rows="5"><?php echo $row['exes'] ?></textarea>
             </div>
           </div>
       </div>    
      <div class="row">
          <div class="col-sm-4">
              <div class="form-group">
                  <strong><label>FECHA PROXIMA DE CITA O CONSULTA : </label></strong><br>
                  <input class="form-control" type="date" value="<?php echo $row['pcita'] ?>" class="form-control" name="pcita"> 
              </div>
          </div>
          <div class="col-sm-4">
                <div class="form-group">
                  <strong><label>HORA PROXIMA DE CITA O CONSULTA : </label></strong>
                  <input type="time" class="form-control" name="hcita" value="<?php echo $row['hcita'] ?>"> 
           
                </div>
          </div>

      </div>
       <hr class="new4">
       <?php 
       $date=date_create($row['fech_egreso']);
        ?>
      <div class="row">
          <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>FECHA REGISTRO : </label></strong><br>
                 <input type="date" name="fecha" value="<?php echo date_format($date,"Y-m-d") ?>" class="form-control">
             </div>
           </div>
           <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>HORA REGISTRO :</label></strong><br>
                 <input type="time" name="hora" value="<?php echo date_format($date,"H:i:s") ?>" class="form-control">
             </div>
           </div>
       <?php 
   }
$select="SELECT * FROM reg_usuarios where id_usua=$id_usua";
$resultado=$conexion->query($select);
while ($row_doc=$resultado->fetch_assoc()) {
    $doctor=$row_doc['nombre'].' '.$row_doc['papell'].' '.$row_doc['sapell'];
    $id_doc=$row_doc['id_usua'];
}

        ?>
          <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>MÉDICO QUE REGISTRÓ: </label></strong><br>
                 <select name="medico" class="form-control" >
                     <option value="<?php echo $id_doc ?>"><?php echo $doctor ?></option>
                     <option></option>
                     <option value=" ">SELECCIONAR MEDICO :</option>
                     <?php 
                      $select="SELECT * FROM reg_usuarios where id_rol=2 || id_rol=12";
                      $resultado=$conexion->query($select);
                      foreach ($resultado as $row ) {
                      ?>
                      <option value="<?php echo $row['id_usua'] ?>"><?php echo $row['nombre'].' '.$row['papell'].' '.$row['sapell']; ?></option>
                  <?php } ?>
                 </select>
             </div>
           </div>
       </div>     
<hr class="new4">
</div>

<center><div class="form-group col-6">
      <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
      <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
    </div></center>

</div>
<br>
<br>
</form>
</div>

   <!--TERMINO DE NOTA DE EVOLUCION-->

 <?php 
  if (isset($_POST['guardar'])) {

        $reingreso    = mysqli_real_escape_string($conexion, (strip_tags($_POST["reingreso"], ENT_QUOTES))); //Escanpando caracteres
        $cond    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cond"], ENT_QUOTES))); //Escanpando caracteres
        $diag_eg   = mysqli_real_escape_string($conexion, (strip_tags($_POST["diag_eg"], ENT_QUOTES)));
        $diagfinal   = mysqli_real_escape_string($conexion, (strip_tags($_POST["diagfinal"], ENT_QUOTES)));
        $res_clin    = mysqli_real_escape_string($conexion, (strip_tags($_POST["res_clin"], ENT_QUOTES))); //Escanpando caracteres
        $manejodur    = mysqli_real_escape_string($conexion, (strip_tags($_POST["manejodur"], ENT_QUOTES)));
        $probclip    = mysqli_real_escape_string($conexion, (strip_tags($_POST["probclip"], ENT_QUOTES)));
        $cuid    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cuid"], ENT_QUOTES)));
        $trat    = mysqli_real_escape_string($conexion, (strip_tags($_POST["trat"], ENT_QUOTES)));
        $exes    = mysqli_real_escape_string($conexion, (strip_tags($_POST["exes"], ENT_QUOTES)));
        $pcita      = mysqli_real_escape_string($conexion, (strip_tags($_POST["pcita"], ENT_QUOTES)));
        $hcita    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hcita"], ENT_QUOTES))); //Escanpando caracteres
        //$nota_defuncion  = mysqli_real_escape_string($conexion, (strip_tags($_POST["nota_defuncion"], ENT_QUOTES)));
        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));
       
       $merge = $fecha.' '.$hora;

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_egreso SET id_usua='$medico',fech_egreso='$merge',reingreso='$reingreso', cond='$cond', diag_eg='$diag_eg', res_clin='$res_clin', diagfinal='$diagfinal', manejodur='$manejodur', probclip='$probclip' , cuid='$cuid', trat='$trat', exes='$exes' , pcita ='$pcita', hcita ='$hcita' WHERE id_egreso= $id_egreso";
        $result = $conexion->query($sql2);

        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }
  ?>
</div>
</div>

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