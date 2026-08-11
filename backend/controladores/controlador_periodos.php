<?php
include_once("backend/modelos/periodos.php");
include_once("backend/modelos/clientes.php");
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

    public function historial() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id_cliente = $_GET['id'];

            // Obtener el historial de pagos para el cliente
            $historial = Periodos::historialPagos($id_cliente);

            // Obtener el nombre del cliente para pasarlo a la vista
            $nombre_cliente = Periodos::obtenerNombreCliente($id_cliente);
            $monto_pagos = Periodos::obtenerNombreCliente($id_cliente);

            // Incluir la vista y pasar el historial
            include_once("backend/vistas/periodos/historial.php");
        } else {
            echo "ID de cliente no proporcionado.";
        }
    }

    public function ActualizarMonto(){
        if (isset($_POST['nombre']) && isset($_POST['no_servicio']) && isset($_POST['monto_pago'])) {
            $nombre = $_POST['nombre'];
            $no_servicio = $_POST['no_servicio'];
            $monto_pago = $_POST['monto_pago'];
            $cliente_id = Clientes::obtenerIdPorNumeroServicio($no_servicio);
            $id_periodo_actual = Periodos::obtenerUltimoPeriodo();

            try {
                Periodos::actualizarMonto($cliente_id, $monto_pago, $id_periodo_actual);
                header("Location: ./?controlador=periodos&accion=ActualizarMonto&success=El cliente ha sido registrado correctamente.");
            } catch (Exception $e) {
                // Redireccionar con mensaje de error
                header("Location: ./?controlador=periodos&accion=ActualizarMonto&error=Ocurrió un error al registrar al cliente: " . $e->getMessage());
            }
            
        }
        include_once("backend/vistas/periodos/actualizar_monto.php");
    } 
    public function agregarclienteperiodo(){
        if ($_POST) {

            $no_servicio = $_POST['no_servicio'];
            $nombre = $_POST['nombre'];
            $ap_pat = $_POST['ap_pat'];
            $ap_mat = $_POST['ap_mat'];

            $ultimo_periodo_id = Periodos::obtenerUltimoPeriodo();
            

            try {
                Clientes::guardarClientesPeriodos($no_servicio, $nombre, $ap_pat, $ap_mat, $ultimo_periodo_id );
                header("Location: ./?controlador=clientes&accion=crear&success=El cliente ha sido registrado correctamente.");
            } catch (Exception $e) {
                // Redireccionar con mensaje de error
                header("Location: ./?controlador=clientes&accion=crear&error=Ocurrió un error al registrar al cliente: " . $e->getMessage());
            }
            exit();
        }
        include_once("backend/vistas/periodos/agregar_cliente_periodo.php");
    } 

    public function obtenerDatosCliente($numeroCliente) {
        return Clientes::obtenerDatosPorNumero($numeroCliente);
        
    }

}
?>