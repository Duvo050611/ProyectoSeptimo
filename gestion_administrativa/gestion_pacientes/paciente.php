<?php
session_start();
require "../../estados.php";
include "../../conexionbd.php";
include "../../gestion_administrativa/header_administrador.php";
$resultado = $conexion->query("select paciente.*, estados.nombre, estado_nac.nom_est_nac, municipios.nombre_m
from paciente inner join estados 
on paciente.id_edo=estados.id_edo
inner join estado_nac on paciente.id_edo_nac=estado_nac.id_edo_nac
inner join municipios on paciente.id_mun=municipios.id_mun") or die($conexion->error);
$usuario = $_SESSION['login'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <link rel="stylesheet" type="text/css" href="css/select2.css">
        <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
            integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
            integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="js/select2.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
        </script>
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <script src="js/select2.js"></script>
    <link rel="stylesheet" href="../global_pac/css_busc/estilos2.css">
    <script src="../global_pac/js_busc/jquery.dataTables.min.js"></script>
    <title>NUEVO PACIENTE</title>

    <style type="text/css">
        #contenido {
            display: none;
        }
        .thead {
            background-color: #2b2d7f;
            color: white;
            font-size: 22px;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="../gestion_pacientes/registro_pac.php" class="btn btn-danger btn-sm">Regresar</a>
        <hr>
        <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show col-sm-4" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php } ?>
        <center>
            <div class="thead">
                <strong>DATOS DEL PACIENTE</strong>
            </div>
        </center>
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="fecha">Fecha y hora de registro:</label>
                        <input type="datetime" name="fecha" value="<?php echo date('d-m-Y H:i:s'); ?>"
                            class="form-control" disabled>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="form-group search-bar">
                        <input type="search" id="input-search" class="form-control" placeholder="Buscar paciente">
                    </div>
                    <div class="content-search">
                        <div class="content-table">
                            <table id="table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_diag = "SELECT pa.*, da.* FROM paciente pa, dat_ingreso da WHERE da.Id_exp = pa.Id_exp ORDER BY pa.Id_exp DESC";
                                    $result_diag = $conexion->query($sql_diag);
                                    while ($row = mysqli_fetch_array($result_diag)) {
                                        $nombre_rec = $row['nom_pac'] . ' ' . $row['papell'] . ' ' . $row['sapell'];
                                    ?>
                                    <tr>
                                        <td><a href="../gestion_pacientes/vista_pacientet.php?id=<?php echo $row['Id_exp']; ?>&nombre=<?php echo urlencode($row['nom_pac']); ?>&papell=<?php echo urlencode($row['papell']); ?>&sapell=<?php echo urlencode($row['sapell']); ?>"
                                                class="btn btn-primary btn-sm"><?php echo htmlspecialchars($nombre_rec); ?></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <form action="insertar_paciente.php?id_usu=<?php echo $usuario['id_usua']; ?>" method="POST"
                onsubmit="return checkSubmit();">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="papell">Primer apellido:</label>
                            <input type="text" name="papell" id="papell" class="form-control"
                                placeholder="Apellido Paterno" onkeypress="return SoloLetras(event);"
                                style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="sapell">Segundo apellido:</label>
                            <input type="text" name="sapell" id="sapell" class="form-control"
                                placeholder="Apellido Materno" onkeypress="return SoloLetras(event);"
                                style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="50" required>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="nom_pac">Nombre(s):</label>
                            <input type="text" name="nom_pac" id="nom_pac" class="form-control"
                                placeholder="Nombre del Paciente" onkeypress="return SoloLetras(event);"
                                style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="50" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="fecnac">Fecha de nacimiento:</label>
                            <input type="date" name="fecnac" id="fecnac" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="estado">Estado de nacimiento:</label>
                            <select id="estado" name="estado" class="form-control select2" required>
                                <option value="" disabled selected>Selecciona el estado</option>
                                <?php
                                $resultadoEstados = $conexion->query("SELECT id_edo, nombre FROM estados WHERE activo=1 ORDER BY nombre ASC") or die($conexion->error);
                                while ($row = mysqli_fetch_assoc($resultadoEstados)) {
                                    echo "<option value='{$row['id_edo']}'>{$row['nombre']}</option>";
                                }
                                ?>
                                <option value="OT">Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="municipios">Municipio:</label>
                            <select id="municipios" name="municipios" class="form-control" required>
                                <option value="" disabled selected>Seleccionar municipio</option>
                            </select>
                            <input type="text" id="municipio_manual" name="municipio_manual"
                                class="form-control" placeholder="Escriba el municipio"
                                style="display: none;" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="sexo">Género:</label>
                            <select name="sexo" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="H">Hombre</option>
                                <option value="M">Mujer</option>
                                <option value="Se desconoce">Se desconoce</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="dir">Dirección:</label>
                            <input type="text" name="dir" id="dir" class="form-control"
                                placeholder="Domicilio del Paciente" style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tel">Teléfono del paciente:</label>
                            <input type="text" name="tel" id="tel" placeholder="Teléfono a 10 dígitos"
                                class="form-control" onkeypress="return SoloNumeros(event);" maxlength="10"
                                required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="religion">Religión:</label>
                            <select name="religion" class="form-control">
                                <option value="">Seleccionar</option>
                                <option value="Católica">Católica</option>
                                <option value="Cristiana">Cristiana</option>
                                <option value="Protestante">Protestante</option>
                                <option value="Testigo de Jehová">Testigo de Jehová</option>
                                <option value="Otra">Otra</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="edociv">Estado civil:</label>
                            <select name="edociv" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="Soltero">Soltero</option>
                                <option value="Casado">Casado</option>
                                <option value="Viudo">Viudo</option>
                                <option value="Divorciado">Divorciado</option>
                                <option value="Unión libre">Unión libre</option>
                            </select>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="thead">
                        <strong>DATOS DEL RESPONSABLE</strong>
                    </div>
                </center>
                <br>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="resp">Nombre completo:</label>
                            <input type="text" name="resp" id="resp" class="form-control"
                                placeholder="Nombre completo del responsable" onkeypress="return SoloLetras(event);"
                                style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="40" required>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="paren">Parentesco:</label>
                            <select name="paren" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="Abuelo">Abuelo</option>
                                <option value="Padre">Padre</option>
                                <option value="Madre">Madre</option>
                                <option value="Tío">Tío</option>
                                <option value="Esposo">Esposo</option>
                                <option value="Esposa">Esposa</option>
                                <option value="Hijo">Hijo</option>
                                <option value="Hermano">Hermano</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="tel_resp">Teléfono:</label>
                            <input type="text" name="tel_resp" id="tel_resp"
                                placeholder="Teléfono del responsable a 10 dígitos" class="form-control"
                                onkeypress="return SoloNumeros(event);" maxlength="10" required>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="dom_resp">Dirección del responsable:</label>
                            <input type="text" name="dom_resp" id="dom_resp" placeholder="Domicilio del responsable"
                                class="form-control" style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                required>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="thead">
                        <strong>HOJA FRONTAL</strong>
                    </div>
                </center>
                <br>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="id_usua">Médico tratante:</label>
                            <select name="id_usua" class="form-control select2" onchange="mostrar(this.value);">
                                <option value="" disabled selected>Seleccionar</option>
                                <?php
                                $resultado1 = $conexion->query("SELECT * FROM reg_usuarios WHERE u_activo='SI' /* AND (id_rol=2 OR id_rol=12 OR id_rol=0) */ ORDER BY nombre ASC") or die($conexion->error);
                                while ($opciones = mysqli_fetch_assoc($resultado1)) {
                                    echo "<option value='{$opciones['id_usua']}'>{$opciones['nombre']} {$opciones['papell']} {$opciones['sapell']}</option>";
                                }
                                ?>
                                <option value="OTRO">Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="habitacion">Seleccionar habitación:</label>
                            <select id="cama" name="habitacion" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <?php
                                $resultado1 = $conexion->query("SELECT * FROM cat_camas WHERE estatus='LIBRE' ORDER BY num_cama ASC") or die($conexion->error);
                                while ($opciones = mysqli_fetch_assoc($resultado1)) {
                                    echo "<option value='{$opciones['id']}'>{$opciones['num_cama']} {$opciones['tipo']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="container" id="contenido">
                    <h5>Registro de nuevo médico</h5>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nombre completo:</label>
                                <input type="text" name="papell_med" class="form-control"
                                    placeholder="Nombre completo">
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="motivo_atn">Motivo de atención:</label>
                            <select name="motivo_atn" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="Hospitalización">Requiere Hospitalización</option>
                                <option value="Cirugía programada">Cirugía programada</option>
                                <option value="Cirugía de urgencia">Cirugía de urgencia</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="alergias">Alergias:</label>
                            <input type="text" name="alergias" class="form-control"
                                placeholder="Alergias del paciente" onkeypress="return SoloLetras(event);"
                                style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="tipo_a">Especialidad:</label>
                            <select name="tipo_a" class="form-control select2" required>
                                <option value="">Seleccionar</option>
                                <?php
                                $resultadoaseg = $conexion->query("SELECT * FROM cat_espec WHERE espec_activo='SI' ORDER BY espec ASC") or die($conexion->error);
                                while ($opcionesaseg = mysqli_fetch_assoc($resultadoaseg)) {
                                    echo "<option value='{$opcionesaseg['espec']}'>{$opcionesaseg['espec']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <center>
                    <div class="thead">
                        <strong>DATOS FINANCIEROS</strong>
                    </div>
                </center>
                <br>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="aseg">Aseguradora:</label>
                            <select name="aseg" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <?php
                                $resultadoaseg = $conexion->query("SELECT * FROM cat_aseg WHERE aseg_activo='SI' ORDER BY aseg ASC") or die($conexion->error);
                                while ($opcionesaseg = mysqli_fetch_assoc($resultadoaseg)) {
                                    echo "<option value='{$opcionesaseg['id_aseg']}'>{$opcionesaseg['aseg']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="banco">Forma de pago:</label>
                            <select name="banco" class="form-control" required>
                                <option value="">Seleccionar</option>
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                <option value="DEPOSITO">DEPOSITO</option>
                                <option value="TARJETA">TARJETA</option>
                                <option value="ASEGURADORA">ASEGURADORA</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="aval">Detalle:</label>
                            <input type="text" name="aval" id="aval" placeholder="Banco, No. de tarjeta, etc."
                                class="form-control" style="text-transform:capitalize;"
                                onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                                maxlength="60">
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="fec_deposito">Fecha:</label>
                            <input type="text" name="fec_deposito" id="fec_deposito" class="form-control"
                                value="<?php echo date('d-m-Y'); ?>" disabled>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="deposito">Cantidad $ (Número):</label>
                            <input type="text" name="deposito" id="deposito" class="form-control number"
                                onkeypress="return SoloNumeros(event);" maxlength="13" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-custom"><i class="fas fa-save"></i>
                        Guardar</button>
                    <a href="../gestion_pacientes/registro_pac.php" class="btn btn-danger btn-custom"><i
                            class="fas fa-times"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    </div>
    <footer class="main-footer">
        <?php include "../../template/footer.php"; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
    <script src="../global_pac/js_busc/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.select2').select2();
        $('#table').DataTable();
        $("#input-search").keyup(function() {
            let searchText = $(this).val().toLowerCase();
            $("#table tbody tr").each(function() {
                let rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.includes(searchText));
            });
        });

        const $municipios = $('#municipios');
        const $municipioManual = $('#municipio_manual');

        // Handle estado selection change
        $('#estado').on('change', function() {
            const idEstado = $(this).val();
            console.log('Selected estado:', idEstado);

            $municipios.prop('disabled', true).html(
                '<option value="" disabled selected>Cargando municipios...</option>');
            $municipioManual.hide().prop('required', false);

            if (idEstado === 'OT') {
                $municipios.hide();
                $municipioManual.show().prop('required', true);
                $municipios.html('<option value="" disabled selected>Seleccionar municipio</option>').select2();
                $municipios.prop('disabled', true);
            } else if (idEstado) {
                $municipios.show();
                $.ajax({
                    url: 'municipios.php',
                    type: 'GET',
                    data: { estado_id: idEstado },
                    dataType: 'json',
                    success: function(datos) {
                        console.log('Response data:', datos);
                        let html = '<option value="" disabled selected>Seleccionar municipio</option>';
                        if (datos && datos.length > 0) {
                            datos.forEach(mun => {
                                html += `<option value="${mun.id_mun}">${mun.nombre_m}</option>`;
                            });
                            $municipios.prop('disabled', false);
                        } else {
                            html = '<option value="">No hay municipios disponibles</option>';
                        }
                        $municipios.html(html).select2();
                    },
                    error: function(xhr, status, error) {
                        console.error('Fetch error:', error, xhr.status, xhr.statusText);
                        $municipios.html(
                            '<option value="">Error al cargar municipios</option>').select2();
                        $municipios.prop('disabled', true);
                    }
                });
            } else {
                $municipios.html(
                    '<option value="" disabled selected>Seleccionar municipio</option>').select2();
                $municipios.prop('disabled', true);
            }
        });

        function mostrar(value) {
            document.getElementById('contenido').style.display = (value === "OTRO") ? 'block' : 'none';
        }

        let enviando = false;

        function checkSubmit() {
            if (!enviando) {
                enviando = true;
                return true;
            } else {
                alert("Guardando Paciente... Por favor espere...");
                return false;
            }
        }

        function SoloLetras(e) {
            const key = e.keyCode || e.which;
            const tecla = String.fromCharCode(key).toLowerCase();
            const letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
            return letras.indexOf(tecla) !== -1;
        }

        function SoloNumeros(e) {
            const key = e.keyCode || e.which;
            const tecla = String.fromCharCode(key);
            const numeros = "0123456789";
            return numeros.indexOf(tecla) !== -1;
        }
    });
    </script>
</body>
</html>