<?php

include_once("backend/modelos/ventas.php");
include_once("backend/modelos/cortes.php");
include_once("conexion.php");


class ControladorVentas {

    public function crear() {
        include_once("backend/vistas/ventas/crear.php");
    }

    public function informacion() {
        if (isset($_POST['fecha_consulta'])) {
            $fecha_consulta = $_POST['fecha_consulta'];
        } else {
            $fecha_consulta = null;
        }
        
        $ventas=Ventas::consultarRegistros($fecha_consulta);
        $cortes = Cortes::consultar($fecha_consulta);

        // Ordenar la lista de ventas por fecha
        
        include_once("backend/vistas/ventas/informacion.php");
        
        
    }

    public function guardar() {
        // Verifica si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados
            $recibos = isset($_POST['recibos']) ? json_decode($_POST['recibos'], true) : null;
            $total_recibos = isset($_POST['total_recibos']) ? $_POST['total_recibos'] : null;
            if ($recibos && $total_recibos !== null) {

                date_default_timezone_set('America/Mexico_City');
                $fecha = date('Y-m-d');
                $hora = date('H:i:s');                
                
                if (isset($_SESSION['id'])) {
                    $user_id = $_SESSION['id'];

                    try {
                        // Inicia una transacción
                        $conexionBD = BD::crearInstancia();
                        $conexionBD->beginTransaction();

                        // Guardar la venta usando el modelo Ventas
                        $venta_id = Ventas::guardarVentas($fecha, $hora, $total_recibos, $user_id);

                        // Guardar los recibos
                        foreach ($recibos as $monto_recibo) {
                            Ventas::guardarRecibo($fecha, $hora, $monto_recibo, $venta_id);
                        }

                        // Confirmar la transacción
                        $conexionBD->commit();

                        // Redirección después de guardar todo correctamente
                        
                        header("Location: ./?controlador=ventas&accion=crear");
                        exit(); 

                    } catch (Exception $e) {
                        // Revertir la transacción si ocurre un error
                        $conexionBD->rollBack();
                        echo json_encode(['success' => false, 'error' => 'Error al guardar en la base de datos']);
                        return;
                    }

                    // Devuelve una respuesta JSON
                    echo json_encode(['success' => true]);
                    return; // Salir del método después de la operación exitosa
                } else {
                    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
                }
            } else {
                // Error en el JSON recibido
                echo json_encode(['success' => false, 'error' => 'Datos no válidos']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Solicitud no válida']);
        }
    }

    public function editar() {
        if(isset($_POST['monto_recibo'])) {
            try {
                $id = $_GET['id'];
                $monto_recibo = $_POST['monto_recibo'];
                Ventas::editar($id, $monto_recibo);
                header("Location:./?controlador=ventas&accion=editar&id=".$id."&success=Monto actualizado correctamente");
            } catch (Exception $e) {
                header("Location:./?controlador=ventas&accion=editar&id=".$id."&error=Error al actualizar el monto");
            }
            
            
        }
        $id=$_GET['id'];
        $recibo=Ventas::buscar($id);
        include_once("backend/vistas/ventas/editar.php");
    }

    public function borrar() {
        if ($_GET) {
            try {
                $id=$_GET['id'];
                Ventas::borrar($id);
                header("Location:./?controlador=ventas&accion=informacion&success=Venta eliminada correctamente.");
                
            } catch (Exception $e) {
                header("Location:./?controlador=ventas&accion=informacion&error=Error al eliminar el registro." . $e->getMessage());                
            }
        }
    }

    
}
?>
