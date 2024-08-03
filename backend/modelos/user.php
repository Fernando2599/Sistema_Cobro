<?php

class User{

    public $id;
    public $username;
    public $password;
    public $created_at;
    public $updated_at;
    public $rol;

    public function __construct($id, $username, $password, $created_at, $updated_at, $rol){
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->rol = $rol;
    }

    public static function consultar(){
        $lista_usuarios=[]; 
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->query("SELECT u.id, u.username, u.password, u.created_at, u.updated_at, r.rol_nombre FROM user u INNER JOIN rol r on u.rol_id = r.id");

        foreach($sql->fetchAll() as $user){
            $usuario = new User($user['id'], $user['username'],$user['password'], $user['created_at'], $user['updated_at'], $user['rol_nombre']);
            $lista_usuarios[] = $usuario;
        }
        return $lista_usuarios;
    }

    public static function crear($username, $password, $created_at, $updated_at, $rol){
        $conexionBD = BD::crearInstancia();

        $sql= $conexionBD->prepare("INSERT INTO user(username, password, created_at, updated_at, rol_id) VALUES(?,?,?,?,?)");

        $sql->execute(array($username, $password, $created_at, $updated_at, $rol));
    }

    public static function borrar($id){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("DELETE FROM user WHERE id=?");
        $sql->execute(array($id));
    }

    public static function buscar($id){
        $conexionBD = BD::crearInstancia();

        $sql = $conexionBD->prepare("SELECT * FROM user WHERE id=?");
        $sql->execute(array($id));
        $user = $sql->fetch();
        return new User ($user['id'], $user['username'],$user['password'], $user['created_at'], $user['updated_at'], $user['rol_id']);
    }

    public static function editar($id, $username, $password, $updated_at, $rol){
        $conexionBD = BD::crearInstancia();

        $sql= $conexionBD->prepare("UPDATE user SET username=?, password=?, updated_at=?, rol_id=? WHERE id=?");

        $sql->execute(array($username, $password, $updated_at, $rol, $id));
        
    }
}

?>