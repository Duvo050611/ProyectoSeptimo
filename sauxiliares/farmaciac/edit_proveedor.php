<?php
session_start();
include "../../conexionbd.php";

if (!isset($_GET['id'])) {
    echo "<script>alert('ID del proveedor no especificado.'); window.location='index.php';</script>";
    exit();
}

$id = $_GET['id'];


$resultado = $conexion->query("SELECT * FROM proveedores WHERE id_prov = $id") or die($conexion->error);
$row = $resultado->fetch_assoc();

if (!$row) {
    echo "<script>alert('Proveedor no encontrado.'); window.location='index.php';</script>";
    exit();
}


$usuario = $_SESSION['login'];
if (!in_array($usuario['id_rol'], [4, 5, 11])) {
    echo "<script>window.location='../../index.php';</script>";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $lic_prov = $_POST['lic_prov'];
    $cont_prov = $_POST['cont_prov'];
    $activo = $_POST['activo'];

    // Actualiza el proveedor en la base de datos
    $conexion->query("UPDATE proveedores SET 
        nom_prov='$nombre_proveedor', 
        dir_prov='$direccion', 
        tel_prov='$telefono', 
        email_prov='$email', 
        lic_prov='$lic_prov', 
        cont_prov='$cont_prov', 
        activo='$activo' 
        WHERE id_prov=$id") or die("Error: " . $conexion->error);
    
    // Almacena el mensaje en la sesión
    $_SESSION['mensaje'] = 'Proveedor actualizado correctamente.';
    echo "<script>window.location='proveedores.php';</script>";
}

include "../header_farmaciac.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            background-color: white;
            padding: 20px;
            margin-top: 50px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary, .btn-secondary {
            padding: 10px 20px;
        }
        .btn-primary {
            background-color: #2b2d7f;
            border-color: #2b2d7f;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn:hover {
            opacity: 0.9;
        }
        /* Estilos para el select */
        #activo {
            transition: background-color 0.3s;
        }
        .activo-s {
            background-color: #28a745; /* Verde para "Sí" */
            color: white;
        }
        .activo-n {
            background-color: #dc3545; /* Rojo para "No" */
            color: white;
        }
    </style>
    <script>
        function cambiarColorSelect(select) {
            if (select.value === 'SI') {
                select.classList.add('activo-s');
                select.classList.remove('activo-n');
            } else if (select.value === 'NO') {
                select.classList.add('activo-n');
                select.classList.remove('activo-s');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var select = document.getElementById('activo');
            cambiarColorSelect(select); // Cambia el color inicial
            select.addEventListener('change', function() {
                cambiarColorSelect(select);
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Editar Proveedor</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="nombre-proveedor">Nombre:</label>
                <input type="text" name="nombre_proveedor" class="form-control" id="nombre-proveedor" value="<?= $row['nom_prov'] ?>" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" class="form-control" id="direccion" value="<?= $row['dir_prov'] ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" class="form-control" id="telefono" value="<?= $row['tel_prov'] ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" name="email" class="form-control" id="email" value="<?= $row['email_prov'] ?>" required>
            </div>
            <div class="form-group">
                <label for="lic_prov">Licencia:</label>
                <input type="text" name="lic_prov" class="form-control" id="lic_prov" value="<?= $row['lic_prov'] ?>" required>
            </div>
            <div class="form-group">
                <label for="cont_prov">Contacto:</label>
                <input type="text" name="cont_prov" class="form-control" id="cont_prov" value="<?= $row['cont_prov'] ?>" required>
            </div>
            <div class="form-group">
                <label for="activo">Activo:</label>
                <select name="activo" class="form-control" id="activo" required>
                    <option value="SI" <?= $row['activo'] == 'SI' ? 'selected' : '' ?>>Sí</option>
                    <option value="NO" <?= $row['activo'] == 'NO' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Proveedor</button>
            <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
