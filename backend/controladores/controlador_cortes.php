<?php
include_once("backend/modelos/cortes.php");
include_once("backend/modelos/ventas.php");
include_once("conexion.php");
class ControladorCortes{

    public function crear(){
        if(isset($_POST['monto_corte'])){
            date_default_timezone_set('America/Mexico_City');
            $fecha = date('Y-m-d');
            $hora = date('H:i:s'); 
            $corte_cantidad = $_POST['monto_corte']; // obteniendo datos del form
            $talon_cant= $_POST['talonarios']; // obteniendo datos del form
    
            // Calcula el total de ventas del día
            $total_ventas = Ventas::calcularTotalVentas($fecha);
            // Calcula el total de cortes al día
            $total_cortes = Cortes::calcularTotalCortes($fecha);
            //Calcula la suma total de talonarios registrados en los cortes
            $sum_talon = Cortes::consultarTalonariosEnCorte($fecha);
            //Calcula la cantidad de talonarios
            $count_talon = Ventas::CalcularTotalDeTalonarios($fecha);

    
            if (($corte_cantidad > ($total_ventas - $total_cortes)) || ($talon_cant > ($count_talon - $sum_talon))) {
                // Redireccionar con mensaje de error
                header("Location: ./?controlador=cortes&accion=crear&error=Verifique los montos e intente nuevamente.");
                exit(); // Detener la ejecución del script
            } else {
                // Guardar el corte
                Cortes::guardarCortes($corte_cantidad,$talon_cant, $fecha, $hora);
                // Redireccionar con mensaje de éxito
                header("Location: ./?controlador=cortes&accion=crear&exitoso=El corte ha sido registrado correctamente.");
                exit();
            }
        }
        include_once("backend/vistas/cortes/crear.php");
    }
    

    public function editar() {
        if(isset($_POST['monto_corte'])) {
            $id = $_GET['id'];
            date_default_timezone_set('America/Mexico_City');
            $fecha = date('Y-m-d');
            $corte_cantidad = $_POST['monto_corte'];
            $talonarios = $_POST['talonarios'];

            try {
                // Guardar el corte
                Cortes::editar($id, $corte_cantidad, $talonarios);
                // Redireccionar con mensaje de éxito
                header("Location: ./?controlador=cortes&accion=editar&id=".$id."&exitoso=Registro exitoso :).");
                exit();
            } catch (Exception $e) {
                header("Location: ./?controlador=cortes&accion=editar&id=".$id."&error=El monto del corte excede el total de ventas disponibles.");
                exit(); // Detener la ejecución del script
            }
        }
        $id=$_GET['id'];
        $corte=Cortes::buscar($id);
        include_once("backend/vistas/cortes/editar.php");
    }

    public function borrar() {
        if ($_GET) {
            try {
                $id=$_GET['id'];
                Cortes::borrar($id);
                header("Location:./?controlador=ventas&accion=informacion&success=Registro eliminado correctamente.");
            } catch (Exception $e) {
                header("Location:./?controlador=ventas&accion=informacion&error=Error al eliminar el registro." . $e->getMessage());                
            }
        }
    }

}

?>