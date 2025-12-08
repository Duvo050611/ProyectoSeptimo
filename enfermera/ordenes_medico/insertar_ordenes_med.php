<?php
session_start();
include '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();
$usuario = $_SESSION['login'];
$rol = $usuario['id_rol'];
$id_usua = $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];

$fecha_actual = date("Y-m-d");
$fecha_actual2 = date("Y-m-d H:i");
$hora_actual = date("H:i:s");
$hora_ord = date("H:i:s");

$dieta = $_POST['dieta'];
$det_dieta = $_POST['det_dieta'];
$cuid_gen = $_POST['cuid_gen'];
$detalle_lab = $_POST['detalle_lab'];
$det_sang = $_POST['det_sang'];
$det_pato = $_POST['det_pato'];
$det_imagen = $_POST['det_imagen'];

$dialisis = $_POST['dialisis'];
$fisio = $_POST['fisio'];
$reha = $_POST['reha'];

$med_med = $_POST['med_med'];
$soluciones = $_POST['soluciones'];
$med = $_POST['med'];
$enf = $_POST['enf'];
$observ_be = $_POST['observ_be'];

// Procesamiento de imagenología
$v1 = $_POST['a1'];
$sep = implode(",", $v1);
$servicio = json_encode($sep);
if ($servicio === 'NINGUNO') {
    $servicio = '"NINGUNO"';
}

// Procesamiento de sangre
$s1 = $_POST['s1'];
$sang = implode(",", $s1);
$serv_sang = json_encode($sang);
if ($serv_sang === 'NINGUNO') {
    $serv_sang = '"NINGUNO"';
}

// Procesamiento de patología
$p1 = $_POST['p1'];
$sol_pato = implode(",", $p1);
$serv_pato = json_encode($sol_pato);
if ($serv_pato === 'NINGUNO') {
    $serv_pato = '"NINGUNO"';
}

// Procesamiento de laboratorio
$l1 = $_POST['l1'];
$id_laborato = $_POST['l1'];
$perfillab = implode(",", $l1);
$serv_perfillab = json_encode($perfillab);
if ($serv_perfillab === 'NINGUNO') {
    $serv_perfillab = '"NINGUNO"';
}

// Obtener habitación
$sql_hab = "SELECT * FROM cat_camas WHERE id_atencion = $id_atencion";
$result_hab = $conexion->query($sql_hab);
$hab = 0;
$tipo = '';
while ($row_hab = $result_hab->fetch_assoc()) {
    $hab = $row_hab['num_cama'];
    $tipo = $row_hab['tipo'];
}

$id_imagenologia = $_POST["a1"];
$solicitud_sang = $_POST["s1"];
$sol_pato = $_POST["p1"];
$id_patolo = $_POST["p1"];
$perfillab = $_POST["l1"];

// Insertar notificaciones de imagenología
for ($i = 0; $i < count($id_imagenologia); $i++) {
    $id_imagenologia1 = $id_imagenologia[$i];
    $cta_cant = 1;
    $precio = 0;
    $activo = "SI";
    $prodserv = "S";

    if ($id_imagenologia1 === 'NINGUNO') {
        continue;
    }

    // CORRECCIÓN: Agregar campo 'link' con valor vacío por defecto
    $insertarnot = mysqli_query($conexion, 'INSERT INTO notificaciones_imagen
        (id_atencion, habitacion, fecha_ord, id_usua, sol_estudios, realizado, resultado, det_imagen, interpretado, link) 
        VALUES 
        (' . $id_atencion . ', ' . $hab . ', "' . $fecha_actual2 . '", ' . $id_usua . ', "' . $id_imagenologia1 . '", "NO", "", "' . $det_imagen . '", "No", "")')
    or die('<p>Error al registrar imagen</p><br>' . mysqli_error($conexion));

    $sql_dat_ingi = "SELECT * FROM cat_servicios WHERE serv_desc='$id_imagenologia1'";
    $result_dat_inga = $conexion->query($sql_dat_ingi);
    while ($row_dat_ingu = $result_dat_inga->fetch_assoc()) {
        $id_cta = $row_dat_ingu['id_serv'];
    }
}

// Insertar notificaciones de sangre
$solicitud_sang1 = $serv_sang;
if ($solicitud_sang1 !== '"NINGUNO"') {
    $insertarnot = mysqli_query($conexion, 'INSERT INTO notificaciones_sangre
        (id_atencion, habitacion, fecha_ord, id_usua, sol_sangre, realizado, resultado, det_sang) 
        VALUES 
        (' . $id_atencion . ', ' . $hab . ', "' . $fecha_actual2 . '", ' . $id_usua . ', ' . $serv_sang . ', "NO", "", "' . $det_sang . '")')
    or die('<p>Error al registrar banco de sangre</p><br>' . mysqli_error($conexion));
}

//// Insertar notificaciones de patología
//$sol_pato1 = $serv_pato;
//if ($sol_pato1 !== '"NINGUNO"') {
//    $insertarpato = mysqli_query($conexion, 'INSERT INTO notificaciones_pato
//        (id_atencion, fecha, hora, id_usua, dispo_p, realizado, resultado, estudios_obser)
//        VALUES
//        (' . $id_atencion . ', "' . $fecha_actual2 . '", "' . $hora_ord . '", ' . $id_usua . ', ' . $serv_pato . ', "NO", "", "' . $det_pato . '")')
//    or die('<p>Error al registrar pato</p><br>' . mysqli_error($conexion));
//}

// Insertar cuenta de patología
for ($iii = 0; $iii < count($id_patolo); $iii++) {
    $perfilpato = $id_patolo[$iii];
    $cta_cant = 1;
    $precio = 0;
    $activo = "SI";
    $prodserv = "S";

    if ($perfilpato === 'NINGUNO') {
        continue;
    }

    $sql_aseg = "SELECT aseg FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_aseg = $conexion->query($sql_aseg);
    $at = '';
    while ($row_aseg = $result_aseg->fetch_assoc()) {
        $at = $row_aseg['aseg'];
    }

    $resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$at'") or die($conexion->error);
    $tr = 0;
    while ($filat = mysqli_fetch_array($resultadot)) {
        $tr = $filat["tip_precio"];
    }

    $sql_dat_ingp = "SELECT * FROM cat_servicios WHERE serv_desc='$perfilpato'";
    $result_dat_ingp = $conexion->query($sql_dat_ingp);
    while ($row_dat_ingp = $result_dat_ingp->fetch_assoc()) {
        $id_cta = $row_dat_ingp['id_serv'];
        if ($tr == 1) $precio = $row_dat_ingp['serv_costo'];
        if ($tr == 2) $precio = $row_dat_ingp['serv_costo2'];
        if ($tr == 3) $precio = $row_dat_ingp['serv_costo3'];
        if ($tr == 4) $precio = $row_dat_ingp['serv_costo4'];
    }

    $registractaa = mysqli_query($conexion, 'INSERT INTO dat_ctapac
        (id_atencion, prod_serv, insumo, cta_fec, cta_cant, cta_tot, id_usua, cta_activo, centro_cto) 
        VALUES 
        (' . $id_atencion . ', "' . $prodserv . '", ' . $id_cta . ', "' . $fecha_actual . '", ' . $cta_cant . ', ' . $precio . ', ' . $id_usua . ', "' . $activo . '", "' . $tipo . '")')
    or die('<p>Error al registrar cuenta patologia</p><br>' . mysqli_error($conexion));
}

// LABORATORIO - Preparar estudios inmediatos
$lab_inmed = "";
$lab_ninmed = "";

for ($ii = 0; $ii < count($id_laborato); $ii++) {
    $perfillab = $id_laborato[$ii];

    if ($perfillab === 'NINGUNO') {
        continue;
    }

    $sql_dat_ingl = "SELECT * FROM cat_servicios WHERE serv_desc='$perfillab'";
    $result_dat_ingl = $conexion->query($sql_dat_ingl);
    while ($row_dat_ingl = $result_dat_ingl->fetch_assoc()) {
        $id_cta = $row_dat_ingl['id_serv'];
        $descrip = $row_dat_ingl['serv_desc'];
        $lab_inmed = $lab_inmed . '/' . $descrip;
        $lab_ninmed = $lab_ninmed . '/' . $descrip;
    }
}

// Insertar notificación de laboratorio
if ($perfillab !== 'NINGUNO') {
    $insertarlabo = mysqli_query($conexion, 'INSERT INTO notificaciones_labo
        (id_atencion, habitacion, fecha_ord, id_usua, sol_estudios, realizado, det_labo) 
        VALUES 
        (' . $id_atencion . ', ' . $hab . ', "' . $fecha_actual2 . '", ' . $id_usua . ', "' . $lab_inmed . '", "NO", "' . $detalle_lab . '")')
    or die('<p>Error al registrar labo inmediatos</p><br>' . mysqli_error($conexion));
}

// Insertar cuenta de laboratorio
for ($ii = 0; $ii < count($id_laborato); $ii++) {
    $perfillab = $id_laborato[$ii];
    $cta_cant = 1;
    $precio = 0;
    $activo = "SI";
    $prodserv = "S";

    if ($perfillab === 'NINGUNO') {
        continue;
    }

    $sql_aseg = "SELECT aseg FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_aseg = $conexion->query($sql_aseg);
    $at = '';
    while ($row_aseg = $result_aseg->fetch_assoc()) {
        $at = $row_aseg['aseg'];
    }

    $resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$at'") or die($conexion->error);
    $tr = 0;
    while ($filat = mysqli_fetch_array($resultadot)) {
        $tr = $filat["tip_precio"];
    }

    $sql_dat_ingl = "SELECT * FROM cat_servicios WHERE serv_desc='$perfillab'";
    $result_dat_ingl = $conexion->query($sql_dat_ingl);
    while ($row_dat_ingl = $result_dat_ingl->fetch_assoc()) {
        $id_cta = $row_dat_ingl['id_serv'];
        if ($tr == 1) $precio = $row_dat_ingl['serv_costo'];
        if ($tr == 2) $precio = $row_dat_ingl['serv_costo2'];
        if ($tr == 3) $precio = $row_dat_ingl['serv_costo3'];
        if ($tr == 4) $precio = $row_dat_ingl['serv_costo4'];
    }

    $registracta = mysqli_query($conexion, 'INSERT INTO dat_ctapac
        (id_atencion, prod_serv, insumo, cta_fec, cta_cant, cta_tot, id_usua, cta_activo, centro_cto) 
        VALUES 
        (' . $id_atencion . ', "' . $prodserv . '", ' . $id_cta . ', "' . $fecha_actual . '", ' . $cta_cant . ', ' . $precio . ', ' . $id_usua . ', "' . $activo . '", "' . $tipo . '")')
    or die('<p>Error al registrar cuenta laboratorios</p><br>' . mysqli_error($conexion));
}

$insertar = mysqli_query($conexion, 'INSERT INTO dat_ordenes_med
    (id_atencion, id_usua, fecha_ord, hora_ord, dieta, det_dieta, cuid_gen, med_med, soluciones, perfillab, sol_estudios, solicitud_sang, det_sang, observ_be, medico, enfermera_tes, tipo, detalle_lab, sol_pato, det_pato, det_imagen, dialisis, fisio, reha)
    VALUES
    (' . $id_atencion . ', ' . $id_usua . ', "' . $fecha_actual . '", "' . $hora_ord . '", "' . $dieta . '", "' . $det_dieta . '", "' . $cuid_gen . '", "' . $med_med . '", "' . $soluciones . '", ' . $serv_perfillab . ', ' . $servicio . ', ' . $serv_sang . ', "' . $det_sang . '", "' . $observ_be . '", "' . $med . '", "' . $enf . '", "VERB", "' . $detalle_lab . '", ' . $serv_pato . ', "' . $det_pato . '", "' . $det_imagen . '", "' . $dialisis . '", "' . $fisio . '", "' . $reha . '")')
or die('<p>Error al registrar orden médica</p><br>' . mysqli_error($conexion));

// Redireccionar según rol
if ($rol == 1) {
    header('location: ../../gestion_administrativa/gestion_pacientes/registro_pac.php');
} else {
    header('location: ../lista_pacientes/vista_pac_enf.php');
}
?>