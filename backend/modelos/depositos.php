<?php

class Depositos{
    public $id;
    public $fecha;
    public $hora;
    public $total_efectivo;
    public $conteo_efectivo;
    public $fecha_venta;
    public $conteo_registros;

    public function __construct($id, $fecha, $hora, $total_efectivo, $conteo_efectivo, $fechas_venta = [], $conteo_registros = 0) {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->total_efectivo = $total_efectivo;
        $this->conteo_efectivo = $conteo_efectivo;
        $this->fechas_venta = $fechas_venta;
        $this->conteo_registros = $conteo_registros;
    }

    public static function ConsultarMontoTotal($fecha) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT SUM(monto_recibo) AS monto_total FROM recibo WHERE fecha = ?");
        $sql->execute(array($fecha));

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado['monto_total'] ?: 0; // Retorna 0 si 'monto_total' es null
    }

    public static function guardarDeposito($fecha, $hora, $total_efectivo, $conteo_efectivo=null){
        $conexionBD = BD::crearInstancia();
        $sql= $conexionBD->prepare("INSERT INTO deposito(fecha, hora, total_efectivo, conteo_efectivo) VALUES(?,?,?,?)");
        $sql->execute(array($fecha, $hora, $total_efectivo, $conteo_efectivo));
        return $conexionBD->lastInsertId();
    }
    
    public static function guardarDepositofechas($efectivo_total, $fecha_venta, $deposito_id){
        $conexionBD = BD::crearInstancia();
        $sql= $conexionBD->prepare("INSERT INTO deposito_fechas(efectivo_total, fecha_venta, deposito_id) VALUES(?,?,?)");
        $sql->execute(array($efectivo_total, $fecha_venta, $deposito_id));

    }


    public static function contarRegistros($fecha) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT COUNT(*) AS total_registros FROM recibo WHERE fecha = ?");
        $sql->execute(array($fecha));
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado['total_registros'] ?: 0; // Retorna 0 si 'total_registros' es null
    }

    public static function informacionReporte() {
        $lista_reportes = []; 
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->query("
            SELECT d.id, d.fecha, d.hora, d.total_efectivo, d.conteo_efectivo, f.fecha_venta
            FROM deposito d 
            INNER JOIN deposito_fechas f ON d.id = f.deposito_id
            ORDER BY d.id, f.fecha_venta
        ");
    
        $reportes = $sql->fetchAll(PDO::FETCH_ASSOC);
        $reportesAgrupados = [];
    
        foreach ($reportes as $datos) {
            $id = $datos['id'];
            if (!isset($reportesAgrupados[$id])) {
                $reportesAgrupados[$id] = [
                    'id' => $id,
                    'fecha' => $datos['fecha'],
                    'hora' => $datos['hora'],
                    'total_efectivo' => $datos['total_efectivo'],
                    'conteo_efectivo' => $datos['conteo_efectivo'],
                    'fechas_venta' => []
                ];
            }
            $reportesAgrupados[$id]['fechas_venta'][] = $datos['fecha_venta'];
        }
    
        foreach ($reportesAgrupados as $reporteDatos) {
            // Contar todos los registros para las fechas de venta
            $totalRegistros = 0;
            foreach ($reporteDatos['fechas_venta'] as $fecha) {
                $totalRegistros += self::contarRegistros($fecha);
            }
            $reporte = new Depositos(
                $reporteDatos['id'], 
                $reporteDatos['fecha'],
                $reporteDatos['hora'], 
                $reporteDatos['total_efectivo'], 
                $reporteDatos['conteo_efectivo'], 
                $reporteDatos['fechas_venta']
            );
            // Añadir el conteo de registros al objeto Depositos
            $reporte->conteo_registros = $totalRegistros;
            $lista_reportes[] = $reporte;
        }
        
        return $lista_reportes;
    }
    
    public static function buscarReport($id){
        $conexionBD = BD::crearInstancia();
        
        // 1. Obtener datos del depósito
        $sql = $conexionBD->prepare("SELECT * FROM deposito WHERE id = ?");
        $sql->execute(array($id));
        $deposito = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$deposito) {
            return null;
        }

        // 2. Obtener fechas de venta asociadas
        $sqlFechas = $conexionBD->prepare("SELECT fecha_venta FROM deposito_fechas WHERE deposito_id = ?");
        $sqlFechas->execute(array($id));
        $fechasRows = $sqlFechas->fetchAll(PDO::FETCH_ASSOC);

        $fechas_venta = [];
        $totalRegistros = 0;

        foreach ($fechasRows as $row) {
            $fechas_venta[] = $row['fecha_venta'];
            $totalRegistros += self::contarRegistros($row['fecha_venta']);
        }

        // 3. Retornar el objeto con todas sus propiedades pobladas
        return new Depositos(
            $deposito['id'], 
            $deposito['fecha'], 
            $deposito['hora'], 
            $deposito['total_efectivo'], 
            $deposito['conteo_efectivo'], 
            $fechas_venta,
            $totalRegistros
        );
    }

    public static function actualizarDeposito($id, $conteo_efectivo){
        $conexionBD = BD::crearInstancia();
        $sql= $conexionBD->prepare("UPDATE deposito SET conteo_efectivo=? WHERE id=?");
        $sql->execute(array($conteo_efectivo, $id));
    }
    public static function borrarDepositoFechas($id){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("DELETE FROM deposito_fechas WHERE deposito_id=?");
        $sql->execute(array($id));
    }

    public static function borrarDeposito($id){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("DELETE FROM deposito WHERE id=?");
        $sql->execute(array($id));
    }

    public static function contarRegistrosReciboPorFecha($fecha) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT COUNT(*) AS total_registros FROM recibo WHERE fecha = ?");
        $sql->execute(array($fecha));
    
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        return $resultado['total_registros'];
    }
    public static function obtenerDetalleClientesPorDeposito($deposito_id) {
    $conexionBD = BD::crearInstancia();
    
    // Consulta relacionando deposito_fechas -> recibo -> clientes_has_periodos -> clientes
        $sql = $conexionBD->prepare("
            SELECT 
                df.fecha_venta,
                r.id AS recibo_id,
                r.monto_recibo AS monto_recibo,
                r.fecha AS fecha_recibo,
                r.hora AS hora_recibo,
                c.id AS cliente_id,
                CONCAT(c.nombres, ' ', c.ap_pat, ' ', c.ap_mat) AS nombre_cliente,
                c.numero_servicio
            FROM deposito_fechas df
            INNER JOIN recibo r ON r.fecha = df.fecha_venta
            INNER JOIN clientes_has_periodos chp ON chp.id = r.clientes_has_periodos_id
            INNER JOIN clientes c ON c.id = chp.clientes_id
            WHERE df.deposito_id = :deposito_id
            ORDER BY r.fecha ASC, r.hora ASC
                    ");
        
        $sql->bindParam(':deposito_id', $deposito_id, PDO::PARAM_INT);
        $sql->execute();
        
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}

?>