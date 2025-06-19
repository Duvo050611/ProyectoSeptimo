<?php
session_start();
//include "../../conexionbd.php";
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

    <title>Despliega usuario </title>

</head>

<body>
<div class="container-fluid">
    <form action="" method="POST"> 
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
     Usuario: <strong><?php echo $f[18];?></strong><br>
     Nombre completo: <strong><?php echo $f[2].' '.$f[3].' '.$f[4];?></strong><br>
      
Fecha de nacimiento:<strong> <?php  $date = date_create($f[5]);
 echo date_format($date,"d/m/Y");?></strong>
    </div>
  
  </div> 
       <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
            <tr><strong><center>DATOS DEL USUARIO</center></strong>
       </div>
<br>
<div class="container">
     <div class="col-sm-4">
        <div class="row">
                <div class="form-group">
                    <label>CURP:</label><br>
                    <input type="text" name="curp_u" class="form-control" value="<?php echo $f[1];?>" disabled >
                </div>
             </div>
        </div>
        <div class="row">
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Primer Apellido:</label><br>
                    <input type="text" name="papell" class="form-control" value="<?php echo $f[3];?>" disabled >
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Segundo Apellido:</label><br>
                    <input type="text" name="sapell" class="form-control" value="<?php echo $f[4];?>" disabled >
                </div>
             </div>
                          <div class="col-sm-4">
                <div class="form-group">
                    <label>Nombre:</label><br>
                    <input type="text" name="nombre" class="form-control" value="<?php echo $f[2];?>" disabled >
                </div>
             </div>
       </div>
</div>
<div class="container">
       <div class="row">
             
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Fecha de nacimiento:</label><br>
                    <input type="text" name="fecnac" class="form-control" value="<?php echo $f[5];?>" disabled >
                </div>
             </div>
            
      
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Cédula profesional:</label><br>
                    <input type="text" name="cedp" class="form-control" value="<?php echo $f[7];?>" disabled >
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                   <label>Función:</label><br>
                    <input type="text" name="cargp" class="form-control" value="<?php echo $f[8];?>" disabled >
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Teléfono:</label><br>
                    <input type="text" name="tel" class="form-control" value="<?php echo $f[9];?>" disabled >
                </div>
             </div>     
       
             <div class="col-sm-4">
                <div class="form-group">
                    <label>e-mail:</label><br>
                    <input type="text" name="email" class="form-control" value="<?php echo $f[10];?>" disabled >
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Prefijo:</label><br>
                    <input type="text" name="pre" class="form-control" value="<?php echo $f[11];?>" disabled >
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Usuario:</label><br>
                    <input type="text" name="usuario" class="form-control" value="<?php echo $f[18];?>" disabled >
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Contraseña:</label><br> 
                    <input type="password" name="pass" class="form-control" value="<?php echo $f[12];?>" disabled >
                </div>
             </div>     
      
             <div class="col-sm-4">
                <div class="form-group">
                      <label class="control-label col-sm-7" for="">Rol de acceso:</label><br>
                      <div class="col-sm-9">
                          <select id="item-type" class="form-control" disabled name="id_rol">
                            <option value="<?php echo $f[13];?>"><?php echo $f[13];?></option>
                              <?php
                              $query = "SELECT * FROM `rol`";
                              $result = $conexion->query($query);
                              //$result = mysql_query($query);
                              while ($row = $result->fetch_assoc()) {
                                  echo "<option value='" . $row['id_rol'] . "'>" . $row['rol'] . "</option>";
                              }
                              ?>
                          </select>
                      </div>
                  </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Imagen del perfil</label><br> 
                  <img src="../../imagenes/<?php echo $f['15']; ?>" class="user-image" alt="User Image" width="100">
                </div>
             </div>
                 <div class="col-sm-4">
                <div class="form-group">
                    <label>Firma</label><br> 
                  <img src="../../imgfirma/<?php echo $f['16']; ?>" class="user-image" alt="User Image" width="160">
                </div>
             </div>
       </div>
</div>

    <center>
              
               <a href="alta_usuarios.php" class="btn btn-danger">Regresar...</a>
                    
    </center>


</form>
</div>



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