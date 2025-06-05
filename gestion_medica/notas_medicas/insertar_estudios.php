<?php
session_start();
include "../../conexionbd.php";

// Check if the user is logged in and id_atencion is set
if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: /gestion_medica/notas_medicas/estudios.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_atencion = $_SESSION['hospital'];
    $id_usua = isset($_SESSION['login']['id_usua']) ? (int)$_SESSION['login']['id_usua'] : 0; // Adjust based on your session structure
    
    // Validate id_usua
    if ($id_usua === 0) {
        $_SESSION['message'] = "Error: No se encontró el ID del usuario en la sesión.";
        $_SESSION['message_type'] = "danger";
        header("Location: /gestion_medica/notas_medicas/estudios.php");
        exit();
    }
    
    // Retrieve Id_exp from dat_ingreso
    $sql = "SELECT Id_exp FROM dat_ingreso WHERE id_atencion = ?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        $_SESSION['message'] = "Error preparando la consulta: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: /gestion_medica/notas_medicas/estudios.php");
        exit();
    }
    $stmt->bind_param("i", $id_atencion);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $Id_exp = $row['Id_exp'];
    } else {
        $_SESSION['message'] = "Error: No se encontró Id_exp para id_atencion = $id_atencion";
        $_SESSION['message_type'] = "danger";
        header("Location: /gestion_medica/notas_medicas/estudios.php");
        exit();
    }
    $stmt->close();
    
    // Sanitize and retrieve form data
    $riesgo_quirurgico = isset($_POST['riesgo_quirurgico']) ? mysqli_real_escape_string($conexion, $_POST['riesgo_quirurgico']) : '';
    $info_riesgo = isset($_POST['info_riesgo']) ? mysqli_real_escape_string($conexion, $_POST['info_riesgo']) : '';
    $analisis_sangre = isset($_POST['analisis_sangre']) ? mysqli_real_escape_string($conexion, $_POST['analisis_sangre']) : '';
    $cv_od = isset($_POST['cv_od']) ? mysqli_real_escape_string($conexion, $_POST['cv_od']) : '';
    $cv_oi = isset($_POST['cv_oi']) ? mysqli_real_escape_string($conexion, $_POST['cv_oi']) : '';
    $cv_general = isset($_POST['cv_general']) ? mysqli_real_escape_string($conexion, $_POST['cv_general']) : '';
    $ecografia_od = isset($_POST['ecografia_od']) ? mysqli_real_escape_string($conexion, $_POST['ecografia_od']) : '';
    $ecografia_oi = isset($_POST['ecografia_oi']) ? mysqli_real_escape_string($conexion, $_POST['ecografia_oi']) : '';
    $ecografia_general = isset($_POST['ecografia_general']) ? mysqli_real_escape_string($conexion, $_POST['ecografia_general']) : '';
    $oct_hrt_od = isset($_POST['oct_hrt_od']) ? mysqli_real_escape_string($conexion, $_POST['oct_hrt_od']) : '';
    $oct_hrt_oi = isset($_POST['oct_hrt_oi']) ? mysqli_real_escape_string($conexion, $_POST['oct_hrt_oi']) : '';
    $oct_hrt_general = isset($_POST['oct_hrt_general']) ? mysqli_real_escape_string($conexion, $_POST['oct_hrt_general']) : ''; // Fixed typo
    $fag_od = isset($_POST['fag_od']) ? mysqli_real_escape_string($conexion, $_POST['fag_od']) : '';
    $fag_oi = isset($_POST['fag_oi']) ? mysqli_real_escape_string($conexion, $_POST['fag_oi']) : '';
    $fag_general = isset($_POST['fag_general']) ? mysqli_real_escape_string($conexion, $_POST['fag_general']) : '';
    $ubm_od = isset($_POST['ubm_od']) ? mysqli_real_escape_string($conexion, $_POST['ubm_od']) : '';
    $ubm_oi = isset($_POST['ubm_oi']) ? mysqli_real_escape_string($conexion, $_POST['ubm_oi']) : '';
    $constante_derecho = isset($_POST['constante_derecho']) ? mysqli_real_escape_string($conexion, $_POST['constante_derecho']) : '';
    $constante_izquierdo = isset($_POST['constante_izquierdo']) ? mysqli_real_escape_string($conexion, $_POST['constante_izquierdo']) : '';

    // Prepare the SQL statement
    $sql = "INSERT INTO ocular_estudios (
        id_atencion, id_usua, Id_exp, riesgo_quirurgico, info_riesgo, analisis_sangre, 
        cv_od, cv_oi, cv_general, 
        ecografia_od, ecografia_oi, ecografia_general, 
        oct_hrt_od, oct_hrt_oi, oct_hrt_general, 
        fag_od, fag_oi, fag_general, 
        ubm_od, ubm_oi, 
        constante_derecho, constante_izquierdo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        $_SESSION['message'] = "Error preparando la consulta: " . $conexion->error;
        $_SESSION['message_type'] = "danger";
        header("Location: /gestion_medica/notas_medicas/estudios.php");
        exit();
    }
    
    // Bind parameters
    $stmt->bind_param(
        "iiisssssssssssssssssss",
        $id_atencion,
        $id_usua,
        $Id_exp,
        $riesgo_quirurgico,
        $info_riesgo,
        $analisis_sangre,
        $cv_od,
        $cv_oi,
        $cv_general,
        $ecografia_od,
        $ecografia_oi,
        $ecografia_general,
        $oct_hrt_od,
        $oct_hrt_oi,
        $oct_hrt_general,
        $fag_od,
        $fag_oi,
        $fag_general,
        $ubm_od,
        $ubm_oi,
        $constante_derecho,
        $constante_izquierdo
    );
    
    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Estudios guardados correctamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al guardar los datos: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }
    
    // Close statement and connection
    $stmt->close();
    $conexion->close();
    
    // Redirect to estudios.php
    header("Location: /gestion_medica/notas_medicas/estudios.php");
    exit();
} else {
    // Redirect if not a POST request
    header("Location: /gestion_medica/notas_medicas/estudios.php");
    exit();
}
?>