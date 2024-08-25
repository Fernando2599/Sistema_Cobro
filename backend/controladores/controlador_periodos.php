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
    
    

}
?>