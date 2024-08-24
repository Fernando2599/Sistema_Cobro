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

}

?>