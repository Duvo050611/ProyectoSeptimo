<?php
session_start();


include "../../conexionbd.php";
include("../header_medico.php");
$usuario = $_SESSION['login'];
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

    <title>NOTA ANESTESICA </title>
</head>
<body>

<div class="col-sm-12">
    <div class="container">
        <div class="row">
            <div class="col">
<div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>REGISTRO DESCRIPTIVO TRANS-ANESTÉSICO</strong></center><p>
</div>                 

    <hr>
<?php

include "../../conexionbd.php";

$resultado1 = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE id_atencion=" . $_SESSION['hospital']) or die($conexion->error);

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
 <div class="row">
            <div class="col-sm-10">
                <?php
                date_default_timezone_set('America/Guatemala');
                $fecha_actual = date("d-m-Y H:i:s");
                ?>
                
                <div class="form-group">
                    <label for="fecha">FECHA Y HORA ACTUAL:</label>
                    <input type="datetime" name="fec_hc" value="<?= $fecha_actual ?>" class="form-control" disabled>
                </div>
            </div>
        </div>



  
      <div class="container">  <!--INICIO DE CONSULTA DE URGENCIAS-->

<form action="" method="POST">
<?php 
$id_trans_anest=$_GET['id_trans_anest'];
$alta="SELECT * FROM dat_trans_anest where id_trans_anest=$id_trans_anest";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
?>
<hr>
<div class="row">
  <div class="col">
    <strong>ANESTESIÓLOGO</strong> <input type="text" name="anest" class="form-control" value="<?php echo $row['anest'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  <div class="col">
    <strong>DIAGNÓSTICO POST-OPERATORIO</strong> <input type="text" name="diagposto" value="<?php echo $row['diagposto'] ?>" class="form-control" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  <div class="col">
    <strong>CIRUGÍA REALIZADA</strong> <input type="text" name="opreal" class="form-control" value="<?php echo $row['opreal'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
</div><hr>
<div class="row">
  <div class="col">
    <strong>ANESTESIA REALIZADA</strong> <input type="text" name="anestreal" class="form-control" value="<?php echo $row['anestreal'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  <div class="col">
    <strong>POSICIÓN Y CUIDADOS</strong> <input type="text" name="poscui" class="form-control" value="<?php echo $row['poscui'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
</div>
<hr>
<div class="row">
  <div class="col-10">
    <strong>INDUCCIÓN</strong> <input type="text" name="ind" class="form-control" value="<?php echo $row['ind'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  <div class="col-2">
    <strong>HORA:</strong> <input type="time" name="hora" class="form-control" value="<?php echo $row['hora'] ?>" required >
  </div>
</div>
<div class="row">
  <div class="col">
    <strong>AGENTES Y DOSIS</strong> <input type="text" name="agdo" class="form-control" value="<?php echo $row['agdo'] ?>" required>
  </div>
</div>
<hr>
<center><h6><strong>VÍA AEREA</strong></h6></center>
<div class="row">
  <div class="col">
    <strong>INTUBACIÓN</strong> <input type="text" name="tin" class="form-control" value="<?php echo $row['tin'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
   <div class="col">
    <strong>MASCARILLA</strong> <input type="text" name="masc" class="form-control" value="<?php echo $row['masc'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
</div>
<div class="row">
  <div class="col">
    <strong>CÁNULA</strong> <input type="text" name="can" class="form-control" value="<?php echo $row['can'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  <div class="col">
    <strong>OTRO:</strong> <input type="text" name="otro" class="form-control" value="<?php echo $row['otro'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
</div>
 <strong>LARINGOSCOPIA:</strong> <input type="text" name="larin" class="form-control" value="<?php echo $row['larin'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"><hr>
<div class="row">
  <div class="col">
    <strong>VENTILACIÓN</strong> <input type="text" name="venti" class="form-control" value="<?php echo $row['venti'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  <div class="col">
    <strong>CIRCUITO:</strong> <input type="text" name="cir" class="form-control" value="<?php echo $row['cir'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  <?php if ($row['esasme']=="ESPONTANEA") { ?>
  <div class="col"><br>
   <div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="esasme" id="es" value="ESPONTANEA"  required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  <label class="form-check-label" for="es">
    ESPONTÁNEA
  </label>
</div>
  </div>
<?php }else{?>
  <div class="col"><br>
   <div class="form-check">
  <input class="form-check-input" type="radio" name="esasme" id="es" value="ESPONTANEA"  required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  <label class="form-check-label" for="es">
    ESPONTÁNEA
  </label>
</div>
  </div>
<?php } ?>

<?php if ($row['esasme']=="ASISTIDA") { ?>
  <div class="col"><br>
   <div class="form-check">
  <input class="form-check-input" type="radio" name="esasme" checked="" id="as" value="ASISTIDA" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  <label class="form-check-label" for="as">
    ASISTIDA
  </label>
</div>
  </div>
<?php }else{?>
  <div class="col"><br>
   <div class="form-check">
  <input class="form-check-input" type="radio" name="esasme" id="as" value="ASISTIDA" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  <label class="form-check-label" for="as">
    ASISTIDA
  </label>
</div>
  </div>
<?php } ?>

<?php if ($row['esasme']=="MECANICA") { ?>
  <div class="col"><br>
   <div class="form-check">
  <input class="form-check-input" type="radio" name="esasme" id="me" value="MECANICA" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  <label class="form-check-label" for="me">
    MECÁNICA
  </label>
</div>
  </div>
<?php }else{?>
  <div class="col"><br>
   <div class="form-check">
  <input class="form-check-input" type="radio" name="esasme" id="me" value="MECANICA" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  <label class="form-check-label" for="me">
    MECÁNICA
  </label>
</div>
  </div>
<?php } ?>
</div>


<hr>
<div class="row">
  <div class="col">
     <strong>MECÁNICA:</strong> <input type="text" name="mec" class="form-control" value="<?php echo $row['mec'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
   <div class="col">
     <strong>MODO:</strong> <input type="text" name="modo" class="form-control" value="<?php echo $row['modo'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
   <div class="col">
     <strong>FI O2</strong> <input type="text" name="fl" class="form-control" value="<?php echo $row['fl'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
   <div class="col">
     <strong>V.CORRIENTE</strong> <input type="text" name="vcor" class="form-control" value="<?php echo $row['vcor'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
   <div class="col">
     <strong>FR</strong> <input type="text" name="fr" class="form-control" value="<?php echo $row['fr'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
   <div class="col">
     <strong>REL. I:E</strong> <input type="text" name="rel" class="form-control" value="<?php echo $row['rel'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
   <div class="col">
     <strong>PEEP</strong> <input type="text" name="peep" class="form-control" value="<?php echo $row['peep'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
</div><br>
 <strong>COMENTARIOS:</strong> <input type="text" name="com" class="form-control" value="<?php echo $row['com'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
<hr>
<div class="form-group"><strong>MANTENIMIENTO:</strong>
  <input type="text" name="mant" class="form-control" value="<?php echo $row['mant'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  <hr>

<div class="row"><strong>RELAJACIÓN MUSCULAR:</strong>
<?php if ($row['relaj']== "NO") {?>
  <div class="col-1">
     <div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="relaj" id="no" value="NO" required>
  <label class="form-check-label" for="no">
    NO
  </label>
</div>
  </div>
   <div class="col-1">
     <div class="form-check">
  <input class="form-check-input" type="radio" name="relaj" id="si" value="SI" required>
  <label class="form-check-label" for="si">
    SI
  </label>
</div>
  </div>
<?php }else{?>
  <div class="col-1">
     <div class="form-check">
  <input class="form-check-input" type="radio" name="relaj" id="no" value="NO" required>
  <label class="form-check-label" for="no">
    NO
  </label>
</div>
  </div>
   <div class="col-1">
     <div class="form-check">
  <input class="form-check-input" type="radio" checked="" name="relaj" id="si" value="SI" required>
  <label class="form-check-label" for="si">
    SI
  </label>
</div>
  </div>
<?php } ?>
   <div class="col">
 <strong>AGENTES:</strong> <input type="text" name="agent" class="form-control" value="<?php echo $row['agent'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
   <div class="col">
 <strong>DOSIS TOTAL:</strong> <input type="text" name="dosis" class="form-control" value="<?php echo $row['dosis'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
   <div class="col">
     <strong>ÚLTIMA DOSIS:</strong> <input type="text" name="ultdosis" class="form-control" value="<?php echo $row['ultdosis'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
</div>
<br>
<div class="row"><strong>ANTAGONISMO:</strong>
<?php if ($row['relaj']== "NO") {?>
  <div class="col-1">
     <div class="form-check">
  <input class="form-check-input1" type="radio" checked="" name="ant" id="no" value="NO" required>
  <label class="form-check-label1" for="no">
    NO
  </label>
</div>
  </div>
   <div class="col-1">
     <div class="form-check">
  <input class="form-check-input1" type="radio" name="ant" id="si" value="SI" required>
  <label class="form-check-label1" for="si">
    SI
  </label>
</div>
  </div>
<?php }else{?>
  <div class="col-1">
     <div class="form-check">
  <input class="form-check-input1" type="radio" name="ant" id="no" value="NO" required>
  <label class="form-check-label1" for="no">
    NO
  </label>
</div>
  </div>
   <div class="col-1">
     <div class="form-check">
  <input class="form-check-input1" type="radio" checked="" name="ant" id="si" value="SI" required>
  <label class="form-check-label1" for="si">
    SI
  </label>
</div>
  </div>
<?php } ?>

   <div class="col">
 <strong>AGENTE Y DOSIS:</strong> <input type="text" name="agdos" class="form-control" value="<?php echo $row['agdos'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
  </div>
  <strong>MONITOREO(OPCIONAL):</strong>
<?php if ($row['relaj']== "SI") {?>
   <div class="col-1">
  <div class="form-check">
  <input class="form-check-input2" type="radio" name="monit" checked="" id="si" value="SI" required>
  <label class="form-check-label2" for="si">
    SI
  </label>
</div>
  </div>
   <div class="col-1">
      <div class="form-check">
  <input class="form-check-input2" type="radio" name="monit" id="si" value="NO" required>
  <label class="form-check-label2" for="si">
    NO
  </label>
</div>
  </div>
<?php }else{?>
   <div class="col-1">
  <div class="form-check">
  <input class="form-check-input2" type="radio" name="monit" id="si" value="SI" required>
  <label class="form-check-label2" for="si">
    SI
  </label>
</div>
  </div>
   <div class="col-1">
      <div class="form-check">
  <input class="form-check-input2" type="radio" name="monit" checked="" id="si" value="NO" required>
  <label class="form-check-label2" for="si">
    NO
  </label>
</div>
  </div>
<?php } ?>
  
</div>
<div class="row">
<strong>COMENTARIOS:</strong> <input type="text" name="comen" value="<?php echo $row['comen'] ?>" class="form-control" >
</div>
  <hr>
<strong>ANESTESIA REGIONAL</strong><br><br>
  <div class="row">
    <div class="col-4">
      <div class="col">BLOQUEO <input type="text" name="bloq" class="form-control" value="<?php echo $row['bloq'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
      <div class="col">TÉCNICA <input type="text" name="tec" class="form-control" value="<?php echo $row['tec'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
      <div class="col">POSICIÓN <input type="text" name="posi" class="form-control" value="<?php echo $row['posi'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
      <div class="col">ASEP Y ANTISEP <input type="text" name="asep" class="form-control" value="<?php echo $row['asep'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
      <div class="col">AGUJA <input type="text" name="aguja" class="form-control" value="<?php echo $row['aguja'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
      <hr>
<div class="col"> B.SIMPÁTICO <input type="text" name="bsim" class="form-control" value="<?php echo $row['bsim'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
<div class="col"> B.MOTOR <input type="text" name="bmotor" class="form-control" value="<?php echo $row['bmotor'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
    </div>
    <div class="col-3">
      <div class="col">AGENTES Y DOSIS <input type="text" name="agdosi" class="form-control" value="<?php echo $row['agdosi'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
      <div class="col">CATETER <input type="text" name="cate" class="form-control" value="<?php echo $row['cate'] ?>" required required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
      <div class="col">LATENCIA <input type="text" name="lat" class="form-control" value="<?php echo $row['lat'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
      <div class="col">DIFUSIÓN <input type="text" name="dif" class="form-control" value="<?php echo $row['dif'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
      <br><br><br><BR>
<div class="col">B. SENSITIVO <input type="text" name="bsen" class="form-control" value="<?php echo $row['bsen'] ?>" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
    </div>
    <div class="col-5">
      <center><strong>DATOS DEL PRODUCTO</strong></center>
      <div class="form-group">
         <textarea name="caso" class="form-control" rows="7"><?php echo $row['caso'] ?></textarea>
  </div>
    </div>
  </div>

<div class="col-7"> COMENTARIOS <input type="text" name="coment" class="form-control" value="<?php echo $row['coment'] ?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"></div>
<hr>
<div class="col">
<div class="form-group"><strong>EMERSIÓN:</strong>
   <textarea name="emer" class="form-control" rows="3"><?php echo $row['emer'] ?></textarea>
  </div>
</div>
    <?php 
$date=date_create($row['fecha']);
$id_usua=$row['id_usua'];
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
  <hr>




 
<center>
                       <div class="form-group col-6">
                            <button type="submit" name="guardar" class="btn btn-primary">FIRMAR</button>
                            <button type="button" class="btn btn-danger" onclick="history.back()">CANCELAR</button>
                        </div>
</center>
                        <br>
</form>
</div> <!--TERMINO DE NOTA MEDICA div container-->
                     
</div>
<?php 
  if (isset($_POST['guardar'])) {

        $anest    = mysqli_real_escape_string($conexion, (strip_tags($_POST["anest"], ENT_QUOTES))); 
        $diagposto    = mysqli_real_escape_string($conexion, (strip_tags($_POST["diagposto"], ENT_QUOTES))); 
        $opreal   = mysqli_real_escape_string($conexion, (strip_tags($_POST["opreal"], ENT_QUOTES)));
        $anestreal    = mysqli_real_escape_string($conexion, (strip_tags($_POST["anestreal"], ENT_QUOTES))); 
        $poscui    = mysqli_real_escape_string($conexion, (strip_tags($_POST["poscui"], ENT_QUOTES)));
        $ind    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ind"], ENT_QUOTES)));
        $hora    = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES)));
        $agdo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["agdo"], ENT_QUOTES)));
        $tin    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tin"], ENT_QUOTES)));
        $masc      = mysqli_real_escape_string($conexion, (strip_tags($_POST["masc"], ENT_QUOTES)));
        $can    = mysqli_real_escape_string($conexion, (strip_tags($_POST["can"], ENT_QUOTES)));

        $otro    = mysqli_real_escape_string($conexion, (strip_tags($_POST["otro"], ENT_QUOTES))); 
        $larin    = mysqli_real_escape_string($conexion, (strip_tags($_POST["larin"], ENT_QUOTES))); 
        $venti   = mysqli_real_escape_string($conexion, (strip_tags($_POST["venti"], ENT_QUOTES)));
        $cir    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cir"], ENT_QUOTES))); 
        $esasme    = mysqli_real_escape_string($conexion, (strip_tags($_POST["esasme"], ENT_QUOTES)));
        $mec    = mysqli_real_escape_string($conexion, (strip_tags($_POST["mec"], ENT_QUOTES)));
        $modo    = mysqli_real_escape_string($conexion, (strip_tags($_POST["modo"], ENT_QUOTES)));
        $fl    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fl"], ENT_QUOTES)));
        $vcor    = mysqli_real_escape_string($conexion, (strip_tags($_POST["vcor"], ENT_QUOTES)));
        $fr      = mysqli_real_escape_string($conexion, (strip_tags($_POST["fr"], ENT_QUOTES)));
        $rel    = mysqli_real_escape_string($conexion, (strip_tags($_POST["rel"], ENT_QUOTES)));

        $peep    = mysqli_real_escape_string($conexion, (strip_tags($_POST["peep"], ENT_QUOTES))); 
        $com    = mysqli_real_escape_string($conexion, (strip_tags($_POST["com"], ENT_QUOTES))); 
        $mant   = mysqli_real_escape_string($conexion, (strip_tags($_POST["mant"], ENT_QUOTES)));
        $relaj    = mysqli_real_escape_string($conexion, (strip_tags($_POST["relaj"], ENT_QUOTES))); 
        $agent    = mysqli_real_escape_string($conexion, (strip_tags($_POST["agent"], ENT_QUOTES)));
        $dosis    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dosis"], ENT_QUOTES)));
        $ultdosis    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ultdosis"], ENT_QUOTES)));
        $ant    = mysqli_real_escape_string($conexion, (strip_tags($_POST["ant"], ENT_QUOTES)));
        $agdos    = mysqli_real_escape_string($conexion, (strip_tags($_POST["agdos"], ENT_QUOTES)));
        $monit      = mysqli_real_escape_string($conexion, (strip_tags($_POST["monit"], ENT_QUOTES)));
        $comen    = mysqli_real_escape_string($conexion, (strip_tags($_POST["comen"], ENT_QUOTES)));

        $bloq    = mysqli_real_escape_string($conexion, (strip_tags($_POST["bloq"], ENT_QUOTES))); 
        $agdosi    = mysqli_real_escape_string($conexion, (strip_tags($_POST["agdosi"], ENT_QUOTES))); 
        $tec   = mysqli_real_escape_string($conexion, (strip_tags($_POST["tec"], ENT_QUOTES)));
        $cate    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cate"], ENT_QUOTES))); 
        $posi    = mysqli_real_escape_string($conexion, (strip_tags($_POST["posi"], ENT_QUOTES)));
        $lat    = mysqli_real_escape_string($conexion, (strip_tags($_POST["lat"], ENT_QUOTES)));
        $asep    = mysqli_real_escape_string($conexion, (strip_tags($_POST["asep"], ENT_QUOTES)));
        $dif    = mysqli_real_escape_string($conexion, (strip_tags($_POST["dif"], ENT_QUOTES)));
        $aguja    = mysqli_real_escape_string($conexion, (strip_tags($_POST["aguja"], ENT_QUOTES)));
        $bsim      = mysqli_real_escape_string($conexion, (strip_tags($_POST["bsim"], ENT_QUOTES)));
        $bmotor    = mysqli_real_escape_string($conexion, (strip_tags($_POST["bmotor"], ENT_QUOTES)));

        $bsen    = mysqli_real_escape_string($conexion, (strip_tags($_POST["bsen"], ENT_QUOTES))); 
        $coment    = mysqli_real_escape_string($conexion, (strip_tags($_POST["coment"], ENT_QUOTES))); 
        $caso   = mysqli_real_escape_string($conexion, (strip_tags($_POST["caso"], ENT_QUOTES)));
        $emer    = mysqli_real_escape_string($conexion, (strip_tags($_POST["emer"], ENT_QUOTES)));

        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;

date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

        $sql2 = "UPDATE dat_trans_anest SET id_usua='$medico',fecha='$merge',anest='$anest', diagposto='$diagposto', opreal='$opreal', anestreal='$anestreal', poscui='$poscui', ind='$ind' , hora='$hora', agdo='$agdo', tin='$tin' , masc  ='$masc', can  ='$can', otro='$otro', larin='$larin', venti='$venti', cir='$cir', esasme='$esasme', mec='$mec' , modo='$modo', fl='$fl', vcor='$vcor' , fr  ='$fr', rel  ='$rel', peep='$peep', com='$com', mant='$mant', relaj='$relaj', agent='$agent', dosis='$dosis' , ultdosis='$ultdosis', ant='$ant', agdos='$agdos' , monit  ='$monit', comen  ='$comen', bloq='$bloq', agdosi='$agdosi', tec='$tec', cate='$cate', posi='$posi', lat='$lat' , asep='$asep', dif='$dif', aguja='$aguja' , bsim  ='$bsim', bmotor  ='$bmotor', bsen='$bsen', coment='$coment', caso='$caso', emer='$emer'  WHERE id_trans_anest=$id_trans_anest";
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