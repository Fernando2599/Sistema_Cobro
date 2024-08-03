document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const errorMessage = urlParams.get('error');
    const successMessage = urlParams.get('success');

    if (errorMessage) {
        showAlert(errorMessage, 'error', function() {
            window.location.href = './?controlador=ventas&accion=informacion';
        });
    }

    if (successMessage) {
        showAlert(successMessage, 'exitoso', function() {
            window.location.href = './?controlador=ventas&accion=informacion';
        });
    }
});

document.getElementById('editar_reciboForm').addEventListener('submit', function(event) {
    
    //obtener la informacion del input
    const montoRecibo = document.getElementById('monto_recibo').value.trim(); 
    if (montoRecibo === "") {
        event.preventDefault(); // Evita el envío del formulario si no se cumple la condición
        showAlert('Debe agregar un monto diferente a vacío', 'error');
        return;
    }
});
