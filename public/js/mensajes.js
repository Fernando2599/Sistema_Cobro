function showAlert(message, type, callback) {
    const alertContainer = document.getElementById('alert-container');
    alertContainer.className = `alert-container alert-${type}`;

    const alertMessage = document.createElement('div');
    alertMessage.className = 'alert-message';
    alertMessage.innerText = message;

    const alertButton = document.createElement('button');
    alertButton.className = `alert-button alert-button-${type}`;
    alertButton.innerText = 'Aceptar';
    alertButton.onclick = function() {
        alertContainer.style.display = 'none';
        alertContainer.innerHTML = ''; // Clear content
        if (callback) {
            callback();
        }
    };

    alertContainer.appendChild(alertMessage);
    alertContainer.appendChild(alertButton);

    alertContainer.style.display = 'block';
}
