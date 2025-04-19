document.addEventListener('DOMContentLoaded', function() {
    const idCliente = document.getElementById('idCliente').value;
    if (idCliente) {
        processClient();
    }
});
function processClient() {
    const idCliente = document.getElementById('idCliente').value;

    // Verifica que idCliente no esté vacío
    if (!idCliente) {
        showAlert('Por favor, ingresa un número de cliente.', 'error');
        return;
    }

    // Realiza una solicitud AJAX para obtener los datos del cliente
    fetch('obtener_estado_cliente.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `idCliente=${idCliente}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data); // Log de la respuesta completa
        if (data && !data.error) {
            document.getElementById('nombre').value = data[0].nombres;
            document.getElementById('ap_pat').value = data[0].ap_pat;
            document.getElementById('ap_mat').value = data[0].ap_mat;
            document.getElementById('no_servicio').value = data[0].numero_servicio;
            document.getElementById('estado').value = data[0].estado;
            document.getElementById('idClientehasPeriodos').value = data[0].id;
            document.getElementById('monto_pago').value = data[0].monto_pago;
            
        } else {
            showAlert('No se encontraron datos para el cliente.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error al obtener los datos del cliente.', 'error');
    });
}

// Evento para manejar el submit del formulario
document.getElementById('datosForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const idCliente = document.getElementById('idCliente').value;
    const idClientehasPeriodos = document.getElementById('idClientehasPeriodos').value;
    const servicio = document.getElementById('no_servicio').value;

    if (idCliente && idClientehasPeriodos && servicio) {
        this.submit();
    } else {
        showAlert('Por favor, completa todos los campos antes de enviar.', 'error');
    }
});

// Función para mostrar alertas
function showAlert(message, type) {
    console.log(`${type.toUpperCase()}: ${message}`);
}
