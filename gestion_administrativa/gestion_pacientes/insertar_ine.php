<?php
 
include "../../conexionbd.php";

$id= $_GET['Id_exp'];


$name = $_FILES['img_inef']['name'];
  $carpeta = "../../ine_frontal/";
  $temp = explode('.', $name);
  $extension = end($temp);
  $nombreFinal = time() . '.' . $extension;

  $trasera = $_FILES['img_inet']['name'];
  $carpetatrasera = "../../ine_trasera/";
  $temptrasera = explode('.', $trasera);
  $extensiontrasera = end($temptrasera);
  $nombreFinaltrasera = time() . '.' . $extensiontrasera;

  $namer = $_FILES['img_inefr']['name'];
  $carpetar = "../../ine_frontalr/";
  $tempr = explode('.', $namer);
  $extensionr = end($tempr);
  $nombreFinalr = time() . '.' . $extensionr;

  $traserar = $_FILES['img_inetr']['name'];
  $carpetatraserar = "../../ine_traserar/";
  $temptraserar = explode('.', $traserar);
  $extensiontraserar = end($temptraserar);
  $nombreFinaltraserar = time() . '.' . $extensiontraserar;

  if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
    if (move_uploaded_file($_FILES['img_inef']['tmp_name'], $carpeta . $nombreFinal)) {
      if (move_uploaded_file($_FILES['img_inet']['tmp_name'], $carpetatrasera . $nombreFinaltrasera)) {
        if (move_uploaded_file($_FILES['img_inefr']['tmp_name'], $carpetar . $nombreFinalr)) {
          if (move_uploaded_file($_FILES['img_inetr']['tmp_name'], $carpetatraserar . $nombreFinaltraserar)) {
         date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
    $ingresar = mysqli_query($conexion,'insert into ine (Id_exp,img_inef,img_inet,img_inefr,img_inetr,fecha) values ('.$id.',"' . $nombreFinal . '","' . $nombreFinaltrasera . '","' . $nombreFinalr . '","' . $nombreFinaltraserar . '","'.$fecha_actual.'")') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

    $sql3 = "UPDATE paciente SET INE='SI' WHERE Id_exp=$id";
      $result = $conexion->query($sql3);



         header('location: vista_paciente.php');
          } else {
            echo 'Error en la Foto INE trasera responsable';
                 } //si no se enviaron datos
        } else {
          echo 'Error en la Foto INE delantera responsable';
                }
      } else {
        echo 'Error en la Foto INE trasera paciente';
            }
    } else {
      echo 'Error en la Foto INE delantera paciente';
        }     
  } 

?>