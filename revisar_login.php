<?php
include 'conexionbd.php';
session_start();
if(isset($_POST['usuario']) && isset($_POST['pass'])) {
    $usuario=$conexion->real_escape_string($_POST['usuario']);
    $pass=$conexion->real_escape_string($_POST['pass']);
    $resultado = $conexion->query("select * from reg_usuarios where usuario='$usuario' and pass='$pass' and u_activo='SI' limit 1") or die($conexion->error);
    if (mysqli_num_rows($resultado) > 0) {
        $datos_usuario = mysqli_fetch_row($resultado);
        $nombre = $datos_usuario[2];
        $papell = $datos_usuario[3];
        $sapell = $datos_usuario[4];
        $id_usua = $datos_usuario[0];
        $cedp = $datos_usuario[7];
        $id_rol = $datos_usuario[13];
        $img_perfil = $datos_usuario[15];
        $firma = $datos_usuario[16];
        $usuario = $datos_usuario[18];
        $_SESSION['login'] = array(
            'nombre' => $nombre,
            'papell' => $papell,
            'sapell' => $sapell,
            'id_usua' => $id_usua,
            'cedp' => $cedp,
            'id_rol' => $id_rol,
            'img_perfil' => $img_perfil,
            'firma' => $firma

        );
$_SESSION['id_usua'] = $id_usua;
        if ($id_rol == '1') {
            header('Location: ./template/menu_administrativo.php');

        } elseif ($id_rol == '2') {
            header('Location: ./template/menu_medico.php');

        } elseif ($id_rol == '3') {
            header('Location: ./template/menu_enfermera.php');

        } elseif ($id_rol == '4') {
            header('Location: ./template/menu_sauxiliares.php');

        } elseif ($id_rol == '5' and $id_usua != '429') {
            header('Location: ./template/menu_gerencia.php');//root

        }elseif ($id_rol == '6') {
            header('Location: ./template/menu_configuracion.php');
        }elseif ($id_rol == '7') {
            header('Location: ./template/menu_farmacia.php');
        }elseif ($id_rol == '8') {
            header('Location: ./template/menu_ceye.php');
        }elseif ($id_rol == '9') {
            header('Location: ./template/menu_imagenologia.php');
        }elseif ($id_rol == '10') {
            header('Location: ./template/menu_laboratorio.php');

        }elseif ($id_rol == '11') {
            header('Location: ./template/menu_almacencentral.php');
        }elseif ($id_rol == '12') {
            header('Location: ./template/menu_residente.php');
        }elseif ($id_rol == '13') {
            header('Location: ./template/menu_patologia.php');
        }elseif ($id_rol == '14') {
            header('Location: ./template/menu_mantenimiento.php');
        }elseif ($id_rol == '15') {
            header('Location: ./template/menu_biomedica.php');
        }elseif ($id_rol == '16') {
            header('Location: ./template/menu_intendencia.php');
        }elseif ($id_rol == '17') {
            header('Location: ./template/menu_calidad.php');
        }elseif ($id_rol == '5' and $id_usua == '429') {
            header('Location: ./template/menu_ejecutivo.php');
        }elseif ($id_rol == '19') {
            header('Location: ./template/menu_certificacion.php');
        }else {
            header('Location: index.php?error=Credenciales incorrectas');
        }
/*        	die('Bienvenido '.$nombre);
            }else{
                    header('Location: index.php?error=Credenciales incorrectas');
            }*/

    } else {
        header('Location: index.php?error=Credenciales incorrectas');
    }
}
