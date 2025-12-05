<?php
date_default_timezone_set('America/Guatemala');

class ConexionBD {

    private static $instancia = null;
    private $conexion;
    private $servidor = "localhost";
    private $nombreBd = "u542863078_ineo";
    private $usuario = "root";
    private $pass = "1234";

    private function __construct() {
        $this->conexion = new mysqli(
            $this->servidor,
            $this->usuario,
            $this->pass,
            $this->nombreBd
        );

        if ($this->conexion->connect_error) {
            die("No se pudo conectar a INEO");
        }

        $this->conexion->set_charset("utf8");
    }
    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

   public function getConexion() {
        return $this->conexion;
    }

    private function __clone() {}
    public function __wakeup() {
        throw new Exception("No se puede deserializar esta clase.");
    }
}
?>
