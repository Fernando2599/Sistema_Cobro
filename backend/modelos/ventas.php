<?php

class Ventas{
    public $id;
    public $fecha;
    public $hora;
    
    public $user_id;
    public $monto_recibo;
    public $clientes_has_periodos; // Nuevo atributo

    public function __construct($id, $fecha, $hora, $user_id, $monto_recibo = null, $clientes_has_periodos = null) {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->user_id = $user_id;
        $this->monto_recibo = $monto_recibo;
        $this->clientes_has_periodos = $clientes_has_periodos; // Inicializa el nuevo atributo
    }

    public static function guardarVentas($fecha, $hora, $total_venta, $user_id){ 
        $conexionBD = BD::crearInstancia();
        $sql= $conexionBD->prepare("INSERT INTO venta(fecha, hora, total_venta, user_id) VALUES(?,?,?,?)");

        $sql->execute(array($fecha, $hora, $total_venta, $user_id));
        return $conexionBD->lastInsertId();
    }

    public static function guardarRecibo($fecha, $hora, $monto_recibo, $venta_id, $clientes_has_periodos){
        
        $conexionBD = BD::crearInstancia();
        $sql= $conexionBD->prepare("INSERT INTO recibo(fecha, hora, monto_recibo, venta_id, clientes_has_periodos_id) VALUES(?,?,?,?,?)");

        $sql->execute(array($fecha, $hora, $monto_recibo, $venta_id, $clientes_has_periodos));
    }

    public static function consultarRegistros($fecha_consulta){
        
        $lista_ventas=[]; 
        $conexionBD = BD::crearInstancia();

        // mañana lunes modificar la consulta para poder consultar a base de la fecha del dia
        $sql = $conexionBD->prepare("SELECT * FROM recibo WHERE fecha=?");
        $sql->execute(array($fecha_consulta));
        
        foreach($sql->fetchAll() as $informacion){
            // Crear instancia de la clase Ventas con la nueva propiedad
            $venta = new Ventas($informacion['id'], $informacion['fecha'], $informacion['hora'], null, $informacion['monto_recibo']);
            $lista_ventas[] = $venta;
        }
        return $lista_ventas;
    }

    // Método para calcular el total de ventas para una fecha dada
    public static function calcularTotalVentas($fecha) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT SUM(monto_recibo) AS total_ventas FROM recibo WHERE fecha = ?");
        $sql->execute(array($fecha));
        
        $resultado = $sql->fetch();
        return $resultado['total_ventas'] ?: 0;
    }

    public static function CalcularTotalDeTalonarios($fecha){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT COUNT(monto_recibo) AS total_talonarios FROM recibo WHERE fecha = ?");
        $sql->execute(array($fecha));
        
        $resultado = $sql->fetch();
        return $resultado['total_talonarios'] ?: 0;
    }

    public static function editar($id, $monto_recibo){
        $conexionBD = BD::crearInstancia();

        $sql= $conexionBD->prepare("UPDATE recibo SET monto_recibo=? WHERE id=?");

        $sql->execute(array($monto_recibo, $id));
        

    }

    public static function borrar($id){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("DELETE FROM recibo WHERE id=?");
        $sql->execute(array($id));
    }

    public static function buscar($id){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT * FROM recibo WHERE id=?");
        $sql->execute(array($id));
        $recibo = $sql->fetch();
        return new Ventas($recibo['id'], $recibo['fecha'], $recibo['hora'], null, $recibo['monto_recibo']);
    }

    


}

?>