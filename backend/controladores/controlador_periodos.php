<?php
include_once("backend/modelos/periodos.php");
include_once("conexion.php");

class ControladorPeriodos{

    
    public function crear(){


        if (isset($_POST['fecha_limite']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
            $fecha_corte = $_POST['fecha_limite'];
            $fecha_inicial = $_POST['fecha_inicio'];
            $fecha_limite = $_POST['fecha_fin'];

            Periodos::guardarPeriodos($fecha_corte, $fecha_inicial, $fecha_limite);
            
        }
    
        include_once("backend/vistas/periodos/crear.php");
    }
    

}
?>