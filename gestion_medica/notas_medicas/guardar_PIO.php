<?php
session_start();
include "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();

if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Recibir datos POST con validación mínima para evitar errores
$id_exp = isset($_POST['id_exp']) && $_POST['id_exp'] !== '' ? intval($_POST['id_exp']) : null;
$id_usua = isset($_POST['id_usua']) && $_POST['id_usua'] !== '' ? intval($_POST['id_usua']) : null;
$id_atencion = isset($_POST['id_atencion']) && $_POST['id_atencion'] !== '' ? intval($_POST['id_atencion']) : null;

$pio_aplanacion_previa_OD = $_POST['pio_aplanacion_previa_OD'] ?? null;
$pio_tng_previa_OD = $_POST['pio_tng_previa_OD'] ?? null;
$pio_aplanacion_OD = $_POST['pio_aplanacion_OD'] ?? null;
$pio_tnc_tipo_OD = $_POST['pio_tnc_tipo_OD'] ?? null;
$pio_tnc_OD = $_POST['pio_tnc_OD'] ?? null;
$tratamiento_pio_OD = $_POST['tratamiento_pio_OD'] ?? null;
$correlacion_paquimetrica_OD = $_POST['correlacion_paquimetrica_OD'] ?? null;

$pio_aplanacion_previa_OI = $_POST['pio_aplanacion_previa_OI'] ?? null;
$pio_tng_previa_OI = $_POST['pio_tng_previa_OI'] ?? null;
$pio_aplanacion_OI = $_POST['pio_aplanacion_OI'] ?? null;
$pio_tnc_tipo_OI = $_POST['pio_tnc_tipo_OI'] ?? null;
$pio_tnc_OI = $_POST['pio_tnc_OI'] ?? null;
$tratamiento_pio_OI = $_POST['tratamiento_pio_OI'] ?? null;
$correlacion_paquimetrica_OI = $_POST['correlacion_paquimetrica_OI'] ?? null;

// Validar que IDs obligatorios no sean nulos
if (is_null($id_exp) || is_null($id_usua) || is_null($id_atencion)) {
    die("Faltan datos obligatorios para guardar el registro.");
}

// Prepara valores para la consulta, escapar con real_escape_string
function escapa($conexion, $dato) {
    return $dato === null ? 'NULL' : "'" . $conexion->real_escape_string($dato) . "'";
}

// Para los numéricos que pueden venir vacíos o con checkbox, forzar null o valor
function valor_numero($dato) {
    if ($dato === null || $dato === '' || strtolower($dato) === 'no colabora') {
        return 'NULL';
    }
    return floatval($dato);
}

// Construir la consulta sin bind_param (ojo con inyección, escapa strings)
$sql = "INSERT INTO pio_examen (
    id_exp, id_usua, id_atencion,
    pio_aplanacion_previa_OD, pio_tng_previa_OD, pio_aplanacion_OD, pio_tnc_tipo_OD, pio_tnc_OD, tratamiento_pio_OD, correlacion_paquimetrica_OD,
    pio_aplanacion_previa_OI, pio_tng_previa_OI, pio_aplanacion_OI, pio_tnc_tipo_OI, pio_tnc_OI, tratamiento_pio_OI, correlacion_paquimetrica_OI
) VALUES (
    $id_exp, $id_usua, $id_atencion,
    " . valor_numero($pio_aplanacion_previa_OD) . ",
    " . valor_numero($pio_tng_previa_OD) . ",
    " . valor_numero($pio_aplanacion_OD) . ",
    " . escapa($conexion, $pio_tnc_tipo_OD) . ",
    " . valor_numero($pio_tnc_OD) . ",
    " . escapa($conexion, $tratamiento_pio_OD) . ",
    " . escapa($conexion, $correlacion_paquimetrica_OD) . ",
    " . valor_numero($pio_aplanacion_previa_OI) . ",
    " . valor_numero($pio_tng_previa_OI) . ",
    " . valor_numero($pio_aplanacion_OI) . ",
    " . escapa($conexion, $pio_tnc_tipo_OI) . ",
    " . valor_numero($pio_tnc_OI) . ",
    " . escapa($conexion, $tratamiento_pio_OI) . ",
    " . escapa($conexion, $correlacion_paquimetrica_OI) . "
)";

if ($conexion->query($sql) === TRUE) {
        header("Location: formulario_PIO.php");
} else {
    echo "Error al guardar: " . $conexion->error;
}

$conexion->close();
?>
