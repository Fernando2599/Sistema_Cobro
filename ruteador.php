<?php

$backend_controlador = "backend/controladores/controlador_" . $controlador . ".php";
$frontend_controlador = "frontend/controladores/controlador_" . $controlador . ".php";

// Verifica si el archivo del backend existe
if (file_exists($backend_controlador)) {
    include_once($backend_controlador);
} else if (file_exists($frontend_controlador)) {  // Verifica si el archivo del frontend existe
    include_once($frontend_controlador);
} else {
    // Si ninguno de los archivos existe, muestra un mensaje de error y detén el script
    die("Error: No se encontró el controlador '$controlador' ni en el backend ni en el frontend.");
}

// Asegúrate de que la clase del controlador exista antes de intentar instanciarla
$objControlador = "Controlador" . ucfirst($controlador);
if (class_exists($objControlador)) {
    $controlador = new $objControlador();  // Crea una instancia del controlador
    if (method_exists($controlador, $accion)) {
        $controlador->$accion();  // Llama a la acción especificada
    } else {
        die("Error: El método '$accion' no existe en el controlador '$objControlador'.");
    }
} else {
    die("Error: La clase del controlador '$objControlador' no existe.");
}

?>
