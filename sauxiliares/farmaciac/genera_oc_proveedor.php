<?php
session_start();
include "../../conexionbd.php";

if (!isset($_SESSION['login'])) {
    echo "<script>window.location='../../index.php';</script>";
    exit;
}

include "../header_farmaciac.php";
$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];

// Consulta para obtener los proveedores que tienen productos en la tabla de existencias
$query_proveedores = "SELECT DISTINCT p.id_prov, p.nom_prov
    FROM proveedores p
    INNER JOIN item_almacen ia ON ia.id_prov = p.id_prov
    WHERE ia.activo = 'SI'";

$proveedores_result = $conexion->query($query_proveedores);

if (!$proveedores_result) {
    die("Error al obtener proveedores: " . $conexion->error);
}

$productos_result = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_prov_seleccionado = $_POST['id_prov'];

    // Consulta para obtener los productos del proveedor seleccionado en la tabla de existencias
    $query_productos = "SELECT ia.item_id, ia.factor, ia.item_name, ia.item_grams, ia.item_costs, ia.item_max, ia.item_min, ia.reorden, SUM(ea.existe_qty) AS existencias FROM item_almacen ia LEFT JOIN existencias_almacen ea ON ea.item_id = ia.item_id WHERE ia.id_prov = '$id_prov_seleccionado' AND ia.activo = 'SI' GROUP BY ia.item_id, ia.factor, ia.item_name, ia.item_costs, ia.item_max, ia.item_min, ia.reorden";
    $productos_result = $conexion->query($query_productos);
    if (!$productos_result) {
        die("Error al obtener productos: " . $conexion->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Orden de Compra</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Orden de Compra</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="mb-5">
            <a type="submit" class="btn btn-danger" href="ordenes_compra.php">Regresar</a>
        </div>
    </div>
    <h2 class="text-center">Generar Orden de Compra por Proveedor</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="id_prov">Selecciona Proveedor:</label>
            <select name="id_prov" id="id_prov" class="form-control" required>
                <option value="">Seleccione un proveedor</option>
                <?php while ($prov = $proveedores_result->fetch_assoc()): ?>
                    <option value="<?php echo $prov['id_prov']; ?>"><?php echo $prov['nom_prov']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ver Productos</button>
    </form>

    <?php if ($productos_result && $productos_result->num_rows > 0): ?>
        <h3 class="mt-5">Productos del Proveedor Seleccionado</h3>
        <form method="post" action="guardar_oc_proveedor.php"> <!-- Cambiado a guardar_oc_proveedor.php -->
            <input type="hidden" name="id_prov" value="<?php echo $id_prov_seleccionado; ?>"> <!-- Campo oculto para id_prov -->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Factor</th>
                    <th>Costo</th>
                    <th>Stock Máximo Piezas</th>
                    <th>Punto de Reorden Piezas</th>
                    <th>Existencias</th>
                    <th>Cantidad a Ordenar Cajas</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($producto = $productos_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $producto['item_id']; ?></td>
                        <td><?php echo $producto['item_name'].', '.$producto['item_grams']; ?></td>
                        <td><?php echo $producto['factor']; ?></td>
                        <td><?php echo number_format($producto['item_costs'], 2); ?></td>
                        <td><?php echo $producto['item_max']; ?></td>
                        <td><?php echo $producto['reorden']; ?></td>
                        <td><?php echo $producto['existencias']; ?></td>
                        <td>
                            <input type="number" name="cantidad[<?php echo $producto['item_id']; ?>]" class="form-control" min="0" > <!-- Cambiado min a 0 -->
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success">Generar Orden de Compra</button>
        </form>

    <?php else: ?>
        <p>No hay productos disponibles para el proveedor seleccionado.</p>
    <?php endif; ?>
</div>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>
</body>
</html>
