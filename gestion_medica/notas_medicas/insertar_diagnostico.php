<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['hospital']) || !isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare data
    $id_atencion = $_SESSION['hospital'];
    $id_usua = isset($_POST['id_usua']) ? (int)$_POST['id_usua'] : 0;
    
    // Validate id_usua
    if ($id_usua === 0) {
        $_SESSION['message'] = "Error: ID de usuario no válido.";
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }
    
    // Fetch Id_exp
    $stmt = $conexion->prepare("SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?");
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id_exp = $row['Id_exp'] ?? null;
    $stmt->close();

    if (!$id_exp) {
        $_SESSION['message'] = "Error: No se encontró el expediente para esta atención.";
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }

    // Sanitize and prepare form data
    $oftalmologicamente_sano = isset($_POST['oftalmologicamente_sano']) ? 1 : 0;
    $sin_diagnostico_cie10 = isset($_POST['sin_diagnostico_cie10']) ? 1 : 0;
    $diagnostico_previo = $conexion->real_escape_string($_POST['diagnostico_previo'] ?? '');

    // Right Eye
    $diagnostico_principal_derecho = $conexion->real_escape_string($_POST['diagnostico_principal_derecho'] ?? '');
    $codigo_cie_derecho = $conexion->real_escape_string($_POST['codigo_cie_derecho'] ?? '');
    $desc_cie_derecho = $conexion->real_escape_string($_POST['desc_cie_derecho'] ?? '');
    $tipo_diagnostico_derecho = $conexion->real_escape_string($_POST['tipo_diagnostico_derecho'] ?? '');
    $otros_diagnosticos_derecho = $conexion->real_escape_string($_POST['otros_diagnosticos_derecho'] ?? '');

    // Left Eye
    $diagnostico_principal_izquierdo = $conexion->real_escape_string($_POST['diagnostico_principal_izquierdo'] ?? '');
    $codigo_cie_izquierdo = $conexion->real_escape_string($_POST['codigo_cie_izquierdo'] ?? '');
    $desc_cie_izquierdo = $conexion->real_escape_string($_POST['desc_cie_izquierdo'] ?? '');
    $tipo_diagnostico_izquierdo = $conexion->real_escape_string($_POST['tipo_diagnostico_izquierdo'] ?? '');
    $otros_diagnosticos_izquierdo = $conexion->real_escape_string($_POST['otros_diagnosticos_izquierdo'] ?? '');

    // Prepare and execute SQL query
    $sql = "INSERT INTO ocular_diagnostico (
        id_atencion, id_usua, Id_exp, oftalmologicamente_sano, sin_diagnostico_cie10, diagnostico_previo,
        diagnostico_principal_derecho, codigo_cie_derecho, desc_cie_derecho, tipo_diagnostico_derecho, otros_diagnosticos_derecho,
        diagnostico_principal_izquierdo, codigo_cie_izquierdo, desc_cie_izquierdo, tipo_diagnostico_izquierdo, otros_diagnosticos_izquierdo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        $_SESSION['message'] = "Error preparando la consulta: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: diagnostico.php");
        exit();
    }
    
    $stmt->bind_param(
        "iiiiisssssssssss",
        $id_atencion,
        $id_usua,
        $id_exp,
        $oftalmologicamente_sano,
        $sin_diagnostico_cie10,
        $diagnostico_previo,
        $diagnostico_principal_derecho,
        $codigo_cie_derecho,
        $desc_cie_derecho,
        $tipo_diagnostico_derecho,
        $otros_diagnosticos_derecho,
        $diagnostico_principal_izquierdo,
        $codigo_cie_izquierdo,
        $desc_cie_izquierdo,
        $tipo_diagnostico_izquierdo,
        $otros_diagnosticos_izquierdo
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Diagnóstico guardado exitosamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al guardar: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    $conexion->close();
    header("Location: diagnostico.php");
    exit();
} else {
    $_SESSION['mensaje'] = "Método no permitido.";
    $_SESSION['message_type'] = "danger";
    header("Location: diagnostico.php");
    exit();
}
?>