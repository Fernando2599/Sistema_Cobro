<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    include_once("backend/modelos/periodos.php");
    include_once("backend/modelos/clientes_has_periodos.php");
    include_once("backend/modelos/clientes.php");
    include_once("backend/controladores/controlador_clientes.php");

    if (!isset($_POST['idCliente'])) {
        echo json_encode(['error' => 'Número de cliente no proporcionado.']);
        exit;
    }

    $idCliente = $_POST['idCliente'];
    $idUltimoPeriodo = Periodos::obtenerUltimoPeriodo();

    if (!$idUltimoPeriodo) {
        echo json_encode(['error' => 'No se pudo obtener el último periodo.']);
        exit;
    }

    $controlador = new ControladorClientes();
    $datosCliente = $controlador->obtenerDatosCliente($idCliente, $idUltimoPeriodo);

    echo json_encode($datosCliente ?: ['error' => 'No se encontraron datos para el cliente.']);
    exit;

} catch (Throwable $e) {
    echo json_encode(['error' => 'Excepción: ' . $e->getMessage()]);
    exit;
}
?>
