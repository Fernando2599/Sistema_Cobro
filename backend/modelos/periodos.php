<?php

class Periodos{

    public $id;
    public $limite_pago;
    public $periodo_inicio;
    public $periodo_fin;
    public $estado;

    public function __construct($id, $limite_pago, $periodo_inicio, $periodo_fin, $estado){
        $this->id = $id;
        $this->limite_pago = $limite_pago;
        $this->periodo_inicio = $periodo_inicio;
        $this->periodo_fin = $periodo_fin;
        $this->estado = $estado;
    }

    public static function guardarPeriodos($limite_pago, $periodo_inicio, $periodo_fin){
        $conexionBD = BD::crearInstancia();

        // Insertar el nuevo periodo en la tabla "periodos"
        $sql = $conexionBD->prepare("INSERT INTO periodos(limite_pago, periodo_inicio, periodo_fin) VALUES(?,?,?)");
        $sql->execute(array($limite_pago, $periodo_inicio, $periodo_fin));

        // Obtener el ID del período recién insertado
        $periodos_id = $conexionBD->lastInsertId();

        // Asignar el nuevo período a todos los clientes en la tabla "clientes_has_periodos"
        self::asignarPeriodoAClientes($periodos_id, $conexionBD);

        return $periodos_id; // Opcional, devuelve el ID del nuevo período
    }

    public static function asignarPeriodoAClientes($periodos_id, $conexionBD) {
        try {
            // Seleccionar todos los IDs de los clientes en orden ascendente
            $sql = $conexionBD->prepare("SELECT id FROM clientes ORDER BY id ASC");
            $sql->execute();
            $clientes = $sql->fetchAll(PDO::FETCH_ASSOC);

            if (!$clientes) {
                throw new Exception("Error al recuperar los clientes.");
            }
        
            // Insertar el nuevo período para cada cliente en la tabla intermedia "clientes_has_periodos"
            $sqlInsert = $conexionBD->prepare("INSERT INTO clientes_has_periodos (clientes_id, periodos_id) VALUES (?, ?)");
            
            foreach ($clientes as $cliente) {
                $sqlInsert->execute(array($cliente['id'], $periodos_id));
            }
        } catch (PDOException $e) {
            echo "Error en la consulta SQL: " . $e->getMessage();
        }
        
    }

    public static function historialPagos($id_cliente) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("
            SELECT c.nombres, c.ap_pat, c.ap_mat, p.limite_pago, p.periodo_inicio, p.periodo_fin, ch.estado, COALESCE(r.monto_recibo, 0) AS monto_recibo
            FROM clientes_has_periodos ch
            INNER JOIN clientes c ON c.id = ch.clientes_id
            INNER JOIN periodos p ON p.id = ch.periodos_id
            LEFT JOIN recibo r ON r.clientes_has_periodos_id = ch.id
            WHERE ch.clientes_id = ?
            ORDER BY p.periodo_inicio DESC
        ");
        $sql->execute(array($id_cliente));
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function obtenerNombreCliente($id_cliente){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("
            SELECT numero_servicio, nombres, ap_pat, ap_mat 
            FROM clientes 
            WHERE id = ?
        ");
        $sql->execute(array($id_cliente));
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public static function obtenerUltimoPeriodo() {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->query("SELECT id FROM periodos ORDER BY id DESC LIMIT 1");
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['id'] : null;
    }

    public static function actualizarEstado($periodo_id, $cliente_id) {
        $conexionBD = BD::crearInstancia();
        
        $sql = $conexionBD->prepare("UPDATE clientes_has_periodos SET estado = 'pagado' WHERE periodos_id = ? AND clientes_id = ?");
        $sql->execute(array($periodo_id, $cliente_id));
    }

   
}
?>
