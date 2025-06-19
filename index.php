<!DOCTYPE html>
<?php
include "conexionbd.php"; ?>

<head>
    <title>INEO Metepec</title>
    <link rel="icon" href="imagenes/SIF.PNG">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    
    #enclogo{
                left:80%;
                margin-top:-100px;
                  
                   
            }
 @media screen and (max-width: 980px){
            #enclogo{
                left:80%;
                margin-top:-40px;
                  
                   
            }
}

    </style>
</head>
<body>
	<img class="wave" src="imagenes/wave.png" class="img-fluid">
	<div class="container">
		<div class="img">
		    
			<?php
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_simg=$f['id_simg'];
?>
            
          <?php
}
?>
		</div>
		<div class="login-content">
		    
			<form action="revisar_login.php" method="POST">
			    
			<?php
$resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_simg=$f['id_simg'];
?>
            <center><span class="fondo"><img src="../../configuracion/admin/img/<?php echo $f['img_base']?>" alt="imgsistema" height="800" width="310"></span></center>
          <?php
}
?>
				 <br>
      		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   
           		   <div class="div">
           		   		<h5>Usuario:</h5>
           		   		<input type="text" class="input" id="usuario" name="usuario" required>
           		   </div>
           		    
           		</div>
           		<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		    	<h5>Contraseña:</h5>
           		    	<input type="password" class="input" id="pass" name="pass" required>
            	   </div>
            	    
            	    
            	</div><br>
            	 <div id="alerta"></div>
            	 <?php
if(isset($_GET['error'])){
    echo '<center><div class="alert alert-danger">'.$_GET['error'].'</div></center>';
}
?>
            	<!--<a href="alta_usuarios.php">¿Quieres registrar un usuario?</a-->
            	<input type="submit" class="btn" value="Entrar">
            
            </form>
            
        </div>	


    </div>
    
    <script type="text/javascript" src="js/main.js">    </script>
    
  <script>
      
      document.oncontextmenu = function(){return false;}
  </script>  

</body>
</html>