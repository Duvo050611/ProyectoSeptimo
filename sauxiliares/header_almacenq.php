<?php
    // No incluir conexión ni iniciar sesión aquí - debe hacerse en el archivo que incluye este header
    // Solo verificar que la sesión ya esté iniciada y que existan las variables necesarias

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['login'])) {
        // remove all session variables
        session_unset();
        // destroy the session
        session_destroy();
        header('Location: ../../index.php');
        exit;
    }
    $usuario1 = $_SESSION['login'];
    
    // Consultar el rol del usuario en la base de datos para verificación adicional
    // Usar el usuario de la sesión o el id_usua como fallback
    $usuario_buscar = isset($usuario1['usuario']) ? $usuario1['usuario'] : $usuario1['id_usua'];
    
    // Primera búsqueda: por campo 'usuario'
    $query_usuario = "SELECT id_rol, id_usua, nombre, papell, sapell FROM reg_usuarios WHERE usuario = '" . mysqli_real_escape_string($conexion, $usuario_buscar) . "' AND u_activo = 'SI'";
    $resultado_usuario = $conexion->query($query_usuario);
    
    // Si no se encuentra por usuario, buscar por id_usua
    if (!$resultado_usuario || $resultado_usuario->num_rows == 0) {
        $query_usuario = "SELECT id_rol, id_usua, nombre, papell, sapell FROM reg_usuarios WHERE id_usua = '" . mysqli_real_escape_string($conexion, $usuario1['id_usua']) . "' AND u_activo = 'SI'";
        $resultado_usuario = $conexion->query($query_usuario);
    }
    
    if ($resultado_usuario && $resultado_usuario->num_rows > 0) {
        $datos_usuario = $resultado_usuario->fetch_assoc();
        $rol_usuario = $datos_usuario['id_rol'];
        
        // Actualizar datos del usuario con información de la BD si es necesario
        if (!isset($usuario1['id_rol']) || $usuario1['id_rol'] != $rol_usuario) {
            $usuario1['id_rol'] = $rol_usuario;
        }
    } else {
        // Usuario no encontrado en la base de datos o inactivo
        session_unset();
        session_destroy();
        header('Location: ../../index.php?error=Usuario no válido');
        exit;
    }
    
    // Validar si el rol del usuario tiene permisos para almacén quirófano
    if (!in_array($rol_usuario, [1, 3, 4, 5, 9, 11])) {
        // Usuario sin permisos
        session_unset();
        session_destroy();
        header('Location: ../../index.php?error=Sin permisos');
        exit;
    }
?>
