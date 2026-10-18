<?php
include_once("conexion.php");
include_once("backend/modelos/depositos.php");
include_once("backend/modelos/cortes.php");

use Dompdf\Dompdf;
use Dompdf\Options;

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

  public function generarPdfDetallado() {
        if (isset($_GET['id'])) {
            $deposito_id = $_GET['id'];

            // 1. Obtener datos del depósito general
            $reporte = Depositos::buscarReport($deposito_id);

            if (!$reporte) {
                header("Location: ./?controlador=depositos&accion=reportes&error=Reporte no encontrado.");
                exit();
            }

            // 2. Obtener los detalles de los clientes
            $detallesClientes = Depositos::obtenerDetalleClientesPorDeposito($deposito_id);

            // 3. AGRUPAR LOS REGISTROS POR FECHA DE COBRO/VENTA
            $registrosPorFecha = [];
            if (!empty($detallesClientes)) {
                foreach ($detallesClientes as $row) {
                    $fecha = $row->fecha_venta;
                    if (!isset($registrosPorFecha[$fecha])) {
                        $registrosPorFecha[$fecha] = [];
                    }
                    $registrosPorFecha[$fecha][] = $row;
                }
            }

            // 4. Estructurar plantilla HTML para PDF
            ob_start();
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Reporte Detallado #<?php echo $reporte->id; ?></title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 15px; }
                    .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 8px; margin-bottom: 15px; }
                    .header h2 { margin: 0; color: #2c3e50; }
                    .resumen { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
                    .resumen td { padding: 5px; background: #f8f9fa; border: 1px solid #dee2e6; }
                    
                    /* Estilos para las tablas agrupadas por fecha */
                    .bloque-fecha { margin-bottom: 20px; }
                    .titulo-fecha { 
                        background-color: #2c3e50; 
                        color: #ffffff; 
                        padding: 6px 10px; 
                        font-size: 12px; 
                        font-weight: bold; 
                        margin-top: 15px;
                        margin-bottom: 0;
                        border-radius: 3px 3px 0 0;
                    }
                    .grid-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
                    .grid-table th { background-color: #34495e; color: #ffffff; padding: 6px; border: 1px solid #1a252f; text-align: left; }
                    .grid-table td { border: 1px solid #ccc; padding: 5px; text-align: left; }
                    .grid-table tr:nth-child(even) { background-color: #f9f9f9; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .subtotal-row { background-color: #e9ecef; font-weight: bold; }
                    .total-general-box { 
                        margin-top: 20px; 
                        padding: 10px; 
                        background-color: #2c3e50; 
                        color: #fff; 
                        text-align: right; 
                        font-size: 13px; 
                        font-weight: bold; 
                    }

                    /* OPCIONAL: Si deseas que cada fecha empiece en una página nueva de PDF, 
                       descomenta la siguiente línea: */
                    /* .salto-pagina { page-break-after: always; } */

                    /* EVITAR QUE EL TFOOT SE REPITA EN CADA PÁGINA */
                    tfoot { 
                        display: table-row-group; 
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>REPORTE DETALLADO DE DEPOSITOS #<?php echo $reporte->id; ?></h2>
                    <p>Fecha de Creación: <?php echo $reporte->fecha; ?></p>
                </div>

                <table class="resumen">
                    <tr>
                        <td><strong>Total Talonarios:</strong> <?php echo $reporte->conteo_registros; ?></td>
                        <td><strong>Total Depósito:</strong> $<?php echo number_format((float)$reporte->total_efectivo, 2); ?></td>
                        <td><strong>Efectivo Contado:</strong> $<?php echo number_format((float)$reporte->conteo_efectivo, 2); ?></td>
                    </tr>
                </table>

                <h3 style="margin-bottom: 5px; color: #2c3e50;">Desglose de Recibos por Fecha</h3>

                <?php 
                $totalGeneralSuma = 0;
                $contadorGlobal = 1;

                if (!empty($registrosPorFecha)): 
                    foreach ($registrosPorFecha as $fechaVenta => $registros): 
                        $subtotalFecha = 0;
                ?>
                    <!-- BLOQUE POR FECHA -->
                    <div class="bloque-fecha salto-pagina">
                        <div class="titulo-fecha">
                            Fecha de Cobro: <?php echo $fechaVenta; ?>
                        </div>

                        <table class="grid-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%;">#</th>
                                    <th style="width: 20%;">No. Servicio</th>
                                    <th>Cliente</th>
                                    <th class="text-right" style="width: 20%;">Monto recibido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registros as $row): 
                                    $monto = (float)($row->monto_recibo ?? 0);
                                    $subtotalFecha += $monto;
                                    $totalGeneralSuma += $monto;
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $contadorGlobal++; ?></td>
                                    <td><?php echo $row->numero_servicio; ?></td>
                                    <td><?php echo htmlspecialchars($row->nombre_cliente); ?></td>
                                    <td class="text-right">$<?php echo number_format($monto, 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="subtotal-row">
                                    <td colspan="3" class="text-right">SUBTOTAL (<?php echo $fechaVenta; ?>):</td>
                                    <td class="text-right">$<?php echo number_format($subtotalFecha, 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php 
                    endforeach; 
                else: 
                ?>
                    <p class="text-center">No se encontraron recibos o clientes registrados para las fechas de este reporte.</p>
                <?php endif; ?>

                <!-- CAJA DE TOTAL GENERAL AL FINAL DEL REPORTE -->
                <div class="total-general-box">
                    TOTAL GENERAL DEL DEPÓSITO: $<?php echo number_format($totalGeneralSuma, 2); ?>
                </div>

            </body>
            </html>
            <?php
            $html = ob_get_clean();

            // Limpiar buffer
            if (ob_get_length()) {
                ob_end_clean();
            }

            // Generación con Dompdf
            if (class_exists('Dompdf\Dompdf')) {
                $options = new \Dompdf\Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', true);

                $dompdf = new \Dompdf\Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                $dompdf->stream("Reporte_Detallado_Deposito_" . $reporte->id . ".pdf", ["Attachment" => false]);
                exit();
            } else {
                echo "<script>window.print();</script>";
                echo $html;
                exit();
            }
        }
    }

}

?>