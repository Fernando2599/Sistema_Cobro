<?php

class Clientes {
    public $id;
    public $numero_servicio;
    public $nombres;
    public $ap_pat; 
    public $ap_mat;
    public $direccion;

    public function __construct($id, $numero_servicio, $nombres, $ap_pat, $ap_mat, $direccion){
        $this->id = $id;
        $this->numero_servicio = $numero_servicio;
        $this->nombres = $nombres;
        $this->ap_pat = $ap_pat;
        $this->ap_mat = $ap_mat;
        $this->direccion = $direccion;
    }

    public static function guardarClientes($numero_servicio, $nombres, $ap_pat, $ap_mat){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("INSERT INTO clientes(numero_servicio, nombres, ap_pat, ap_mat) VALUES(?,?,?,?)");
        $sql->execute(array($numero_servicio, $nombres, $ap_pat, $ap_mat));
    }

    public static function consultarClientes(){
        $lista_clientes=[];
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->query("SELECT * FROM clientes");
        foreach($sql->fetchAll() as $cliente){
            $cliente = new Clientes($cliente['id'], $cliente['numero_servicio'], $cliente['nombres'], $cliente['ap_pat'], $cliente['ap_mat'], $cliente['direccion']);
            $lista_clientes[] = $cliente;
        }
        return $lista_clientes;
    }

    public static function contarClientes() {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->query("SELECT COUNT(*) AS total FROM clientes");
        $resultado = $sql->fetch();
        return $resultado['total'];
    }

    public static function contarClientesFiltrados($filtro_nombres, $filtro_ap_pat, $filtro_ap_mat) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT COUNT(*) AS total FROM clientes 
            WHERE nombres LIKE ? OR ap_pat LIKE ? OR ap_mat LIKE ?");
        $sql->execute(array("%$filtro_nombres%", "%$filtro_ap_pat%", "%$filtro_ap_mat%"));
        $resultado = $sql->fetch();
        return $resultado['total'];
    }

    public static function consultarClientesPaginados($offset, $limit) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT * FROM clientes LIMIT :offset, :limit");
        $sql->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $sql->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $sql->execute();
        $clientes = [];
        foreach ($sql->fetchAll() as $cliente) {
            $clientes[] = new Clientes($cliente['id'], $cliente['numero_servicio'], $cliente['nombres'], $cliente['ap_pat'], $cliente['ap_mat'], $cliente['direccion']);
        }
        return $clientes;
    }

    public static function consultarClientesFiltradosPaginados($filtro_nombres, $filtro_ap_pat, $filtro_ap_mat, $offset, $limit) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT * FROM clientes 
            WHERE nombres LIKE :nombres OR ap_pat LIKE :ap_pat OR ap_mat LIKE :ap_mat 
            LIMIT :offset, :limit");
        $sql->bindValue(':nombres', "%$filtro_nombres%", PDO::PARAM_STR);
        $sql->bindValue(':ap_pat', "%$filtro_ap_pat%", PDO::PARAM_STR);
        $sql->bindValue(':ap_mat', "%$filtro_ap_mat%", PDO::PARAM_STR);
        $sql->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $sql->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $sql->execute();
        $clientes = [];
        foreach ($sql->fetchAll() as $cliente) {
            $clientes[] = new Clientes($cliente['id'], $cliente['numero_servicio'], $cliente['nombres'], $cliente['ap_pat'], $cliente['ap_mat'], $cliente['direccion']);
        }
        return $clientes;
    }

}
?>