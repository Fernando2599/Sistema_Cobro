<?php
include_once("backend/modelos/clientes.php");

include_once("conexion.php");


class ControladorClientes{

    public function inicio() {
        // Parámetros de la paginación
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $registros_por_pagina = 50;
        $offset = ($pagina_actual - 1) * $registros_por_pagina;
    
        // Parámetros de filtrado
        $filtro_nombres = isset($_GET['filter_nombres']) ? $_GET['filter_nombres'] : '';
        $filtro_ap_pat = isset($_GET['filter_ap_pat']) ? $_GET['filter_ap_pat'] : '';
        $filtro_ap_mat = isset($_GET['filter_ap_mat']) ? $_GET['filter_ap_mat'] : '';
    
        if (!empty($filtro_nombres) || !empty($filtro_ap_pat) || !empty($filtro_ap_mat)) {
            $clientes = Clientes::consultarClientesFiltrados($filtro_nombres, $filtro_ap_pat, $filtro_ap_mat, $offset, $registros_por_pagina);
            $total_clientes = Clientes::contarClientesFiltrados($filtro_nombres, $filtro_ap_pat, $filtro_ap_mat);
        } else {
            $clientes = Clientes::consultarClientesPaginados($offset, $registros_por_pagina);
            $total_clientes = Clientes::contarClientes();  // O una función similar para contar todos los clientes
        }
    
        $total_paginas = ceil($total_clientes / $registros_por_pagina);
    
        include_once("backend/vistas/clientes/inicio.php");
    }
    
    

    public function crear() {

        if ($_POST) {

            $no_servicio = $_POST['no_servicio'];
            $nombre = $_POST['nombre'];
            $ap_pat = $_POST['ap_pat'];
            $ap_mat = $_POST['ap_mat'];
            

            try {
                Clientes::guardarClientes($no_servicio, $nombre, $ap_pat, $ap_mat );
                header("Location: ./?controlador=clientes&accion=crear&success=El cliente ha sido registrado correctamente.");
            } catch (Exception $e) {
                // Redireccionar con mensaje de error
                header("Location: ./?controlador=clientes&accion=crear&error=Ocurrió un error al registrar al cliente: " . $e->getMessage());
            }
            exit();
        }
        
        include_once("backend/vistas/clientes/crear.php");
    }

    public function editar() {

        if ($_POST) {
            $id_cliente = $_GET['id'];
            $cliente = $_POST['idCliente'];
            $id_cliente_has_periodo = $_POST['idClientehasPeriodos'];
            try {
                Clientes::CancelarVenta($cliente);
                Clientes::EliminarRecibo($id_cliente_has_periodo);
                header("Location:./?controlador=clientes&accion=editar&id=".$id_cliente."&exitoso=Venta eliminada correctamente.");
            } catch (Exception $e) {
                header("Location:./?controlador=clientes&accion=editar&id=".$id_cliente."&error=Error al eliminar venta: " . $e->getMessage());
            }
            exit();
        }
        include_once("backend/vistas/clientes/editar.php");
    }

    public function obtenerDatosCliente($idCliente) {
        return Clientes::ObtenerEstadoPago($idCliente);
    }

    public function borrar() {
        if ($_GET) {
            try {
                $id = $_GET['id'];
                Clientes::eliminarCliente($id);
                header("Location:./?controlador=clientes&accion=inicio&exitoso=Usuario eliminado correctamente.");
            } catch (Exception $e) {
                header("Location:./?controlador=clientes&accion=inicio&error=Error al eliminar el usuario: " . $e->getMessage());
            }
            exit();
        }
    }

}
?>