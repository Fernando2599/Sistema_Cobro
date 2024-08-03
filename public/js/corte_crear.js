document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const errorMessage = urlParams.get('error');
    const successMessage = urlParams.get('exitoso');

    if (errorMessage) {
        showAlert(errorMessage, 'error');
    }

    if (successMessage) {
        showAlert(successMessage, 'exitoso', function() {
            window.location.href = './?controlador=ventas&accion=informacion';
        });
    }
});

document.getElementById('corteForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario si no se cumple la condición

    const montoCorte = document.getElementById('monto_corte').value.trim(); 

    if (montoCorte === "" || parseFloat(montoCorte) <= 0) {
        showAlert('Debe agregar un monto mayor a 0.', 'warning');
        return;
    }

    document.getElementById('corteForm').submit();
});