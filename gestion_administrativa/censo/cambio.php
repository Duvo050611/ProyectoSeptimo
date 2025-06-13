<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include("../header_administrador.php");
?>

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

  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>



</head>

<body>
  <div class="col-sm-2">
            <a type="submit" class="btn btn-danger btn-sm" onclick="history.back()"><font color="white">Regresar</font></a>
        </div>
        <br>
  <section class="content container-fluid">
    <div class="container box">
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
           <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
            <tr><strong><center>CAMBIO DE HABITACIÓN</center></strong>
       </div>
       <br>
            <?php
            $id = $_GET['id_cama'];
            $tipo = $_GET['tipo'];
            $hab = $_GET['hab'];
            $id_atencion = $_GET['id_atencion'];

            $sql = "SELECT * from cat_camas c, dat_ingreso d, paciente p where c.num_cama = $id and c.id_atencion=d.id_atencion and d.Id_exp=p.Id_exp";
            $result = $conexion->query($sql);
            while ($row_datos = $result->fetch_assoc()) {
              $cama_ant=$row_datos['num_cama'];
              ?>
              <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label>HABITACIÓN ACTUAL: </label>
                  <div class="col-md-6">
                    <input type="text" name="num_cama" class="form-control" value="<?php echo $row_datos['num_cama'] ?>" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label > NOMBRE COMPLETO DEL PACIENTE EN LA HABITACIÓN: </label>
                  <div class="col-md-6">
                    <input type="text" name="num_cama" class="form-control" value="<?php echo $row_datos['nom_pac'].' '.$row_datos['papell'].' '.$row_datos['sapell'] ?>" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label>NUEVA HABITACIÓN : </label><br>
                                    
                                    <select name="cama" class="form-control" required>
                                        <?php
                                $resultadoaseg = $conexion->query("SELECT * FROM cat_camas WHERE estatus='LIBRE' ORDER by num_cama ASC") or die($conexion->error);
                                ?>
                                <option value="">SELECCIONAR </option>
                                <?php foreach ($resultadoaseg as $opcionesaseg): ?>


                                    <option value="<?php echo $opcionesaseg['num_cama'] ?>"><?php echo $opcionesaseg['num_cama'].' '. $opcionesaseg['tipo'] ?></option>

                                <?php endforeach ?>
                                    </select>
                </div>
              <?php } ?>
              <div class="form-group">
                  <input type="submit" name="del" class="btn btn-success" value="GUARDAR">
                  <a href="tabla_censo.php" class="btn btn-danger">CANCELAR</a>
              </div>
              </form>
          </div>
          <div class="col-md-2"></div>
        </div>
        <?php

        if (isset($_POST['del'])) {
          $usuario=$_SESSION['login'];
          $id_usua=$usuario['id_usua'];

          $cama    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cama"], ENT_QUOTES))); //nueva cama
          $id    = mysqli_real_escape_string($conexion, (strip_tags($_GET["id_cama"], ENT_QUOTES))); //anterior cama 
          $id_atencion    = mysqli_real_escape_string($conexion, (strip_tags($_GET["id_atencion"], ENT_QUOTES))); //
          $hab    = mysqli_real_escape_string($conexion, (strip_tags($_GET["hab"], ENT_QUOTES))); //1
          $tipo    = mysqli_real_escape_string($conexion, (strip_tags($_GET["tipo"], ENT_QUOTES))); //hospi, tera, ucin,urg


$fecha_actual = date("Y-m-d H:i:s");
 
 $select_cama="SELECT * FROM cat_camas WHERE num_cama=$cama";
 $result_cama=$conexion->query($select_cama);
 while ($row_cam=$result_cama->fetch_assoc()) {
   $tipo_new=$row_cam['tipo'];
   $hab_new=$row_cam['habitacion'];
   $serv_cve = $row_cam['serv_cve'];
 }

 if ($tipo_new != $tipo) {// comparar si la nueva habitacion es igual a la habitacion anterior
  if ($cama >= 101 && $cama <= 210) { //camas standar, se cambia de cama y se agrega a la cuenta

// nueva fecha_cama
 
 
$sql_dia_hab = "UPDATE dat_ingreso SET fecha_cama='$fecha_actual', area='$tipo_new' WHERE id_atencion = $id_atencion";// nueva fecha_cama
$result_dia_hab = $conexion->query($sql_dia_hab);

// insertar nueva cuenta con el tipo de servicio standar=1
$sql2 = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant, cta_tot, id_usua, cta_activo)VALUES($id_atencion,'S',$serv_cve,'$fecha_actual',1,0,$id_usua,'SI')"; 
$result = $conexion->query($sql2);

//// se hace el cambio de cama
$sql2 = "UPDATE cat_camas SET estatus = 'LIBRE', id_atencion=0 WHERE num_cama= $id";
$result = $conexion->query($sql2);

$sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion='$id_atencion' WHERE num_cama = $cama";
$result = $conexion->query($sql2);
     }elseif ($cama >= 301 && $cama <= 304) {
   // nueva fecha_cama
$sql_dia_hab = "UPDATE dat_ingreso SET fecha_cama='$fecha_actual', area='$tipo_new' WHERE id_atencion = $id_atencion";// nueva fecha_cama
$result_dia_hab = $conexion->query($sql_dia_hab);

// insertar nueva cuenta con el tipo de servicio suite=2
$sql2 = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant, cta_tot, id_usua, cta_activo)VALUES($id_atencion,'S',$serv_cve,'$fecha_actual',1,0,$id_usua,'SI')"; 
$result = $conexion->query($sql2);

//// se hace el cambio de cama
$sql2 = "UPDATE cat_camas SET estatus = 'LIBRE', id_atencion=0 WHERE num_cama= $id";
$result = $conexion->query($sql2);

$sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion='$id_atencion' WHERE num_cama = $cama";
$result = $conexion->query($sql2);
 }elseif ($cama >= 1 && $cama <= 5) {
   // nueva fecha_cama
$sql_dia_hab = "UPDATE dat_ingreso SET fecha_cama='$fecha_actual', area='$tipo_new'  WHERE id_atencion = $id_atencion";// nueva fecha_cama
$result_dia_hab = $conexion->query($sql_dia_hab);

// insertar nueva cuenta con el tipo de servicio ucin=4
$sql2 = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant, cta_tot, id_usua, cta_activo)VALUES($id_atencion,'S',$serv_cve,'$fecha_actual',1,0,$id_usua,'SI')"; 
$result = $conexion->query($sql2);

//// se hace el cambio de cama
$sql2 = "UPDATE cat_camas SET estatus = 'LIBRE', id_atencion=0 WHERE num_cama= $id";
$result = $conexion->query($sql2);

$sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion='$id_atencion' WHERE num_cama = $cama";
$result = $conexion->query($sql2);
 }


 }else{//si es igual solo se cambia de cama sin agregar a cuenta
  $sql2 = "UPDATE cat_camas SET estatus = 'LIBRE', id_atencion=0 WHERE num_cama= $id";
          $result = $conexion->query($sql2);

          $sql2 = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion='$id_atencion' WHERE num_cama = $cama";
          $result = $conexion->query($sql2);
 }
          echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato editado correctamente...</p>";
          echo '<script type="text/javascript">window.location.href = "tabla_censo.php";</script>';
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
</body>

</html>