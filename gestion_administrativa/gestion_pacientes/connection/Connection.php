<?php
    class Connection extends Mysqli {
        function __construct() {
            parent::__construct('localhost', 'root', '', 'u542863078_ineo');
            $this->set_charset('utf8');
            $this->connect_error == NULL ? 'DB Conectada' : die('Error al conectarse a la base de datos') ;
        }
    }
?>
