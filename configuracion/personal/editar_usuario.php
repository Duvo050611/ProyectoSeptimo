<?php
session_start();
include "../../conexionbd.php";
include "../../configuracion/header_configuracion.php";

if( isset($_GET['id_usua'])) { //si existe el id mandado por metodo GET, se hara la consulta donde el id debe ser igual al id mandado por el metodo GET
  $resultado = $conexion ->query("SELECT * FROM reg_usuarios WHERE id_usua=".$_GET['id_usua'])or die($conexion->error);
  if(mysqli_num_rows($resultado) > 0 ){ //se mostrara si existe mas de 1
    $f=mysqli_fetch_row($resultado);

  }else{
    header("location: alta_usuarios.php"); //te regresa a la página principal
  }
}else{
  header("location: alta_usuarios.php"); //te regresa a la página principal
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="gb18030">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
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

</head>

<body>
<div class="container-fluid">
    <form action="" method="POST" enctype="multipart/form-data"> 
    <div class="row">       
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php
                        $id_usua= $_GET['id_usua'];
                        ?>
                        <input type="hidden" name="id_usua" placeholder="Expediente" id="id_usua" class="form-control" value="<?php echo $id_usua ?>" 
                               disabled>
                    </div>
                </div>
         </div> 
         <div class="row">
    <div class="col">
       No: <strong><?php echo $f[0];?></strong> <br>
     Usuario: <strong><?php echo $f[2];?></strong><br>
     Nombre completo: <strong><?php echo $f[3];?></strong><br>
      
Fecha de nacimiento:<strong> <?php  $date = date_create($f[5]);
 echo date_format($date,"d/m/Y");?></strong>
    </div>
  
  </div> <br>
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
            <tr><strong><center>EDITAR USUARIO</center></strong>
      </div><br>
<div class="container-fluid">
       <div class="row">

             <div class="col-sm-4">
                <div class="form-group">
                    <label>CURP:</label><br>
                    <input type="text" name="curp_u" class="form-control" value="<?php echo $f[1];?>" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
             </div>
        </div>     
        <div class="row">
          
              <div class="col-sm-4">
                <div class="form-group">
                    <label>Primer Apellido:</label><br>
                    <input type="text" name="papell" class="form-control" value="<?php echo $f[3];?>" >
                </div>
             </div>
              <div class="col-sm-4">
                <div class="form-group">
                    <label>Segundo Apellido:</label><br>
                    <input type="text" name="sapell" class="form-control" value="<?php echo $f[4];?>" >
                </div>
             </div>
               <div class="col-sm-4">
                <div class="form-group">
                    <label>Nombre:</label><br>
                    <input type="text" name="nombre" class="form-control" value="<?php echo $f[2];?>" >
                </div>
             </div>
       </div>

       <div class="row">
             
              <div class="col-sm-4">
                        <div class="form-group">
                            <label for="fecnac">Fecha de nacimiento:</label>
                    
                          <input type="date" name="fecnac" value="<?php echo $f[5];?>" id="fecnac"
                                   class="form-control" required>
                        </div>
                    </div>
                
      
             <div class="col-sm-4">
                <div class="form-group">
                   <label>Cédula profesional:</label><br>
                    <input type="text" name="cedp" class="form-control" value="<?php echo $f[7];?>">
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Función:</label><br>
                    <input type="text" name="cargp" class="form-control" value="<?php echo $f[8];?>">
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Teléfono:</label><br>
                    <input type="text" name="tel" class="form-control" value="<?php echo $f[9];?>" >
                </div>
             </div>     
      
             <div class="col-sm-4">
                <div class="form-group">
                    <label>e-mail :</label><br>
                    <input type="text" name="email" class="form-control" value="<?php echo $f[10];?>">
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Pefijo:</label><br>
                    <input type="text" name="pre" class="form-control" value="<?php echo $f[11];?>">
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Usuario:</label><br>
                    <input type="text" name="usu" class="form-control" value="<?php echo $f[18];?>">
                </div>
                </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Contraseña:</label><br>
                    <input type="password" name="pass" class="form-control" value="<?php echo $f[12];?>">
                </div>
             </div>     
      
             <div class="col-sm-4">
                <div class="form-group">
                     <label >Rol de acceso:</label>
                      <?php 
$usuario=$_SESSION['login'];
$rol=$usuario['id_rol'];
if($rol == 1 ){
$resultado1 = $conexion ->query("SELECT * FROM rol where id_rol!=5")or die($conexion->error); ?>
<select name="id_rol"class="form-control" required >
<option value="<?php echo $f[13];?>"><?php echo $f[13];?></option>
<option></option>
<option value="">Seleccionar</option>
<?php foreach ($resultado1 as $opciones):?>
    <option value="<?php echo $opciones['id_rol']?>"><?php echo $opciones['rol']?></option>
    <?php endforeach?>
</select>
<?php }elseif($rol == 5){
    $resultado1 = $conexion ->query("SELECT * FROM rol")or die($conexion->error); ?>
<select name="id_rol"class="form-control" required >
    <option value="<?php echo $f[13];?>"><?php echo $f[13];?></option>
<option value="">Seleccionar</option>
<?php foreach ($resultado1 as $opciones):?>
    <option value="<?php echo $opciones['id_rol']?>"><?php echo $opciones['rol']?></option>
    <?php endforeach?>
</select>
<?php }elseif($rol == 12){
    $resultado1 = $conexion ->query("SELECT * FROM rol where id_rol=2 or id_rol=3 or id_rol=12")or die($conexion->error); ?>
<select name="id_rol"class="form-control" required >
<option value="<?php echo $f[13];?>"><?php echo $f[13];?></option>
<option value="">Seleccionar</option>
<?php foreach ($resultado1 as $opciones):?>
    <option value="<?php echo $opciones['id_rol']?>"><?php echo $opciones['rol']?></option>
    <?php endforeach?>
</select>
<?php } ?>
                      
                  </div>
             </div>

              <div class="col-sm-4">
    <label for="img_perfil"><strong><font size="2">Seleccionar imagen del perfil</font></strong></label>
    <input type="file" class="form-control-file" id="img_perfil" name="img_perfil">
    </div>
<div class="col-sm-4">
    <label for="firma"><strong><font size="2">Seleccionar archivo de firma</font></strong></label>
    <input type="file" class="form-control-file" id="firma" name="firma">
    </div>
             </div>                
       </div>
<hr>

    <center>
            <button type="submit" name="guardar" class="btn btn-success">Guardar</button>
            <a href="alta_usuarios.php" class="btn btn-danger">Cancelar</a>
    </center>

</form>
</div>
<br>

<?php
if (isset($_POST['guardar'])) {

        $curp_u    = mysqli_real_escape_string($conexion, (strip_tags($_POST["curp_u"], ENT_QUOTES))); //Escanpando caracteres
        $nombre    = mysqli_real_escape_string($conexion, (strip_tags($_POST["nombre"], ENT_QUOTES))); //Escanpando caracteres
        $papell    = mysqli_real_escape_string($conexion, (strip_tags($_POST["papell"], ENT_QUOTES))); //Escanpando caracteres
        $sapell   = mysqli_real_escape_string($conexion, (strip_tags($_POST["sapell"], ENT_QUOTES))); //Escanpando caracteres

          $fecnac    = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecnac"], ENT_QUOTES)));
         
          $cedp    = mysqli_real_escape_string($conexion, (strip_tags($_POST["cedp"], ENT_QUOTES)));

          $cargp   = mysqli_real_escape_string($conexion, (strip_tags($_POST["cargp"], ENT_QUOTES)));
           $tel    = mysqli_real_escape_string($conexion, (strip_tags($_POST["tel"], ENT_QUOTES)));
          $email    = mysqli_real_escape_string($conexion, (strip_tags($_POST["email"], ENT_QUOTES)));
          $pre    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pre"], ENT_QUOTES)));
          $usu    = mysqli_real_escape_string($conexion, (strip_tags($_POST["usu"], ENT_QUOTES)));
          $pass    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pass"], ENT_QUOTES)));
          $id_rol    = mysqli_real_escape_string($conexion, (strip_tags($_POST["id_rol"], ENT_QUOTES)));
        
//imagen1 EDITAR
        if($_FILES['img_perfil']['name']!=''){
    $nombr = $_FILES['img_perfil']['name'];
    $carpeta="../../imagenes/";
//imagen1.jpg
            $temp=explode('.' ,$nombr);
        $extension= end($temp);
        $img=time().'.'.$extension;

        if($extension=='jpg' || $extension=='png' || $extension=='jpeg'){

        if(move_uploaded_file($_FILES['img_perfil']['tmp_name'], $carpeta.$img)){
            $fila= $conexion->query('select img_perfil from reg_usuarios where id_usua='.$_GET['id_usua']);
            $id=mysqli_fetch_row($fila);
            if(file_exists('../../imagenes/'.$id[0])){
            unlink('../../imagenes/'.$id[0]);
                }
            $conexion->query("update reg_usuarios set img_perfil='".$img."' where id_usua=".$_GET['id_usua']);
                }
            }//llave tipo archivo
        }    //llave si no esta vacio

//firma EDITAR
        if($_FILES['firma']['name']!=''){
    $nombrf = $_FILES['firma']['name'];
    $carpetaf="../../imgfirma/";
//firma.jpg
            $tempf=explode('.' ,$nombrf);
        $extensionf= end($tempf);
        $imgf=time().'.'.$extensionf;

        if($extensionf=='jpg' || $extensionf=='png' || $extensionf=='jpeg'){

        if(move_uploaded_file($_FILES['firma']['tmp_name'], $carpetaf.$imgf)){
            $filaf= $conexion->query('select firma from reg_usuarios where id_usua='.$_GET['id_usua']);
            $id=mysqli_fetch_row($filaf);
            if(file_exists('../../imgfirma/'.$id[0])){
            unlink('../../imgfirma/'.$id[0]);
                }
            $conexion->query("update reg_usuarios set firma='".$imgf."' where id_usua=".$_GET['id_usua']);
                }
            }//llave tipo archivo
        }    //llave si no esta vacio


        
        $sql2 = "UPDATE reg_usuarios SET curp_u='$curp_u' , nombre='$nombre', papell='$papell', sapell='$sapell', fecnac='$fecnac', tel='$tel', email='$email', pre='$pre', usuario='$usu',pass='$pass', id_rol=$id_rol, cedp='$cedp', cargp='$cargp' WHERE id_usua= ".$_GET['id_usua'];
     // echo $sql2;
      // return 'hbgk';
        $result = $conexion->query($sql2);
        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
        echo '<script type="text/javascript">window.location ="alta_usuarios.php"</script>';
      }
?>
 </div>

    <footer class="main-footer">
        <?php
        include("../../template/footer.php");
        ?>
    </footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>
</html>