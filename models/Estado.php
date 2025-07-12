<?php
    require "connection/Connection.php";

    class Estado {

        public function obtener_estados_select() {
            $db = new Connection();
            $query = "SELECT id_edo, nombre FROM estados"; // <- columna corregida
            $resultado = $db->query($query);
            $datos = [];
            if($resultado->num_rows) {
                while ($row = $resultado->fetch_assoc()) {
                    $datos[] = [
                        'id' => $row['id_edo'],     // <- también se ajusta aquí
                        'nombre' => $row['nombre'],
                    ];
                }
            }
            return $datos;
        }

    }
?>
