<?php
include_once("backend/modelos/iniciar.php");
include_once("conexion.php");

BD::crearInstancia();

class ControladorIniciar{
    public function iniciar() {

        if ($_POST) {
            // Obtener datos del formulario
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user=Iniciar::buscar($username, $password);

            if ($user) {
                // Credenciales correctas
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['rol_id'] = $user['rol_id'];
                header("Location:./?controlador=iniciar&accion=login&success=Le damos la bienvenida ".$username);
                
                exit();
            } else {
                // Credenciales incorrectas
                header("Location:./?controlador=iniciar&accion=login&error=Usuario o contraseña incorrectos");
                exit();
            }
        }
        
    }

    public function login() {
        include_once("frontend/vistas/iniciar/login.php");
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: ./?controlador=iniciar&accion=login");
        exit();
    }
}