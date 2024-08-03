document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const errorMessage = urlParams.get('error');
    const successMessage = urlParams.get('exitoso');

    if (errorMessage) {
        showAlert(errorMessage, 'error');
    }

    if (successMessage) {
        showAlert(successMessage, 'exitoso', function() {
            window.location.href = './?controlador=depositos&accion=reportes';
        });
    }
});

document.getElementById('depositoForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario si no se cumple la condición

    const montoEfectivo = document.getElementById('monto_efectivo').value.trim(); 

    if (montoEfectivo === "" || parseFloat(montoEfectivo) <= 0) {
        showAlert('Debe agregar un monto mayor a 0.', 'warning');
        return;
    }

    document.getElementById('depositoForm').submit();
});