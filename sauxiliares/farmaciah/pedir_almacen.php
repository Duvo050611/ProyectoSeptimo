<?php
include "../../conexionbd.php";
session_start();
ob_start();

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
date_default_timezone_set('America/Guatemala');

if (isset($usuario['id_rol'])) {
    if ($usuario['id_rol'] == 11 || $usuario['id_rol'] == 4 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 1) {
        include "../header_farmaciah.php";
    } else {
        session_unset();
        session_destroy();
        echo "<script>window.location='../../index.php';</script>";
        exit();
    }
}

// Procesar inserción desde formulario
if (isset($_POST['item_id']) && isset($_POST['qty'])) {
    $item_id = $_POST['item_id'];
    $qty = $_POST['qty'];

    // Inserta los datos en la tabla `cart_recib`
    $ingresar2 = mysqli_query($conexion, "INSERT INTO cart_recib(item_id, solicita, almacen,id_usua) VALUES ($item_id, $qty, 'FARMACIA',$id_usua)")
    or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

    // Redirige al usuario después de insertar los datos
    echo '<script type="text/javascript">window.location.href = "pedir_almacen.php";</script>';
    exit(); // Termina el script después de la redirección
}

// Otras acciones: confirmar, eliminar, consultar...
if (isset($_GET['q']) && $_GET['q'] == 'conf' && isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];
    $updateQuery = "UPDATE cart_recib SET confirma = 'SI' WHERE id_recib = ?";
    $stmt = $conexion->prepare($updateQuery);
    $stmt->bind_param('i', $cart_id);

    if ($stmt->execute()) {
        header("Location: pedir_almacen.php?success_confirm=true");
        exit();
    } else {
        echo "<script>alert('Error al confirmar');</script>";
    }
    $stmt->close();
}

if (isset($_GET['q']) && $_GET['q'] == 'eliminar' && isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];
    $deleteQuery = "DELETE FROM cart_recib WHERE id_recib = ?";
    $stmt = $conexion->prepare($deleteQuery);
    $stmt->bind_param('i', $cart_id);
    if ($stmt->execute()) {
        header("Location: pedir_almacen.php?success_delete=true");
        exit();
    } else {
        echo "<script>alert('Error al eliminar el registro');</script>";
    }
    $stmt->close();
}

$resultado = $conexion->query("SELECT * FROM cart_recib c, item_almacen i WHERE i.item_id = c.item_id AND c.almacen = 'FARMACIA'") or die($conexion->error);

if (isset($_GET['success']) && $_GET['success'] == 'true') {
    echo "<script>alert('Datos insertados correctamente');</script>";
}

if (isset($_GET['success_confirm']) && $_GET['success_confirm'] == 'true') {
    echo "<script>
       alert('Surtido confirmado');
       window.location.href = 'pedir_almacen.php';
     </script>";
    exit();
}

if (isset($_GET['success_delete']) && $_GET['success_delete'] == 'true') {
    echo "<script>
        alert('Registro eliminado exitosamente');
        window.location.href = 'pedir_almacen.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedir a Almacén</title>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --color-primario: #2b2d7f;
            --color-secundario: #1a1c5a;
            --color-fondo: #f8f9ff;
            --color-borde: #e8ebff;
            --sombra: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* ===== ESTILOS GENERALES ===== */
        body {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container-moderno {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin: 20px auto;
            max-width: 98%;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
        }

        /* ===== BOTONES MODERNOS ===== */
        .btn-moderno {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: var(--sombra);
        }

        .btn-regresar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white !important;
            margin-left: 30px;
            margin-bottom: 20px;
        }

        .btn-agregar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }

        .btn-confirmar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
            padding: 8px 12px;
            font-size: 14px;
        }

        .btn-eliminar {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white !important;
            padding: 8px 12px;
            font-size: 14px;
        }

        .btn-moderno:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        /* ===== HEADER SECTION ===== */
        .header-principal {
            text-align: center;
            margin-bottom: 30px;
            padding: 25px 0;
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            border-radius: 20px;
            color: white;
            box-shadow: var(--sombra);
            position: relative;
        }

        .header-principal .icono-principal {
            font-size: 36px;
            margin-bottom: 10px;
            display: block;
        }

        .header-principal h2 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* ===== FORMULARIO MODERNIZADO ===== */
        .contenedor-formulario {
            background: white;
            border: 2px solid var(--color-borde);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: var(--sombra);
        }

        .form-control {
            border: 2px solid var(--color-borde);
            border-radius: 10px;
            transition: all 0.3s ease;
            height: 48px;
        }

        .form-control:focus {
            border-color: var(--color-primario);
            box-shadow: 0 0 0 3px rgba(43, 45, 127, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--color-primario);
            margin-bottom: 8px;
        }

        /* ===== TABLA MODERNIZADA ===== */
        .tabla-contenedor {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--sombra);
            border: 2px solid var(--color-borde);
            margin: 25px 0;
        }

        .table-moderna {
            margin: 0;
            font-size: 14px;
            width: 100%;
            border-collapse: collapse;
        }

        .table-moderna thead th {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
            color: white;
            border: none;
            padding: 15px 12px;
            font-weight: 600;
            text-align: center;
            font-size: 14px;
            white-space: nowrap;
        }

        .table-moderna tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-moderna tbody tr:hover {
            background-color: var(--color-fondo);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table-moderna tbody td {
            padding: 12px;
            vertical-align: middle;
            border: none;
            text-align: center;
            font-size: 13px;
        }

        /* ===== BADGES DE ESTADO ===== */
        .badge-confirmado {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pendiente {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .container-moderno {
                margin: 10px;
                padding: 20px;
                border-radius: 15px;
            }

            .header-principal h2 {
                font-size: 22px;
            }

            .btn-moderno {
                padding: 10px 16px;
                font-size: 14px;
            }

            .table-moderna {
                font-size: 12px;
            }

            .table-moderna thead th,
            .table-moderna tbody td {
                padding: 8px 6px;
            }

            .btn-regresar {
                margin-left: 15px;
            }
        }

        /* ===== ANIMACIONES ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container-moderno {
            animation: fadeInUp 0.6s ease-out;
        }

        .contenedor-formulario,
        .tabla-contenedor {
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        /* ===== TÍTULO DE SECCIÓN ===== */
        .titulo-seccion {
            color: var(--color-primario);
            font-weight: 700;
            font-size: 22px;
            margin: 25px 0 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .titulo-seccion i {
            font-size: 24px;
        }
    </style>
</head>

<body>
<!-- Botón Regresar -->
<a href="../../template/menu_farmaciahosp.php" class="btn-moderno btn-regresar">
    <i class="fas fa-arrow-left"></i>
    Regresar
</a>

<!-- Container Principal -->
<div class="container-moderno">
    <!-- Header Principal -->
    <div class="header-principal">
        <i class="fas fa-pills icono-principal"></i>
        <h2>PEDIR A ALMACÉN</h2>
    </div>

    <!-- Formulario para Agregar Medicamentos -->
    <div class="contenedor-formulario">
        <h3 class="titulo-seccion">
            <i class="fas fa-plus-circle"></i>
            Agregar Medicamento
        </h3>

        <form action="" method="POST" id="medicamentos">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label class="form-label">MEDICAMENTOS:</label>
                    <select name="item_id" class="form-control" required>
                        <option value="">Seleccione un medicamento...</option>
                        <?php
                        // Consulta con DISTINCT para evitar duplicados y JOIN para obtener el item_name
                        $sql = "
                                SELECT DISTINCT ia.item_id, ia.item_name, ia.item_grams
                                FROM item_almacen ia
                                WHERE ia.item_id IS NOT NULL
                                ORDER BY ia.item_name";
                        $result = $conexion->query($sql);
                        while ($row_datos = $result->fetch_assoc()) {
                            echo "<option value='" . $row_datos['item_id'] . "'>" .
                                    htmlspecialchars($row_datos['item_name']) . ', ' .
                                    htmlspecialchars($row_datos['item_grams']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">CANTIDAD:</label>
                    <input type="number" name="qty" class="form-control" min="1" required>
                </div>
                <div class="col-md-4 d-flex justify-content-center">
                    <button type="submit" class="btn-moderno btn-agregar">
                        <i class="fas fa-plus"></i>
                        AGREGAR
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de Medicamentos -->
    <div class="titulo-seccion">
        <i class="fas fa-list"></i>
        Medicamentos Solicitados
    </div>

    <div class="tabla-contenedor">
        <table class="table table-moderna">
            <thead>
            <tr>
                <th><i class="fas fa-hashtag"></i> No.</th>
                <th><i class="fas fa-calendar"></i> FECHA</th>
                <th><i class="fas fa-pills"></i> NOMBRE DEL MEDICAMENTO</th>
                <th><i class="fas fa-sort-numeric-up"></i> CANTIDAD</th>
                <th><i class="fas fa-info-circle"></i> ESTADO</th>
                <th><i class="fas fa-cogs"></i> ACCIONES</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $hay_datos = false;
            while ($row_lista = $resultado->fetch_assoc()) {
                $hay_datos = true;
                $estado = ($row_lista['confirma'] == 'SI') ? 'Confirmado' : 'No Confirmado';
                $badge_class = ($row_lista['confirma'] == 'SI') ? 'badge-confirmado' : 'badge-pendiente';

                $action_buttons = '';
                if ($row_lista['confirma'] == 'NO') {
                    $action_buttons = '<a class="btn-moderno btn-confirmar" href="?q=conf&cart_id=' . $row_lista['id_recib'] . '" title="Confirmar">
                                <i class="fas fa-check"></i>
                            </a>';
                }
                $action_buttons .= ' ';
                $action_buttons .= '<a class="btn-moderno btn-eliminar" href="?q=eliminar&cart_id=' . $row_lista['id_recib'] . '" 
                            onclick="return confirm(\'¿Está seguro de eliminar este registro?\')" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </a>';

                echo '<tr>';
                echo '<td><strong>' . $no . '</strong></td>';
                echo '<td>' . htmlspecialchars($row_lista['fecha']) . '</td>';
                echo '<td>' . htmlspecialchars($row_lista['item_name']) . ', ' . htmlspecialchars($row_lista['item_grams']) . '</td>';
                echo '<td><span class="badge bg-info">' . htmlspecialchars($row_lista['solicita']) . '</span></td>';
                echo '<td><span class="' . $badge_class . '">' . $estado . '</span></td>';
                echo '<td>' . $action_buttons . '</td>';
                echo '</tr>';
                $no++;
            }

            if (!$hay_datos) {
                echo '<tr>';
                echo '<td colspan="6" class="text-center py-5">';
                echo '<i class="fas fa-inbox" style="font-size: 48px; color: #ccc; display: block; margin-bottom: 15px;"></i>';
                echo '<h5 style="color: #666;">No hay medicamentos solicitados</h5>';
                echo '<p style="color: #999;">Agregue medicamentos usando el formulario superior</p>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
      integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
      integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</body>

</html>