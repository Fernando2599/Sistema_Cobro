let barcodeBuffer = '';

// Maneja el input del código de barras
function handleBarcodeInput(event) {
    const key = event.key || event.code;

    if (key === 'Enter' || key === 'NumpadEnter') {
        // Prevenir el comportamiento por defecto de la tecla Enter
        event.preventDefault();
        return;
    }

    const value = event.target.value;

    if (value.length === 1) {
        // Si el valor es un solo carácter, lo agregamos al buffer
        barcodeBuffer += value;

        if (barcodeBuffer.length === 30) {
            processBarcode(barcodeBuffer); // Procesa el código de barras completo
            barcodeBuffer = ''; // Limpia el buffer
        }
    } else if (value.length > 1) {
        // Si el valor es mayor a 1 carácter, considera que puede ser una entrada completa
        barcodeBuffer += value;

        if (barcodeBuffer.length >= 30) {
            const barcode = barcodeBuffer.slice(0, 30); // Extrae los primeros 30 dígitos
            barcodeBuffer = barcodeBuffer.slice(30); // Mantiene el resto para futuras entradas

            processBarcode(barcode); // Procesa el código de barras completo
        }
    }

    // Resetea el campo de entrada
    event.target.value = '';
}

// Procesa el código de barras
function processBarcode(barcode) {
    barcode = barcode.trim();

    if (barcode.length === 30) {
        const numeroCliente = barcode.substring(2, 14);

        // Realiza una solicitud AJAX para obtener los datos del cliente
        fetch('obtener_datos_cliente.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `numeroCliente=${numeroCliente}`
        })
        .then(response => response.json()) // Cambia de .text() a .json() para parsear directamente
        .then(data => {
            console.log('Respuesta del servidor:', data); // Log de la respuesta completa
            if (data && !data.error) {
                document.getElementById('nombre').value = data.nombres;
                document.getElementById('ap_pat').value = data.ap_pat;
                document.getElementById('ap_mat').value = data.ap_mat;
            } else {
                showAlert('No se encontraron datos para el cliente.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al obtener los datos del cliente.', 'error');
        });
        

        // Establece el valor de los inputs, pero no envía el formulario
        document.getElementById('no_servicio').value = numeroCliente;
        document.getElementById('monto_pago').value = parseFloat(barcode.substring(20, 29));

        // Muestra un mensaje o notifica que los datos han sido cargados
        showAlert('Datos del código de barras cargados. Completa el formulario.', 'success');
    } else {
        showAlert('El código de barras debe tener 30 dígitos', 'error');
    }
}

// Evento para manejar el submit del formulario
document.getElementById('datosForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario si no se cumplen condiciones
    // Validaciones adicionales, si es necesario
    const noServicio = document.getElementById('no_servicio').value;
    const montoPago = document.getElementById('monto_pago').value;
    const nombre = document.getElementById('nombre').value;

    // Aquí puedes agregar otras validaciones antes de enviar
    if (noServicio && montoPago && nombre) {
        this.submit(); // Envía el formulario solo si los campos están completos
    } else {
        showAlert('Por favor, completa todos los campos antes de enviar.', 'error');
    }
});

// Asocia el input del código de barras con la función handleBarcodeInput
document.getElementById('barcodeInput').addEventListener('input', handleBarcodeInput);

// Prevenir el envío del formulario si se detecta Enter en el campo de código de barras
document.getElementById('barcodeInput').addEventListener('keypress', function(event) {
    if (event.key === 'Enter' || event.code === 'NumpadEnter') {
        event.preventDefault();
    }
});

// Función para mostrar alertas
function showAlert(message, type) {
    // Aquí puedes implementar la lógica para mostrar alertas en tu aplicación
    console.log(`${type.toUpperCase()}: ${message}`);
}
