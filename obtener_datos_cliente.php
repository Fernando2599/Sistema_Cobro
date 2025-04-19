<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("backend/modelos/clientes.php");
include_once("backend/controladores/controlador_periodos.php");

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

if (isset($_POST['idCliente'])) {
    $idCliente = $_POST['idCliente'];
    $controlador = new ControladorPeriodos();
    $datosCliente = $controlador->obtenerDatosCliente($idCliente);
    
    if ($datosCliente) {
        echo json_encode($datosCliente);
    } else {
        echo json_encode(['error' => 'No se encontraron datos para el cliente.']);
    }
} else {
    echo json_encode(['error' => 'Número de cliente no proporcionado.']);
}
?>
