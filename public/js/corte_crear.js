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

    const efectivo = document.getElementById('monto_corte').value.trim();
    const talon = document.getElementById('talonarios').value.trim(); 

    if ((efectivo === "" || parseFloat(efectivo) <= 0) || (talon === "" || parseFloat(talon) <= 0)) {
        showAlert('Verifique que los campos no esten vacios.', 'warning');
        return;
    }

    document.getElementById('corteForm').submit();
});