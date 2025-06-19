<?php
session_start();
//include "../../conexionbd.php";
include "../header_configuracion.php";
$resultado=$conexion->query("select * from reg_usuarios") or die($conexion->error);

$usuario=$_SESSION['login'];
$rol=$usuario['id_rol'];
$id_usu=$usuario['id_usua'];
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
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>LISTA DE PERSONAL</center></strong>
</div><br>

<div class="container">
    <center>
    <div class="row">
        <div class="col-sm-1">
        <div class="form-group"> 
           <?php if($id_usu == 1){?>
            <a href="excelpersonal.php"><button type="button" class="btn btn-warning btn-sm">
                <img src="https://img.icons8.com/color/48/000000/ms-excel.png" width="42"/><strong>Exportar a excel</strong></button></a>
              <?php }?>
        </div>
    </div>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 <div class="col-sm-1">
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i><font id="letra"> Nuevo usuario </font></button>
          </div>
    </div>
</div>

<!-- Modal Insertar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="insertar_usuario.php" method="POST" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">NUEVO USUARIO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      
      </div>
      <div class="modal-body">

 <div class="form-group">
            <label for="curp_u">CURP:</label>
            <input type="text" size="18" name="curp_u" id="curp_u" class="form-control" style="text-transform:uppercase;" value=""  onkeyup="javascript:this.value=this.value.toUpperCase();" required>
          </div> 

        
          <div class="form-group">
            <label for="papell">Primer Apellido:</label>
            <input type="text" name="papell" id="papell" class="form-control"  required>
          </div>

        <div class="form-group">
            <label for="sapell">Segundo Apellido:</label>
            <input type="text" name="sapell" id="sapell" class="form-control"  required>
         </div> 
         <div class="form-group">
            <label for="nombre">Nombre(s):</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
          </div> 

<div class="form-group">
            <label for="fecha">Fecha de nacimiento:</label>
            <input type="date" name="fecnac"  id="fecha" class="form-control" required>
          </div>

<div class="form-group">
            <label for="cedp">Cédula profesional:</label>
            <input type="text" name="cedp"  id="cedp" class="form-control">
          </div>
<div class="form-group">
            <label for="cargp">Especialidad:</label>
            <input type="text" name="cargp"  id="cargp" class="form-control">
          </div>
<div class="form-group">
            <label for="tel">Teléfono:</label>
            <input type="number"  name="tel" id="tel" class="form-control" required>
          </div>
<div class="form-group">
            <label for="email">e-mail:</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>

      <div class="form-group">
<label for="pre">Prefijo:</label>
<select name="pre"class="form-control" required >
<option value="">Seleccionar</option>
<option value="Dra">Dra</option>
<option value="Dr">Dr</option>
<option value="Lic">Lic</option>
<option value="L.N">L.N.</option>
<option value="Psic">Psic</option>   
<option value="Enf">Enf</option> 
<option value="I.Q">I.Q</option> 
<option value="Rad">Rad</option>
<option value="C">C.</option>
<option value="Ing">Ing.</option> 
</select>
</div>

<div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="nombre" class="form-control" required>
          </div> 
<div class="form-group">
            <label for="pass">Contraseña:</label>
            <input type="password" name="pass"  id="pass" class="form-control" required>
          </div>  

          <div class="form-group">
            <label for="img_perfil">Imagen del perfil:</label>
            <input type="file" name="img_perfil" id="img_perfil" class="form-control" required="">
          </div>
          
          <div class="form-group">
            <label for="firma">Firma digitalizada:</label>
            <input type="file" name="firma" id="firma" class="form-control" required="">
          </div>        
          
          <div class="form-group">
<label for="id_usua">Rol de acceso:</label>
<?php 
$usuario=$_SESSION['login'];
$rol=$usuario['id_rol'];
if($rol == 1 ){
$resultado1 = $conexion ->query("SELECT * FROM rol where id_rol!=5")or die($conexion->error); ?>
<select name="id_rol"class="form-control" required >
<option value="">Seleccionar</option>
<?php foreach ($resultado1 as $opciones):?>
    <option value="<?php echo $opciones['id_rol']?>"><?php echo $opciones['rol']?></option>
    <?php endforeach?>
</select>
<?php }elseif($rol == 5){
    $resultado1 = $conexion ->query("SELECT * FROM rol")or die($conexion->error); ?>
<select name="id_rol"class="form-control" required >
<option value="">Seleccionar</option>
<?php foreach ($resultado1 as $opciones):?>
    <option value="<?php echo $opciones['id_rol']?>"><?php echo $opciones['rol']?></option>
    <?php endforeach?>
</select>
<?php }elseif($rol == 12){
    $resultado1 = $conexion ->query("SELECT * FROM rol where id_rol=2 or id_rol=3 or id_rol=12")or die($conexion->error); ?>
<select name="id_rol"class="form-control" required >
<option value="">Seleccionar</option>
<?php foreach ($resultado1 as $opciones):?>
    <option value="<?php echo $opciones['id_rol']?>"><?php echo $opciones['rol']?></option>
    <?php endforeach?>
</select>
<?php } ?>

</div>

<!--<label>ACCESO A:</label>

<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">Enfermeria  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">Hospitalizacion</label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">Urgencias</label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">Quirofano</label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">Farmacia</label>
</div>-->


      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-success">Guardar</button>
         <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
    </form>

  </div>
</div>

<section class="content container-fluid">

    <!--------------------------
    | Your Page Content Here |
    -------------------------->


    <div class="container box">
        <div class="content">


            <?php

            include "../../conexionbd.php";



           // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios";

          //  $result = $conn->query($sql);

            $resultado2=$conexion->query("SELECT id_usua, curp_u, nombre, papell,sapell,fecnac,mat,cedp,cargp,email,u_activo FROM reg_usuarios") or die($conexion->error);
            ?>


            <!--Fin de los filtros-->


            <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
            </div>

            <div class="table-responsive">
                <!--<table id="myTable" class="table table-striped table-hover">-->

                <table  class="table table-bordered table-striped" id="mytable">
                    <thead class="thead" style="background-color: #2b2d7f;color:white;">
                    <tr>
                        <th>Editar</th>
                        <th>Ver datos</th>
                        <th>Id</th>
                        <th>Usuario</th>
                        <th>Primer apellido</th>
                        <th>Segundo apellido</th>
                        <th>Nombre(s)</th>                       
                        <th>Cédula profesional</th>
                        <th>Función</th>
                        <th>Activo</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                   // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
                  //  $result = $conn->query($sql);
                    $resultado2=$conexion->query("SELECT id_usua, curp_u, nombre,papell,sapell,fecnac,mat,cedp,cargp,tel,email,u_activo,usuario FROM reg_usuarios") or die($conexion->error);

                    $no = 1;
                    while ($row = $resultado2->fetch_assoc()) {
                         $eid = $row['id_usua'];
                        echo '<tr>'
                        . '<td> <a href="editar_usuario.php?id_usua=' . $row['id_usua'] . '" title="Editar datos" class="btn btn-warning btn-sm "><span class="fa fa-edit" aria-hidden="true"></span></a></td>'

                        . '<td> <a href="mostrar_usuario.php?id_usua=' . $row['id_usua'] . '" title="Mostrar datos" class="btn btn-warning btn-sm "><span class="fa fa-indent" aria-hidden="true"></span></a></td>'
                            . '<td>' . $row['id_usua'] . '</td>'
                          
                            . '<td>'. $row['usuario'] . '</a></td>'
                            . '<td>' . $row['papell']. '</td>'
                            . '<td>' . $row['sapell']. '</td>'
                           . '<td>' . $row['nombre'] . '</td>'
                            . '<td>' . $row['cedp'] . '</td>'
                            . '<td>' . $row['cargp'] . '</td>'
                             ?>
                  <form class="form-horizontal title1" name="form" action="insertar_servicio.php?q=estatus"
                          method="POST" enctype="multipart/form-data">
                        <?php
                        echo '<td>';
                        if ((strpos($row['u_activo'], 'NO') !== false)) {
                            echo '<a type="submit" class="btn btn-danger btn-sm" href="activo.php?q=estatus&eid=' . $eid . '&est=' . $row['u_activo'] . '"><span class = "fa fa-power-off"></span></a>';

                        } else {
                            echo '<a type="submit" class="btn btn-success btn-sm" href="activo.php?q=estatus&eid=' . $eid . '&est=' . $row['u_activo'] . '"><span class = "fa fa-power-off"></span></a>';
                        }

                            echo '</tr>';
                        $no++;
                    }

                    ?>


                    </tbody>
                </table>

            </div>


</section>
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