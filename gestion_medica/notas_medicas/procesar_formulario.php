<?php
session_start();
include "../../conexionbd.php";

if (!$conexion) {
    die("Error de conexión a la base de datos");
}

// Validar claves foráneas
$id_exp = isset($_POST['id_exp']) && $_POST['id_exp'] !== '' ? intval($_POST['id_exp']) : null;
$id_usua = isset($_POST['id_usua']) && $_POST['id_usua'] !== '' ? intval($_POST['id_usua']) : null;
$id_atencion = isset($_POST['id_atencion']) && $_POST['id_atencion'] !== '' ? intval($_POST['id_atencion']) : null;

if (is_null($id_exp) || is_null($id_usua) || is_null($id_atencion)) {
    die("Faltan datos obligatorios");
}

// Función para escapar texto
function escapa($conexion, $dato) {
    return $dato === null ? 'NULL' : "'" . $conexion->real_escape_string($dato) . "'";
}

// Función para valores numéricos
function valor_numero($dato) {
    return ($dato === null || $dato === '') ? 'NULL' : floatval($dato);
}

// Recibir campos
$apertura_palpebral = $_POST['apertura_palpebral'] ?? null;
$hendidura_palpebral = $_POST['hendidura_palpebral'] ?? null;
$funcion_musculo_elevador = $_POST['funcion_musculo_elevador'] ?? null;
$fenomeno_bell = $_POST['fenomeno_bell'] ?? null;
$laxitud_horizontal = $_POST['laxitud_horizontal'] ?? null;
$laxitud_vertical = $_POST['laxitud_vertical'] ?? null;
$desplazamiento_ocular = $_POST['desplazamiento_ocular'] ?? null;
$maniobra_vatsaha = $_POST['maniobra_vatsaha'] ?? null;
$distancia_margen_reflejo_1 = $_POST['distancia_margen_reflejo_1'] ?? null;
$distancia_margen_reflejo_2 = $_POST['distancia_margen_reflejo_2'] ?? null;
$exposicion_escleral_superior = $_POST['exposicion_escleral_superior'] ?? null;
$exposicion_escleral_inferior = $_POST['exposicion_escleral_inferior'] ?? null;
$altura_surco = $_POST['altura_surco'] ?? null;
$distancia_ceja_pestana = $_POST['distancia_ceja_pestana'] ?? null;
$exoftalmometria = $_POST['exoftalmometria'] ?? null;
$exoftalmometria_base = $_POST['exoftalmometria_base'] ?? null;

$apertura_palpebral_oi = $_POST['apertura_palpebral_oi'] ?? null;
$hendidura_palpebral_oi = $_POST['hendidura_palpebral_oi'] ?? null;
$funcion_musculo_elevador_oi = $_POST['funcion_musculo_elevador_oi'] ?? null;
$fenomeno_bell_oi = $_POST['fenomeno_bell_oi'] ?? null;
$laxitud_horizontal_oi = $_POST['laxitud_horizontal_oi'] ?? null;
$laxitud_vertical_oi = $_POST['laxitud_vertical_oi'] ?? null;
$desplazamiento_ocular_oi = $_POST['desplazamiento_ocular_oi'] ?? null;
$maniobra_vatsaha_oi = $_POST['maniobra_vatsaha_oi'] ?? null;

$observaciones = $_POST['observaciones'] ?? null;

// Query
$sql = "INSERT INTO exploraciones (
    id_exp, id_usua, id_atencion,

    -- Ojo derecho
    apertura_palpebral, hendidura_palpebral, funcion_musculo_elevador,
    distancia_margen_reflejo_1, distancia_margen_reflejo_2,
    exposicion_escleral_superior, exposicion_escleral_inferior,
    altura_surco, distancia_ceja_pestana,
    fenomeno_bell, laxitud_horizontal, laxitud_vertical,
    exoftalmometria, exoftalmometria_base,
    desplazamiento_ocular, maniobra_vatsaha,

    -- Ojo izquierdo
    apertura_palpebral_oi, hendidura_palpebral_oi, funcion_musculo_elevador_oi,
    fenomeno_bell_oi, laxitud_horizontal_oi, laxitud_vertical_oi,
    desplazamiento_ocular_oi, maniobra_vatsaha_oi,

    observaciones
) VALUES (
    $id_exp, $id_usua, $id_atencion,

    " . valor_numero($apertura_palpebral) . ",
    " . valor_numero($hendidura_palpebral) . ",
    " . valor_numero($funcion_musculo_elevador) . ",
    " . valor_numero($distancia_margen_reflejo_1) . ",
    " . valor_numero($distancia_margen_reflejo_2) . ",
    " . valor_numero($exposicion_escleral_superior) . ",
    " . valor_numero($exposicion_escleral_inferior) . ",
    " . valor_numero($altura_surco) . ",
    " . valor_numero($distancia_ceja_pestana) . ",
    " . escapa($conexion, $fenomeno_bell) . ",
    " . escapa($conexion, $laxitud_horizontal) . ",
    " . escapa($conexion, $laxitud_vertical) . ",
    " . valor_numero($exoftalmometria) . ",
    " . valor_numero($exoftalmometria_base) . ",
    " . escapa($conexion, $desplazamiento_ocular) . ",
    " . escapa($conexion, $maniobra_vatsaha) . ",

    " . valor_numero($apertura_palpebral_oi) . ",
    " . valor_numero($hendidura_palpebral_oi) . ",
    " . valor_numero($funcion_musculo_elevador_oi) . ",
    " . escapa($conexion, $fenomeno_bell_oi) . ",
    " . escapa($conexion, $laxitud_horizontal_oi) . ",
    " . escapa($conexion, $laxitud_vertical_oi) . ",
    " . escapa($conexion, $desplazamiento_ocular_oi) . ",
    " . escapa($conexion, $maniobra_vatsaha_oi) . ",

    " . escapa($conexion, $observaciones) . "
)";
if ($conexion->query($sql) === TRUE) {
    header("Location: formulario_exploracion.php");
} else {
    echo "Error al guardar: " . $conexion->error;
}

$conexion->close();
?>
