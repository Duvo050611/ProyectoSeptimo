<?php
session_start();
include '../../conexionbd.php';
require 'Curp.php';
require_once "CifrasEnLetras.php";

// Check if required fields are set
if (
    isset($_POST['nom_pac']) && isset($_POST['papell']) && isset($_POST['sapell']) &&
    isset($_POST['fecnac']) && isset($_POST['sexo']) && isset($_POST['estado']) &&
    isset($_POST['dir']) && isset($_POST['tel']) && isset($_POST['religion']) &&
    isset($_POST['edociv']) && isset($_POST['resp']) && isset($_POST['paren']) &&
    isset($_POST['tel_resp']) && isset($_POST['dom_resp']) && isset($_POST['motivo_atn']) &&
    isset($_POST['alergias']) && isset($_POST['tipo_a']) && isset($_POST['aseg']) &&
    isset($_POST['banco']) && isset($_POST['deposito']) && isset($_POST['id_usua']) &&
    isset($_POST['habitacion'])
) {
    // Sanitize and assign POST variables
    $nom_pac = ucfirst(mysqli_real_escape_string($conexion, $_POST['nom_pac']));
    $papell = ucfirst(mysqli_real_escape_string($conexion, $_POST['papell']));
    $sapell = ucfirst(mysqli_real_escape_string($conexion, $_POST['sapell']));
    $fecnac = mysqli_real_escape_string($conexion, $_POST['fecnac']);
    $sexo = mysqli_real_escape_string($conexion, $_POST['sexo']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
    $id_edo = isset($_POST['id_edo']) ? (int)$_POST['id_edo'] : 0;
    $id_mun = isset($_POST['municipios']) && $_POST['estado'] !== 'OT' ? (int)$_POST['municipios'] : 0;
    $municipio_manual = isset($_POST['municipio_manual']) && $_POST['estado'] === 'OT' ? ucfirst(mysqli_real_escape_string($conexion, $_POST['municipio_manual'])) : '';
    $loc = isset($_POST['loc']) ? ucfirst(mysqli_real_escape_string($conexion, $_POST['loc'])) : '';
    $dir = ucfirst(mysqli_real_escape_string($conexion, $_POST['dir']));
    $tel = mysqli_real_escape_string($conexion, $_POST['tel']);
    $religion = mysqli_real_escape_string($conexion, $_POST['religion']);
    $l_indigena = isset($_POST['l_indigena']) ? mysqli_real_escape_string($conexion, $_POST['l_indigena']) : '';
    $edociv = mysqli_real_escape_string($conexion, $_POST['edociv']);
    $resp = ucfirst(mysqli_real_escape_string($conexion, $_POST['resp']));
    $paren = mysqli_real_escape_string($conexion, $_POST['paren']);
    $tel_resp = mysqli_real_escape_string($conexion, $_POST['tel_resp']);
    $dom_resp = ucfirst(mysqli_real_escape_string($conexion, $_POST['dom_resp']));
    $motivo_atn = mysqli_real_escape_string($conexion, $_POST['motivo_atn']);
    $alergias = ucfirst(mysqli_real_escape_string($conexion, $_POST['alergias']));
    $tipo_a = mysqli_real_escape_string($conexion, $_POST['tipo_a']);
    $aseg = (int)$_POST['aseg'];
    $aval = isset($_POST['aval']) ? ucfirst(mysqli_real_escape_string($conexion, $_POST['aval'])) : '';
    $banco = mysqli_real_escape_string($conexion, $_POST['banco']);
    $deposito = (float)$_POST['deposito'];
    $id_usua = $_POST['id_usua'] === 'OTRO' ? 0 : (int)$_POST['id_usua'];
    $id_cam = (int)$_POST['habitacion'];
    $id_usu = (int)$_GET['id_usu'];
    $fecha_actual = date("Y-m-d H:i:s");

    // Additional doctor fields
    $id_usua2 = isset($_POST['id_usua2']) ? (int)$_POST['id_usua2'] : 0;
    $id_usua3 = isset($_POST['id_usua3']) ? (int)$_POST['id_usua3'] : 0;
    $id_usua4 = isset($_POST['id_usua4']) ? (int)$_POST['id_usua4'] : 0;
    $id_usua5 = isset($_POST['id_usua5']) ? (int)$_POST['id_usua5'] : 0;

    // Generate CURP
    $diase = date("d", strtotime($fecnac));
    $mes = date("m", strtotime($fecnac));
    $annio = date("Y", strtotime($fecnac));

    if ($diase && $mes && $annio && $sexo && $estado) {
        $curp = new Curp();
        $stringCURP = $curp->generarCURP($nom_pac, $papell, $sapell, $diase, $mes, $annio, $sexo, $estado);
    } else {
        echo '<script>alert("Llenar todos los campos necesarios para generar CURP.");</script>';
        header('location: ../gestion_pacientes/paciente.php.php?error=Faltan datos para CURP');
        exit;
    }

    // Calculate age
    function bisiesto($anio_actual) {
        return checkdate(2, 29, $anio_actual);
    }

    function calculaedad($fecnac) {
        $fecha_actual = date("Y-m-d");
        $array_nacimiento = explode("-", $fecnac);
        $array_actual = explode("-", $fecha_actual);

        $anos = $array_actual[0] - $array_nacimiento[0];
        $meses = $array_actual[1] - $array_nacimiento[1];
        $dias = $array_actual[2] - $array_nacimiento[2];

        if ($dias < 0) {
            --$meses;
            switch ($array_actual[1]) {
                case 1: $dias_mes_anterior = 31; break;
                case 2: $dias_mes_anterior = 31; break;
                case 3:
                    $dias_mes_anterior = bisiesto($array_actual[0]) ? 29 : 28; break;
                case 4: $dias_mes_anterior = 31; break;
                case 5: $dias_mes_anterior = 30; break;
                case 6: $dias_mes_anterior = 31; break;
                case 7: $dias_mes_anterior = 30; break;
                case 8: $dias_mes_anterior = 31; break;
                case 9: $dias_mes_anterior = 31; break;
                case 10: $dias_mes_anterior = 30; break;
                case 11: $dias_mes_anterior = 31; break;
                case 12: $dias_mes_anterior = 30; break;
            }
            $dias += $dias_mes_anterior;
        }

        if ($meses < 0) {
            --$anos;
            $meses += 12;
        }

        if ($anos > 0) {
            return "$anos años";
        } elseif ($meses > 0) {
            return "$meses meses";
        } else {
            return "$dias días";
        }
    }

    $edad = calculaedad($fecnac);

    // Convert deposit to words
    $v = new CifrasEnLetras();
    $dep_l = $v->convertirEurosEnLetras($deposito);

    // Check if patient already exists
    $sql_check = "SELECT * FROM paciente WHERE papell = ? AND sapell = ? AND nom_pac = ? AND fecnac = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("ssss", $papell, $sapell, $nom_pac, $fecnac);
    $stmt_check->execute();
    $resultadof = $stmt_check->get_result();

    if ($resultadof->num_rows > 0) {
        $stmt_check->close();
        header("location: paciente.php?error=Este paciente ya ha sido registrado");
        exit;
    }
    $stmt_check->close();

    // Insert patient data
    $sql_paciente = "INSERT INTO paciente (curp, papell, sapell, nom_pac, fecnac, edad, id_edo_nac, sexo, nac, id_edo, id_mun, municipio_manual, loc, dir, tel, fecha, h_clinica, religion, l_indigena, edociv, resp, paren, tel_resp, dom_resp) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'MEXICANA', ?, ?, ?, ?, ?, ?, ?, 'NO', ?, ?, ?, ?, ?, ?, ?)";
    $stmt_paciente = $conexion->prepare($sql_paciente);
    $stmt_paciente->bind_param("ssssssisiiissssssssssss", $stringCURP, $papell, $sapell, $nom_pac, $fecnac, $edad, $estado, $sexo, $id_edo, $id_mun, $municipio_manual, $loc, $dir, $tel, $fecha_actual, $religion, $l_indigena, $edociv, $resp, $paren, $tel_resp, $dom_resp);

    if ($stmt_paciente->execute()) {
        $id_exp = $conexion->insert_id;

        // Update folio
        $sql_folio = "UPDATE paciente SET folio = ? WHERE id_exp = ?";
        $stmt_folio = $conexion->prepare($sql_folio);
        $stmt_folio->bind_param("ii", $id_exp, $id_exp);
        $stmt_folio->execute();
        $stmt_folio->close();

        // Update sexo
        $sexo_value = ($sexo == "M") ? 'Mujer' : (($sexo == "H") ? 'Hombre' : $sexo);
        $sql_sexo = "UPDATE paciente SET sexo = ? WHERE id_exp = ?";
        $stmt_sexo = $conexion->prepare($sql_sexo);
        $stmt_sexo->bind_param("si", $sexo_value, $id_exp);
        $stmt_sexo->execute();
        $stmt_sexo->close();

        // Handle new doctor registration if id_usua is OTRO
        if ($id_usua == "OTRO") {
            $papell_med = isset($_POST['papell_med']) ? ucfirst(mysqli_real_escape_string($conexion, $_POST['papell_med'])) : '';
            $sapell_med = isset($_POST['sapell_med']) ? ucfirst(mysqli_real_escape_string($conexion, $_POST['sapell_med'])) : '';
            $nom_med = isset($_POST['nom_med']) ? ucfirst(mysqli_real_escape_string($conexion, $_POST['nom_med'])) : '';

            $sql_medico = "INSERT INTO reg_usuarios (nombre, papell, sapell, id_rol) VALUES (?, ?, ?, 2)";
            $stmt_medico = $conexion->prepare($sql_medico);
            $stmt_medico->bind_param("sss", $nom_med, $papell_med, $sapell_med);
            $stmt_medico->execute();
            $id_usua = $conexion->insert_id;
            $stmt_medico->close();
        }

        // Get aseguradora name
        $sql_aseg = "SELECT aseg FROM cat_aseg WHERE id_aseg = ?";
        $stmt_aseg = $conexion->prepare($sql_aseg);
        $stmt_aseg->bind_param("i", $aseg);
        $stmt_aseg->execute();
        $resu_seg = $stmt_aseg->get_result();
        $asegu = $resu_seg->num_rows > 0 ? $resu_seg->fetch_assoc()['aseg'] : '';
        $stmt_aseg->close();

        // Insert admission data
        $sql_ingreso = "INSERT INTO dat_ingreso (Id_exp, fecha, alergias, tipo_a, id_usua, fecha_cama, aseg, id_usua2, id_usua3, id_usua4, id_usua5, motivo_atn) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_ingreso = $conexion->prepare($sql_ingreso);
        $stmt_ingreso->bind_param("isssisiiiiiis", $id_exp, $fecha_actual, $alergias, $tipo_a, $id_usua, $fecha_actual, $asegu, $id_usua2, $id_usua3, $id_usua4, $id_usua5, $motivo_atn);
        
        if ($stmt_ingreso->execute()) {
            $id_at = $conexion->insert_id;

            // Update bed status
            $sql_cama = "UPDATE cat_camas SET estatus = 'OCUPADA', id_atencion = ? WHERE id = ?";
            $stmt_cama = $conexion->prepare($sql_cama);
            $stmt_cama->bind_param("ii", $id_at, $id_cam);
            $stmt_cama->execute();
            $stmt_cama->close();

            // Get bed details
            $sql_cama_details = "SELECT serv_cve, tipo FROM cat_camas WHERE id = ?";
            $stmt_cama_details = $conexion->prepare($sql_cama_details);
            $stmt_cama_details->bind_param("i", $id_cam);
            $stmt_cama_details->execute();
            $resultado_cama = $stmt_cama_details->get_result();
            $cama_data = $resultado_cama->fetch_assoc();
            $cobro_cve = $cama_data['serv_cve'];
            $ubica = $cama_data['tipo'];
            $stmt_cama_details->close();

            // Insert into dat_ctapac
            $sql_ctapac = "INSERT INTO dat_ctapac (id_atencion, prod_serv, insumo, cta_fec, cta_cant, id_usua, centro_cto) 
                           VALUES (?, 'S', ?, ?, 1, ?, ?)";
            $stmt_ctapac = $conexion->prepare($sql_ctapac);
            $stmt_ctapac->bind_param("issis", $id_at, $cobro_cve, $fecha_actual, $id_usu, $ubica);
            $stmt_ctapac->execute();
            $stmt_ctapac->close();

            // Update dat_ingreso with bed and area details
            $sql_update_ingreso = "UPDATE dat_ingreso SET cama = '1', area = ?, especialidad = ? WHERE id_atencion = ?";
            $stmt_update_ingreso = $conexion->prepare($sql_update_ingreso);
            $stmt_update_ingreso->bind_param("ssi", $ubica, $ubica, $id_at);
            $stmt_update_ingreso->execute();
            $stmt_update_ingreso->close();

            // Insert financial data
            $sql_financieros = "INSERT INTO dat_financieros (id_atencion, aseg, resp, dir_resp, id_edo, id_mun, municipio_manual, tel, aval, banco, deposito, dep_l, fec_deposito, fecha, id_usua) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_financieros = $conexion->prepare($sql_financieros);
            $tel_res = isset($_POST['tel_rf']) ? mysqli_real_escape_string($conexion, $_POST['tel_rf']) : '';
            $dir_resp = isset($_POST['dir_resp']) ? ucfirst(mysqli_real_escape_string($conexion, $_POST['dir_resp'])) : '';
            $stmt_financieros->bind_param("isssiissssssssi", $id_at, $asegu, $resp, $dir_resp, $id_edo, $id_mun, $municipio_manual, $tel_res, $aval, $banco, $deposito, $dep_l, $fecha_actual, $fecha_actual, $id_usu);

            if ($stmt_financieros->execute()) {
                $stmt_financieros->close();
                $stmt_paciente->close();
                header('location: ../gestion_pacientes/paciente.php');
                exit;
            } else {
                echo '<p>Error al registrar datos financieros</p><br>' . mysqli_error($conexion);
            }
        } else {
            echo '<p>Error al registrar datos de ingreso</p><br>' . mysqli_error($conexion);
        }
        $stmt_ingreso->close();
    } else {
        echo '<p>Error al registrar paciente</p><br>' . mysqli_error($conexion);
    }
    $stmt_paciente->close();
} else {
    header('location: ../gestion_pacientes/paciente.php.php?error=Faltan datos requeridos');
}
$conexion->close();
?>