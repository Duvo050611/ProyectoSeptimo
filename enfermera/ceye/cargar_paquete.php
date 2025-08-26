  <?php
include "../../conexionbd.php";
if (@$_GET['q'] == 'cargar') {
  $paquete = $_GET['paquete'];
  $paciente = $_GET['paciente'];
  $id_usua = $_GET['id_usua'];
   date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
  $sql = "SELECT * FROM paquetes_ceye where nombre = '$paquete'";

  $result = $conexion->query($sql);

  while ($row = $result->fetch_assoc()) {

    $item_id = $row['material_id'];
    $cart_uniquid = uniqid();
    $qty = $row['cantidad'];

    $sql_stock = "SELECT * FROM stock_ceye s , paquetes_ceye p where $item_id = s.material_id ";
    //  echo $sql_stock;
    $result_stock = $conexion->query($sql_stock);

    while ($row_stock = $result_stock->fetch_assoc()) {
      $stock_id = $row_stock['stock_id'];
      $stock_qty = $row_stock['stock_qty'];
    }
    // echo $stock_qty - $qty;
    if (($stock_qty - $qty) >= 0) {
      $sql2 = "INSERT INTO cart_ceye(material_id,cart_qty,cart_stock_id,id_usua,cart_uniqid, paciente,cart_fecha)VALUES($item_id,$qty, $stock_id,$id_usua,'$cart_uniquid', $paciente, $fecha_actual);";
      //echo $sql2;
      $result_cart = $conexion->query($sql2);
      $stock = $stock_qty - $qty;
      $sql3 = "UPDATE stock_ceye set stock_qty=$stock where stock_id = $stock_id";
      $result3 = $conexion->query($sql3);
    }
  }
  echo '<script type="text/javascript">window.location.href = "order_enf_ceye.php?paciente=' . $paciente . '";</script>';
}

if (@$_GET['q'] == 'eliminar') {
  $paciente = $_GET['paciente'];
  $cart_stock_id = $_GET['cart_stock_id'];
  $cart_qty = $_GET['cart_qty'];
  $cart_id = $_GET['cart_id'];


  $sql2 = "SELECT * FROM stock_ceye where stock_id = $cart_stock_id";
  $result = $conexion->query($sql2);

  while ($row_stock = $result->fetch_assoc()) {
    $stock = $row_stock['stock_qty'];
  }

  $stock_final = $stock + $cart_qty;


  $sql1 = "DELETE FROM cart_ceye WHERE cart_id = $cart_id";
  $result1 = $conexion->query($sql1);


  $sql3 = "UPDATE stock_ceye set stock_qty=$stock_final where stock_id = $cart_stock_id";
  $result3 = $conexion->query($sql3);
  echo '<script type="text/javascript">window.location.href = "order_enf_ceye.php?paciente=' . $paciente . '";</script>';
}

if (@$_GET['q'] == 'eliminar_serv') {
  $paciente = $_GET['paciente'];
  $cart_qty = $_GET['cart_qty'];
  $cart_id = $_GET['cart_id'];


  $sql1 = "DELETE FROM cart_serv WHERE cart_id = $cart_id";
  $result1 = $conexion->query($sql1);

  $sql1 = "DELETE FROM equipos_ceye WHERE cart_id = $cart_id";
  $result1 = $conexion->query($sql1);

  echo '<script type="text/javascript">window.location.href = "../../enfermera/registro_quirurgico/nav_med.php";</script>';
}

if (@$_GET['q'] == 'eliminarquir') {
  $paciente = $_GET['paciente'];
  $cart_stock_id = $_GET['cart_stock_id'];
  $cart_qty = $_GET['cart_qty'];
  $cart_id = $_GET['cart_id'];


  $sql2 = "SELECT * FROM stock_ceye where stock_id = $cart_stock_id";
  $result = $conexion->query($sql2);

  while ($row_stock = $result->fetch_assoc()) {
    $stock = $row_stock['stock_qty'];
  }

  $stock_final = $stock + $cart_qty;


  $sql1 = "DELETE FROM cart_ceye WHERE cart_id = $cart_id";
  $result1 = $conexion->query($sql1);

  $sql1 = "DELETE FROM medica_enf WHERE  = $cart_id";
  $result1 = $conexion->query($sql1);


  $sql3 = "UPDATE stock_ceye set stock_qty=$stock_final where stock_id = $cart_stock_id";
  $result3 = $conexion->query($sql3);
  echo '<script type="text/javascript">window.location.href = "../../enfermera/registro_quirurgico/nav_med.php";</script>';
}