<?php
include '../../conexionbd.php';

if (@$_GET['q'] == 'del_car') {
  $cart_stock_id = $_GET['cart_stock_id'];
  $cart_qty = $_GET['cart_qty'];
  $paciente = $_GET['paciente'];
  $cart_id = $_GET['cart_id'];
  $existec = "NO";

/*
  $sql2 = "SELECT * FROM stock_rojo3 where stock_id = $cart_stock_id";
  $result = $conexion->query($sql2);

  while ($row_stock = $result->fetch_assoc()) {
    $stock = $row_stock['stock_qty'];
    $salidas = $row_stock['stock_salidas'];
  }

  $stock_final = $stock + $cart_qty;
  $stock_salidas = $salidas + $cart_qty;
*/

  $sql1 = "DELETE FROM cart_rojo3 WHERE cart_id = $cart_id";
  $result1 = $conexion->query($sql1);

  $sql3 = "DELETE FROM medicamentos_rojo3 WHERE cart_id = $cart_id";
  $result3 = $conexion->query($sql3);
/*
  $sql2 = "UPDATE stock_rojo3 set stock_qty=$stock_final, stock_salidas=$stock_salidas where stock_id = $cart_stock_id";
  $result2 = $conexion->query($sql2);*/
  header('location: nav_med.php');

 /* echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
  echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
  echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
  echo '<script>
                  $(document).ready(function() {
                      swal({
                          title: "Insumo eliminado correctamente", 
                          type: "success",
                          confirmButtonText: "ACEPTAR"
                      }, function(isConfirm) { 
                          if (isConfirm) {
                              window.location.href = "../hospitalizacion/vista_pac_hosp.php";
                          }
                      });
                  });
              </script>';*/
}

if (@$_GET['q'] == 'comf_cart') 
{
  $paciente = $_GET['paciente'];
  $id_usua = $_GET['id_usua'];
  //$id_cart = $_GET['id_cart'];
  $id_atencion = $_GET['id_atencion'];

  $sql2 = "SELECT * FROM cart_rojo3 , material_rojo3 , item_type  where cart_rojo3.material_id = material_rojo3.material_id and material_rojo3.material_tipo=item_type.item_type_id and paciente = $id_atencion";
  $result = $conexion->query($sql2);

  while ($row_stock = $result->fetch_assoc()) {
    $material_id = $row_stock['material_id'];
    $item_id = $row_stock['material_id'];
    $material_codigo = $row_stock['material_codigo'];
    $material_nombre = $row_stock['material_nombre'];
    $material_fabricante = $row_stock['material_fabricante'];
    $material_contenido = $row_stock['material_contenido'];
    $material_tipo = $row_stock['material_tipo'];
    $cart_qty = $row_stock['cart_qty'];
    $cart_price = $row_stock['cart_price'];
    $qty=$cart_qty;
    $qty_salida=$cart_qty;

    
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");

    $sql_insert = "INSERT INTO sales_rojo3(item_id,item_code,generic_name,brand,gram,type,qty,price,date_sold,paciente,id_usua,fecha_solicitud) VALUES('$material_id','$material_codigo','$material_nombre','$material_fabricante','$material_contenido','$material_tipo',$cart_qty,$cart_price,'$fecha_actual',$id_atencion,$id_usua,'$fecha_actual')";

    $result_insert = $conexion->query($sql_insert);

    $sql_insert_cuenta = "INSERT INTO dat_ctapac(id_atencion,prod_serv,insumo,cta_fec,cta_cant,cta_tot,id_usua,cta_activo)VALUES('$id_atencion','PR3','$material_id','$fecha_actual','$cart_qty',$cart_price,'$id_usua','SI')";
    $result_insert_cuenta = $conexion->query($sql_insert_cuenta);

    $existenciasc=0;
    $salidasc=0;
    $sql2c = $conexion->query("SELECT * FROM stock_rojo3 where stock_rojo3.item_id=$item_id") or die('<p>Error al encontrar stock rojo3</p><br>' . mysqli_error($conexion));
        while ($row_stockc = $sql2c->fetch_assoc()) {
          $existenciasc = $row_stockc['stock_qty'];
          $salidasc = $row_stockc['stock_salidas'];
          $existec = "SI";
        }
    
        $stock_finalc = 0;
        $salidas_finalc = 0;
        if ($existec === "SI") {
            $stock_finalc = $existenciasc - $qty;
            $salidas_finalc = $salidasc + $qty_salida;
            $sql3c = "UPDATE stock_rojo3 set stock_qty=$stock_finalc, stock_salidas=$salidas_finalc, stock_added='$fecha_actual',id_usua=$id_usua where stock_rojo3.item_id = $item_id";
            $result3c = $conexion->query($sql3c);
        }
        else {
            $ingresar = mysqli_query($conexion, 'insert into stock_rojo3 (item_id,stock_qty,stock_salidas,stock_added,id_usua) values(' . $item_id . ',' . $qty . ',' . $qty_salida . ',"' . $fecha_actual . '",' . $id_usua . ')') or die('<p>Error al registrar stock carro rojo</p><br>' . mysqli_error($conexion));
        }

  }
/*
 $insertar=mysqli_query($conexion,'INSERT INTO sales_rojo3(item_code,generic_name,brand,gram,type,qty,price, paciente) values ("'.$material_id.'","'.$material_nombre.'","'.$material_fabricante.'","'.$material_contenido.'","'.$material_tipo.'","'.$cart_qty.'","'.$material_precio.'","'.$paciente.'")')  or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));
*/
  $sql_delCart = "DELETE FROM cart_rojo3 WHERE paciente = $id_atencion";
  $result_delCart = $conexion->query($sql_delCart);

  

  if ($result_delCart && $result_insert) {
header('location: nav_med_rojo3.php');
    /*echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "Insumo Quirúrgico Validado Correctamente", 
                            type: "success",
                            confirmButtonText: "ACEPTAR"
                        }, function(isConfirm) { 
                            if (isConfirm) {
                            window.location.href = "../enfermera/lista_pacientes/vista_pac_enf.php";

                            }
                        });
                    });
                </script>';*/
  } else {

    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                        $(document).ready(function() {
                            swal({
                                title: "Error al confirmar el Insumo de Carro Rojo, Verificar con Farmacia", 
                                type: "error",
                                confirmButtonText: "ACEPTAR"
                            }, function(isConfirm) { 
                                if (isConfirm) {
                                window.location.href = "../lista_pacientes/vista_pac_enf.php";

                                }
                            });
                        });
                    </script>';
  }
}

