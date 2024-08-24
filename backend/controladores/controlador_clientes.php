<?php
include_once("backend/modelos/clientes.php");

include_once("conexion.php");


class ControladorClientes{

    public function inicio() {
        $clientes=(Clientes::consultarClientes());
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

        if($_POST){
            $id=$_GET['id'];
            $username = $_POST['username'];
            $password = $_POST['pass'];
            date_default_timezone_set('America/Mexico_City');
            $updated_at = date('Y-m-d H:i:s');
            $rol = $_POST['rol'];

            try {
                User::editar($id, $username, $password, $updated_at, $rol);
                header("Location:./?controlador=user&accion=editar&id=".$id."&success=Datos actualizados correctamente");
            } catch (Exception $e) {
                header("Location:./?controlador=user&accion=editar&id=".$id."&success=Error al actualizar los datos". $e->getMessage());
            }

            
        }
        $id=$_GET['id'];
        $user=User::buscar($id);
        include_once("backend/vistas/user/editar.php");
    }

    public function borrar() {
        if ($_GET) {
            try {
                $id = $_GET['id'];
                User::borrar($id);
                header("Location:./?controlador=user&accion=inicio&success=Usuario eliminado correctamente.");
            } catch (Exception $e) {
                header("Location:./?controlador=user&accion=inicio&error=Error al eliminar el usuario: " . $e->getMessage());
            }
            exit();
        }
    }

}
?>