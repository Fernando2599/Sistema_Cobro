<?php
include_once("conexion.php");
include_once("backend/modelos/depositos.php");
include_once("backend/modelos/cortes.php");

class ControladorDepositos {
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST)) {
                $fechas = isset($_POST['fechas_depositos']) ? json_decode($_POST['fechas_depositos'], true) : null;
                $monto_total_fechas = 0; // Cambiado de $monto_total_fecha para mantener consistencia

                if ($fechas !== null) {
                    date_default_timezone_set('America/Mexico_City');
                    $fecha_actual = date('Y-m-d');
                    $hora_actual = date('H:i:s');

                    foreach ($fechas as $fecha) {
                        $monto_total_fechas += Depositos::ConsultarMontoTotal($fecha);
                    }

                    try {
                        // Inicia una transacción
                        $conexionBD = BD::crearInstancia();
                        $conexionBD->beginTransaction();

                        // Guardar los depositos
                        $deposito_id = Depositos::guardarDeposito($fecha_actual, $hora_actual, $monto_total_fechas, null);

                        // Guardar las fechas de manera individual
                        foreach ($fechas as $fecha_venta) {
                            $efectivo_total_dia = Depositos::ConsultarMontoTotal($fecha_venta);
                            Depositos::guardarDepositofechas($efectivo_total_dia, $fecha_venta, $deposito_id);
                        }

                        // Confirmar la transacción
                        $conexionBD->commit();
                        header("Location: ./?controlador=depositos&accion=crear&success=Reporte generado exitosamente");
                        exit(); // Detener la ejecución del script
                    } catch (Exception $e) {
                        // Revertir la transacción si ocurre un error
                        $conexionBD->rollBack();
                        header("Location: ./?controlador=depositos&accion=crear&error=Error al generar el reporte");
                        exit(); // Detener la ejecución del script
                    }
                }
            }
        }
        include_once("backend/vistas/depositos/crear.php");
    }

    public function reportes() {
        $reportes = Depositos::informacionReporte();
    
        if (!empty($reportes)) {
            foreach ($reportes as $reporte) {
                if (isset($reporte->fechas_venta)) {
                    foreach ($reporte->fechas_venta as $fecha) {
                        $corte_dia = Cortes::consultarFaltante($fecha);
                        if (isset($corte_dia->faltante)) {
                            $reporte->faltante[] = $corte_dia->faltante;
                        } else {
                            $reporte->faltante[] = "No disponible";
                        }
                    }
                }
            }
    
            // Depuración
            //foreach ($reportes as $reporte) {
              //  var_dump($reporte->faltante); // Verifica el contenido de faltante
            //}
        } else {
            echo "No hay datos disponibles para generar el reporte.";
        }
    
        include_once("backend/vistas/depositos/reporte.php");
    }
    
    

    public function editarReporte(){
        if(isset($_POST['monto_efectivo'])) {
            $id = $_GET['id'];
            $conteo_efectivo = $_POST['monto_efectivo'];

            try {
                // Guardar el corte
                Depositos::actualizarDeposito($id, $conteo_efectivo);
                // Redireccionar con mensaje de éxito
                header("Location: ./?controlador=depositos&accion=editarReporte&id=".$id."&exitoso=El corte ha sido registrado correctamente.");
                exit();
            } catch (Exception $e) {
                header("Location: ./?controlador=depositos&accion=editarReporte&id=".$id."&error=El monto del corte excede el total de ventas disponibles.");
                exit(); // Detener la ejecución del script
            }
        }
        $id=$_GET['id'];
        $reporte=Depositos::buscarReport($id);
        include_once("backend/vistas/depositos/editar.php");
    }
    
    public function borrarReporte() {
        if ($_GET) {
            try {
                $id = $_GET['id'];
                Depositos::borrarDepositoFechas($id);
                Depositos::borrarDeposito($id);
                header("Location:./?controlador=depositos&accion=reportes&exitoso=Reporte eliminado correctamente.");
            } catch (Exception $e) {
                header("Location:./?controlador=depositos&accion=reportes&error=Error al eliminar el reporte.");
                exit(); // Detener la ejecución del script
            }
        }
    }

}

?>