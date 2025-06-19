<?php 
include "../../conexionbd.php";


/*$res = "SELECT * FROM reg_usuarios where id_usua=".$_GET['id_usua'];
        $result_i = $conexion->query($res);
while ($row_res = $result_i->fetch_assoc()) {
  $id_usua = $row_res['id_usua'];
}

$curp_u=($_POST['curp_u']);
$nombre=($_POST['nombre']);
$papell=($_POST['papell']);
$sapell=($_POST['sapell']);
$fecnac=($_POST['fecnac']);
$cedp=($_POST['cedp']);
$cargp=($_POST['cargp']);
$tel=($_POST['tel']);
$email=($_POST['email']);
$pre=($_POST['pre']);
$pass=($_POST['pass']);
$id_rol=($_POST['id_rol']);


//imagen1 EDITAR
        if($_FILES['img_perfil']['name']!=''){
    $nombr = $_FILES['img_perfil']['name'];
    $carpeta="../../imagenes/";
//imagen.jpg
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

        
        $sql2 = "UPDATE reg_usuarios SET curp_u='$curp_u', nombre='$nombre', papell='$papell', sapell='$sapell', fecnac='$fecnac', tel='$tel', email='$email', pre='$pre', pass='$pass', id_rol=$id_rol, cedp='$cedp', cargp='$cargp' WHERE id_usua=".$id_usua;
     // echo $sql2;
      // return 'hbgk';
        $result = $conexion->query($sql2);
        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
        echo '<script type="text/javascript">window.location ="alta_usuarios.php"</script>';

*/

//otro
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
          $pass    = mysqli_real_escape_string($conexion, (strip_tags($_POST["pass"], ENT_QUOTES)));
          $id_rol    = mysqli_real_escape_string($conexion, (strip_tags($_POST["id_rol"], ENT_QUOTES)));
        
        $sql2 = "UPDATE reg_usuarios SET curp_u='$curp_u' , nombre='$nombre', papell='$papell', sapell='$sapell', fecnac='$fecnac', tel='$tel', email='$email', pre='$pre', pass='$pass', id_rol=$id_rol, cedp='$cedp', cargp='$cargp' WHERE id_usua= ".$_GET['id_usua'];
     // echo $sql2;
      // return 'hbgk';
        $result = $conexion->query($sql2);
        echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
        echo '<script type="text/javascript">window.location ="alta_usuarios.php"</script>';
      }
      
?>