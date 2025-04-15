<?php

include_once("backend/modelos/ventas.php");
include_once("backend/modelos/clientes.php");
include_once("backend/modelos/periodos.php");
include_once("backend/modelos/clientes_has_periodos.php");
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
            // Si no se proporciona una fecha, se utiliza la fecha de hoy
            date_default_timezone_set('America/Mexico_City');
            $fecha_consulta = date('Y-m-d');
        }
        //Obtiene cada uno de los registros
        $ventas=Ventas::consultarRegistros($fecha_consulta);
        $cortes = Cortes::consultarRegistros($fecha_consulta);

        //Obtiene la suma de los montos de cada registro
        $venta_total = Ventas::calcularTotalVentas($fecha_consulta);
        $cortes_total = Cortes::calcularTotalCortes($fecha_consulta);
        $monto_total_talonarios = Cortes::consultarTalonariosEnCorte($fecha_consulta);

        //Cuenta la cantidad de registros
        $talonarios_total = Ventas::CalcularTotalDeTalonarios($fecha_consulta);

        // Ordenar la lista de ventas por fecha
        
        include_once("backend/vistas/ventas/informacion.php");
        
        
    }

    public function guardar() {
        // Verifica si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados
            $recibos = isset($_POST['recibos']) ? json_decode($_POST['recibos'], true) : null;
            $servicios = isset($_POST['clientes']) ? json_decode($_POST['clientes'], true) : null;
            $total_recibos = isset($_POST['total_recibos']) ? $_POST['total_recibos'] : null;

            print_r($recibos);
            print_r($servicios);
            print_r($total_recibos);
            if ($recibos !== null && $total_recibos !== null && $servicios !== null) {

                date_default_timezone_set('America/Mexico_City');
                $fecha = date('Y-m-d');
                $hora = date('H:i:s');                
                
                if (isset($_SESSION['id'])) {
                    $user_id = $_SESSION['id'];

                    // Validar que todos los clientes existen en la base de datos
                    $todos_existen = true;
                    $cliente_ids = [];
                
                    foreach ($servicios as $no_servicio) {
                        $cliente_id = Clientes::obtenerIdPorNumeroServicio($no_servicio);
                        if (!$cliente_id) {
                            $todos_existen = false;
                            break; // Salir del bucle si algún cliente no existe
                        }
                        $cliente_ids[] = $cliente_id; // Almacenar el ID del cliente
                    }

                    if ($todos_existen) {
                        $ultimo_periodo_id = Periodos::obtenerUltimoPeriodo();
                         

                        try {
                            // Inicia una transacción
                            $conexionBD = BD::crearInstancia();
                            $conexionBD->beginTransaction();
    
                            // Guardar la venta usando el modelo Ventas
                            $venta_id = Ventas::guardarVentas($fecha, $hora, $total_recibos, $user_id);
    
                            // Guardar los recibos y asociar con clientes_has_periodos
                            foreach ($recibos as $index => $monto_recibo) {
                                $cliente_id = $cliente_ids[$index];
                                $clientes_has_periodos_id = ClientesHasPeriodos::obtenerId($cliente_id, $ultimo_periodo_id);
                                if ($clientes_has_periodos_id) {
                                    Ventas::guardarRecibo($fecha, $hora, $monto_recibo, $venta_id, $clientes_has_periodos_id);

                                    Periodos::actualizarEstado($ultimo_periodo_id, $cliente_id);
                                } else {
                                    // Manejar caso si no se encuentra el ID en clientes_has_periodos
                                    throw new Exception("No se encontró el ID en clientes_has_periodos para cliente_id: $cliente_id");
                                }
                            }
    
                            // Confirmar la transacción
                            $conexionBD->commit();
    
                            // Redirección después de guardar todo correctamente
                            
                            header("Location: ./?controlador=ventas&accion=crear");
                            exit(); 
    
                        } catch (Exception $e) {
                            // Revertir la transacción si ocurre un error
                            $conexionBD->rollBack();
                            echo json_encode(['success' => false, 'error' => 'Error al guardar en la base de datos'. $e->getMessage()]);
                            return;
                        }
    
                        // Devuelve una respuesta JSON
                        echo json_encode(['success' => true]);
                        return; // Salir del método después de la operación exitosa

                            
                    }

                    
                } else {
                    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
                }
            } else {
                // Error en el JSON recibido
                echo json_encode(['success' => false, 'error' => 'verificar recibo en cero']);
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
