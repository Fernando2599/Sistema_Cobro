<?php
include_once("backend/modelos/user.php");
include_once("conexion.php");

BD::crearInstancia();

class ControladorClientes{

    public function inicio() {
        $usuarios=(User::consultar());
        include_once("backend/vistas/user/inicio.php");
    }

    public function crear() {

        if ($_POST) {
            $username = $_POST['username'];
            $password = $_POST['pass'];
            date_default_timezone_set('America/Mexico_City');
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            $rol = $_POST['rol'];

            try {
                User::crear($username, $password, $created_at, $updated_at, $rol);
                header("Location: ./?controlador=user&accion=crear&success=El usuario ha sido registrado correctamente.");
            } catch (Exception $e) {
                // Redireccionar con mensaje de error
                header("Location: ./?controlador=user&accion=crear&error=Ocurrió un error al registrar el usuario: " . $e->getMessage());
            }
            exit();
        }
        
        include_once("backend/vistas/user/crear.php");
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