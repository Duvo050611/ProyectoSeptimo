<?php
include "../../conexionbd.php";
session_start();

if (!isset($_SESSION['pac'])) {
    header("Location: ../lista_pacientes/lista_pacientes.php");
    exit();
}

$id_atencion = $_SESSION['pac'];
$id_usua = $_SESSION['id_usua'] ?? null;
if (empty($id_usua)) {
    die("Error: id_usua está vacío o no definido.");
}

$procedimiento = $_POST['procedimiento'] ?? '';

switch ($procedimiento) {
    case 'BLEFAROPLASTIA':
        $presion_antes = $_POST['blefaro_presion_antes'] ?? '';
        $presion_durante = $_POST['blefaro_presion_durante'] ?? '';
        $presion_despues = $_POST['blefaro_presion_despues'] ?? '';
        $nota = $_POST['blefaro_nota'] ?? '';
        $enfermera = $_POST['blefaro_enfermera'] ?? '';
        

        $sql = "INSERT INTO procedimientos_quirurgicos (
            id_atencion, id_usua, procedimiento, presion_antes, presion_durante, presion_despues, nota, enfermera, fecha
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iissssss", $id_atencion, $id_usua, $procedimiento, $presion_antes, $presion_durante, $presion_despues, $nota, $enfermera);
        break;

    case 'LASIK':
        $od1 = $_POST['lasik_od1'] ?? '';
        $queratometria_od = $_POST['lasik_queratometria_od'] ?? '';
        $oi1 = $_POST['lasik_oi1'] ?? '';
        $queratometria_oi = $_POST['lasik_queratometria_oi'] ?? '';
        $microqueratomo = $_POST['lasik_microqueratomo'] ?? '';
        $anillo = $_POST['lasik_anillo'] ?? '';
        $tope = $_POST['lasik_tope'] ?? '';
        $nota = $_POST['lasik_nota'] ?? '';
        $enfermera = $_POST['lasik_enfermera'] ?? '';
        $medico = $_POST['lasik_medico'] ?? '';
        // ...otros campos...

        $sql = "INSERT INTO procedimientos_quirurgicos (
            id_atencion, id_usua, procedimiento, od1, queratometria_od, oi1, queratometria_oi, microqueratomo, anillo, tope, nota, enfermera, medico, fecha
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iisssssssssss", $id_atencion, $id_usua, $procedimiento, $od1, $queratometria_od, $oi1, $queratometria_oi, $microqueratomo, $anillo, $tope, $nota, $enfermera, $medico);
        break;

    // Agrega un case para cada procedimiento...

    default:
        $_SESSION['message'] = "Procedimiento no reconocido.";
        $_SESSION['message_type'] = "danger";
        header("Location: reg_pro.php");
        exit;
}

if ($stmt->execute()) {
    $_SESSION['message'] = "Procedimiento guardado correctamente.";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error al guardar el procedimiento: " . $stmt->error;
    $_SESSION['message_type'] = "danger";
}
$stmt->close();
header("Location: reg_pro.php");
exit;
?>