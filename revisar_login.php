<?php
include 'conexionbd.php';
session_start();

// Verificar que los campos existan
if (!isset($_POST['usuario']) || !isset($_POST['pass'])) {
    header('Location: index.php?error=Faltan datos');
    exit();
}

$usuario = $conexion->real_escape_string($_POST['usuario']);
$pass = $_POST['pass'];

// Traer los datos del usuario
$resultado = $conexion->query("
    SELECT * FROM reg_usuarios 
    WHERE usuario = '$usuario'
    AND u_activo = 'SI'
    LIMIT 1
") or die($conexion->error);

// Validar si existe el usuario
if ($resultado->num_rows == 0) {
    header('Location: index.php?error=Usuario no encontrado');
    exit();
}

$datos_usuario = mysqli_fetch_assoc($resultado);
$pass_bd = $datos_usuario['pass'];

// --------------------------------------------
//   VALIDACIÓN DE CONTRASEÑA (HASH + LEGADO)
// --------------------------------------------

// Caso A: Contraseña YA está hasheada correctamente
if (password_verify($pass, $pass_bd)) {

    // No hacer nada, login correcto

}
// Caso B: Contraseña no está hasheada (texto plano antiguo)
// Se detecta si el valor en BD coincide literal con el que ingresó el usuario
else if ($pass_bd === $pass) {

    // Convertir automáticamente a hash
    $nuevoHash = password_hash($pass, PASSWORD_BCRYPT);

    $conexion->query("
        UPDATE reg_usuarios 
        SET pass = '$nuevoHash'
        WHERE id_usua = {$datos_usuario['id_usua']}
    ");

    // Login correcto (ya está migrado)
}
// Caso C: Contraseña incorrecta
else {
    header('Location: index.php?error=Contraseña incorrecta');
    exit();
}

// --------------------------------------------
//  LOGIN EXITOSO: CREAR SESIÓN
// --------------------------------------------

$_SESSION['login'] = [
    'nombre'   => $datos_usuario['nombre'],
    'papell'   => $datos_usuario['papell'],
    'sapell'   => $datos_usuario['sapell'],
    'id_usua'  => $datos_usuario['id_usua'],
    'cedp'     => $datos_usuario['cedp'],
    'id_rol'   => $datos_usuario['id_rol'],
    'img_perfil' => $datos_usuario['img_perfil'],
    'firma'      => $datos_usuario['firma'],
    'usuario'    => $datos_usuario['usuario']
];

$_SESSION['id_usua'] = $datos_usuario['id_usua'];

$id_rol = $datos_usuario['id_rol'];
$id_usua = $datos_usuario['id_usua'];

// --------------------------------------------
//    REDIRECCIONES SEGÚN TUS ROLES (igual)
// --------------------------------------------

// Ejemplo: modifica según tus rutas
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

exit();
?>
