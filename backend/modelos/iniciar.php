<?php

class Iniciar{

    public static function buscar($username, $password) {
        $conexion = BD::crearInstancia();
        $consulta = $conexion->prepare("SELECT id, username, rol_id FROM user WHERE username = :username AND password = :password");
        $consulta->execute(['username' => $username, 'password' => $password]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    
}

?>