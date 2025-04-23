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

        $cliente_nuevo = $conexionBD->lastInsertId();

        return $cliente_nuevo;
    }

    

    public static function contarClientes() {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->query("SELECT COUNT(*) AS total FROM clientes");
        $resultado = $sql->fetch();
        return $resultado['total'];
    }


    //consulta inicial sin filtrado
    public static function consultarClientesPaginados($offset, $limit) {
        $conexionBD = BD::crearInstancia();
        
        // Consulta con ordenamiento alfabético por nombres, ap_pat y ap_mat
        $sql = $conexionBD->prepare("
            SELECT * FROM clientes 
            ORDER BY ap_pat ASC
            LIMIT :offset, :limit
        ");
        
        // Asociamos los parámetros para la paginación
        $sql->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $sql->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        
        // Ejecutamos la consulta
        $sql->execute();
        
        // Recogemos los resultados
        $clientes = [];
        foreach ($sql->fetchAll() as $cliente) {
            $clientes[] = new Clientes($cliente['id'], $cliente['numero_servicio'], $cliente['nombres'], $cliente['ap_pat'], $cliente['ap_mat'], $cliente['direccion']);
        }
        
        return $clientes;
    }
    

    // Función para consultar clientes filtrados con paginación
    public static function consultarClientesFiltrados($numero_servicio = null, $nombres = null, $ap_pat = null, $ap_mat = null, $inicio = 0, $registros_por_pagina = 45) {
        $lista_clientes = [];
        $conexionBD = BD::crearInstancia();
        
        // Consulta base
        $sql_query = "SELECT * FROM clientes WHERE 1=1";
        $params = [];
        
        // Añadir filtros
        if ($numero_servicio){
            $sql_query .= " AND numero_servicio = ?";
            $params[] = $numero_servicio; // Búsqueda exacta
        }
        if ($nombres) {
            $sql_query .= " AND nombres LIKE ?";
            $params[] = "%$nombres%";
        }
        
        // Filtro exacto para ap_pat
        if ($ap_pat) {
            $sql_query .= " AND ap_pat = ?";
            $params[] = $ap_pat; // Búsqueda exacta
        }
    
        if ($ap_mat) {
            $sql_query .= " AND ap_mat LIKE ?";
            $params[] = "%$ap_mat%";
        }
        
        // Ordenar por apellido paterno de forma ascendente
        $sql_query .= " ORDER BY ap_pat ASC";
    
        // Añadir paginación
        $sql_query .= " LIMIT $inicio, $registros_por_pagina";
        
        // Preparar la consulta
        $sql = $conexionBD->prepare($sql_query);
        
        // Asociar los parámetros
        foreach ($params as $key => $value) {
            $sql->bindValue($key + 1, $value);
        }
    
        // Ejecutar la consulta
        $sql->execute();
    
        // Recoger los resultados
        foreach ($sql->fetchAll() as $cliente) {
            $lista_clientes[] = new Clientes($cliente['id'], $cliente['numero_servicio'], $cliente['nombres'], $cliente['ap_pat'], $cliente['ap_mat'], $cliente['direccion']);
        }
    
        return $lista_clientes;
    }
    

    // Función para contar clientes filtrados
    public static function contarClientesFiltrados($numero_servicio = null, $nombres = null, $ap_pat = null, $ap_mat = null) {
        $conexionBD = BD::crearInstancia();
        
        $sql_query = "SELECT COUNT(*) as total FROM clientes WHERE 1=1";
        $params = [];
        
        // Añadir filtros
        if ($numero_servicio){
            $sql_query .= " AND numero_servicio = ?";
            $params[] = $numero_servicio; // Búsqueda exacta
        }
        if ($nombres) {
            $sql_query .= " AND nombres LIKE ?";
            $params[] = "%$nombres%";
        }
        if ($ap_pat) {
            $sql_query .= " AND ap_pat LIKE ?";
            $params[] = "%$ap_pat%";
        }
        if ($ap_mat) {
            $sql_query .= " AND ap_mat LIKE ?";
            $params[] = "%$ap_mat%";
        }

        // Preparar la consulta
        $sql = $conexionBD->prepare($sql_query);

        // Asociar los parámetros
        foreach ($params as $key => $value) {
            $sql->bindValue($key + 1, $value);
        }

        // Ejecutar la consulta
        $sql->execute();

        // Obtener el resultado
        $result = $sql->fetch();
        return $result['total'];
    }
    // Método para obtener el ID de un cliente por su número de servicio
    public static function obtenerIdPorNumeroServicio($numero_servicio) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT id FROM clientes WHERE numero_servicio = ?");
        $sql->execute(array($numero_servicio));
        $cliente = $sql->fetch(PDO::FETCH_ASSOC);
        
        return $cliente ? $cliente['id'] : null;
    }
    // Método para actualizar los datos de un cliente
    public static function actualizarCliente($id, $numero_servicio, $nombres, $ap_pat, $ap_mat, $direccion) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("UPDATE clientes SET numero_servicio =?, nombres =?, ap_pat =?, ap_mat =?, direccion =? WHERE id =?");
        $sql->execute(array($numero_servicio, $nombres, $ap_pat, $ap_mat, $direccion, $id));
    }
    // Método para eliminar un cliente
    public static function eliminarCliente($id) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("DELETE FROM clientes WHERE id =?");
        $sql->execute(array($id));
    }

    public static function obtenerDatosPorNumero($numeroCliente) {
        $conexionBD = BD::crearInstancia();
        $sql = "SELECT nombres, ap_pat, ap_mat FROM clientes WHERE numero_servicio = :numeroCliente";
        $stmt = $conexionBD->prepare($sql);
        $stmt->bindParam(':numeroCliente', $numeroCliente, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function ObtenerEstadoPago($idCliente, $idUltimoPeriodo){
        $conexionBD = BD::crearInstancia();
        $sql = "SELECT c.numero_servicio, c.nombres, c.ap_pat, c.ap_mat, ch.id, ch.clientes_id, ch.estado , ch.periodos_id , ch.monto_pago FROM clientes c INNER JOIN clientes_has_periodos ch on c.id = ch.clientes_id WHERE c.id = :idCliente AND ch.periodos_id = :idUltimoPeriodo";
        $stmt = $conexionBD->prepare($sql);
        $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_STR);
        $stmt->bindParam(':idUltimoPeriodo', $idUltimoPeriodo, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function CancelarVenta($id_cliente){
        $conexionBD = BD::crearInstancia();
        $sql = "UPDATE clientes_has_periodos SET estado = 'no pagado' WHERE clientes_id = :id_cliente";
        $stmt = $conexionBD->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public static function EliminarRecibo($id_cliente_has_periodo){
        $conexionBD = BD::crearInstancia();
        $sql = "DELETE FROM recibo WHERE clientes_has_periodos_id = :id_cliente_has_periodo";
        $stmt = $conexionBD->prepare($sql);
        $stmt->bindParam(':id_cliente_has_periodo', $id_cliente_has_periodo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }
    
}
?>