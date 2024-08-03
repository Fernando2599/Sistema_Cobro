document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const errorMessage = urlParams.get('error');
    const successMessage = urlParams.get('success');

    if (errorMessage) {
        showAlert(errorMessage, 'error', function() {
            window.location.href = './?controlador=iniciar&accion=login';
        });
    }

    if (successMessage) {
        showAlert(successMessage, 'exitoso', function() {
            window.location.href = './?controlador=paginas&accion=inicio';
        });
    }
});