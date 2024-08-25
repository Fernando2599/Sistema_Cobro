<?php

class ClientesHasPeriodos {
    
    public $id;
    public $clientes_id;
    public $periodos_id;
    public $estado;

    // Constructor
    public function __construct($id, $clientes_id, $periodos_id, $estado) {
        $this->id = $id;
        $this->clientes_id = $clientes_id;
        $this->periodos_id = $periodos_id;
        $this->estado = $estado;
    }

    // Método para obtener el ID de la tabla clientes_has_periodos basado en cliente_id y periodos_id
    public static function obtenerId($clientes_id, $periodos_id) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("
            SELECT id 
            FROM clientes_has_periodos 
            WHERE clientes_id = ? AND periodos_id = ?
        ");
        $sql->execute(array($clientes_id, $periodos_id));
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['id'] : null;
    }
}

?>
