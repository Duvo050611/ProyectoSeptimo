<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital']) || !isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_atencion = $_SESSION['hospital'];
    $id_usua = isset($_POST['id_usua']) ? (int)$_POST['id_usua'] : 0;
    
    // Validate id_usua
    if ($id_usua === 0) {
        $_SESSION['message'] = "Error: ID de usuario no válido.";
        $_SESSION['message_type'] = "danger";
        header("Location: lente_intraocular.php");
        exit();
    }
    
    // Retrieve Id_exp from dat_ingreso
    $sql = "SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        $_SESSION['message'] = "Error preparando la consulta: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: lente_intraocular.php");
        exit();
    }
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $id_exp = $row['Id_exp'];
    } else {
        $_SESSION['message'] = "Error: No se encontró Id_exp para id_atencion = $id_atencion";
        $_SESSION['message_type'] = "danger";
        header("Location: lente_intraocular.php");
        exit();
    }
    $stmt->close();
    
    // Sanitize and retrieve form data
    $lente_derecho = isset($_POST['lente_derecho']) ? 1 : 0;
    $marca_derecho = isset($_POST['marca_derecho']) ? $conexion->real_escape_string($_POST['marca_derecho']) : '';
    $modelo_derecho = isset($_POST['modelo_derecho']) ? $conexion->real_escape_string($_POST['modelo_derecho']) : '';
    $otros_derecho = isset($_POST['otros_derecho']) ? $conexion->real_escape_string($_POST['otros_derecho']) : '';
    $dioptrias_derecho = isset($_POST['dioptrias_derecho']) ? $conexion->real_escape_string($_POST['dioptrias_derecho']) : '';
    
    $lente_izquierdo = isset($_POST['lente_izquierdo']) ? 1 : 0;
    $marca_izquierdo = isset($_POST['marca_izquierdo']) ? $conexion->real_escape_string($_POST['marca_izquierdo']) : '';
    $modelo_izquierdo = isset($_POST['modelo_izquierdo']) ? $conexion->real_escape_string($_POST['modelo_izquierdo']) : '';
    $otros_izquierdo = isset($_POST['otros_izquierdo']) ? $conexion->real_escape_string($_POST['otros_izquierdo']) : '';
    $dioptrias_izquierdo = isset($_POST['dioptrias_izquierdo']) ? $conexion->real_escape_string($_POST['dioptrias_izquierdo']) : '';

    // Prepare the SQL statement
    $sql = "INSERT INTO ocular_lente_intraocular (
        id_atencion, id_usua, Id_exp,
        lente_derecho, marca_derecho, modelo_derecho, otros_derecho, dioptrias_derecho,
        lente_izquierdo, marca_izquierdo, modelo_izquierdo, otros_izquierdo, dioptrias_izquierdo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        $_SESSION['message'] = "Error preparando la consulta: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: lente_intraocular.php");
        exit();
    }
    
    // Bind parameters
    $stmt->bind_param(
        "iiiissssissss",
        $id_atencion,
        $id_usua,
        $id_exp,
        $lente_derecho,
        $marca_derecho,
        $modelo_derecho,
        $otros_derecho,
        $dioptrias_derecho,
        $lente_izquierdo,
        $marca_izquierdo,
        $modelo_izquierdo,
        $otros_izquierdo,
        $dioptrias_izquierdo
    );
    
    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Lente intraocular guardado correctamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al guardar los datos: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }
    
    // Close statement and connection
    $stmt->close();
    $conexion->close();
    
    // Redirect to lente_intraocular.php
    header("Location: lente_intraocular.php");
    exit();
}

$conexion->close();
?>