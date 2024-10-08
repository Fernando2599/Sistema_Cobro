<?php

class Cortes{
    public $id;
    public $corte_cantidad;
    public $talonarios;
    public $fecha;
    public $hora;
    public $faltante;

    public function __construct($id, $corte_cantidad, $talonarios, $fecha, $hora, $faltante = null){
        $this->id = $id;
        $this->corte_cantidad = $corte_cantidad;
        $this->talonarios = $talonarios;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->faltante = $faltante;
    }

    public static function guardarCortes($corte_cantidad, $talonarios, $fecha, $hora, $faltante){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("INSERT INTO corte(corte_cantidad, talonarios, fecha, hora, faltante) VALUES(?,?,?,?,?)");
        $sql->execute(array($corte_cantidad, $talonarios, $fecha, $hora, $faltante));
    }

    public static function consultarRegistros($fecha_consulta = null){
        date_default_timezone_set('America/Mexico_City');
        $lista_cortes=[]; 
        $conexionBD = BD::crearInstancia();


        // Si no se proporciona una fecha, se utiliza la fecha de hoy
        if ($fecha_consulta === null) {
            $fecha_consulta = date('Y-m-d');
        }

        // mañana lunes modificar la consulta para poder consultar a base de la fecha del dia
        $sql = $conexionBD->prepare("SELECT * FROM corte WHERE fecha=?");
        $sql->execute(array($fecha_consulta));
        
        foreach($sql->fetchAll() as $corte){
            // Crear instancia de la clase Ventas con la nueva propiedad
            $cortes = new Cortes($corte['id'], $corte['corte_cantidad'], $corte['talonarios'], $corte['fecha'], $corte['hora']);
            $lista_cortes[] = $cortes;
        }
        return $lista_cortes;
    }

    public static function calcularTotalCortes($fecha) {
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT SUM(corte_cantidad) AS total_cortes FROM corte WHERE fecha = ?");
        $sql->execute(array($fecha));
        
        $resultado = $sql->fetch();
        return $resultado['total_cortes'] ?: 0;
    }

    public static function consultarTalonariosEnCorte($fecha){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT SUM(talonarios) AS total_talonarios FROM corte WHERE fecha = ?");
        $sql->execute(array($fecha));

        $resultado = $sql->fetch();
        return $resultado['total_talonarios'] ?: 0; //devuelve el valor si en dado caso no es vacio o null
    }

    public static function editar($id, $monto_corte, $talonarios){
        $conexionBD = BD::crearInstancia();

        $sql= $conexionBD->prepare("UPDATE corte SET corte_cantidad=?, talonarios=? WHERE id=?");

        $sql->execute(array($monto_corte, $talonarios, $id));
        header("Location:./?controlador=ventas&accion=informacion");

    }

    public static function borrar($id){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("DELETE FROM corte WHERE id=?");
        $sql->execute(array($id));
    }

    public static function buscar($id){
        $conexionBD = BD::crearInstancia();

        $sql = $conexionBD->prepare("SELECT * FROM corte WHERE id=?");
        $sql->execute(array($id));
        $corte = $sql->fetch();
        return new Cortes ($corte['id'], $corte['corte_cantidad'], $corte['talonarios'],$corte['fecha'], $corte['hora']);
    }
    public static function consultarFaltante1($fecha){
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT faltante FROM corte WHERE fecha =?");
        $sql->execute(array($fecha));
    }
    public static function consultarFaltante($fecha) {
        // Simulación de consulta a la base de datos
        $conexionBD = BD::crearInstancia();
        $sql = $conexionBD->prepare("SELECT faltante FROM corte WHERE fecha = :fecha");
        $sql->execute(['fecha' => $fecha]);
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $corte = new stdClass();
            $corte->faltante = $resultado['faltante'];
            return $corte;
        } else {
            return null;
        }
    }

}

?>