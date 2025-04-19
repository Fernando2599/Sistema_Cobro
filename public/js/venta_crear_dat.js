let recibos = [];
let clientes = [];
let infoclientes = [];
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
        // Si el valor es un solo carácter, agregamos al buffer
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
        const identificadorInicial = barcode.substring(0, 2);
        const numeroCliente = barcode.substring(2, 14).trim();
        const fecha = barcode.substring(14, 20);
        const costo = barcode.substring(20, 29);
        const identificadorFinal = barcode.substring(29);

        // Verifica y convierte el costo a formato decimal si es necesario
        const costoBarcode = parseFloat(costo);

        console.log("Identificador Inicial: " + identificadorInicial);
        console.log("Número de Cliente: " + numeroCliente);
        console.log("Fecha: " + fecha);
        console.log("Costo: " + costoBarcode);
        console.log("Identificador Final: " + identificadorFinal);

        //Recuperar el nombre del cliente
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
                document.getElementById('nombres').value = data.nombres;
                document.getElementById('ap_pat').value = data.ap_pat;
                document.getElementById('ap_mat').value = data.ap_mat;

                // Muestra un mensaje o notifica que los datos han sido cargados
                showAlert('Datos del código de barras cargados. Completa el formulario.', 'success');

                // Establece el valor del input y agrega el recibo
                document.getElementById('monto_recibo').value = costoBarcode;
                document.getElementById('no_cliente').value = numeroCliente;
                addRecibo();
            } else {
                showAlert('No se encontraron datos para el cliente.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al obtener los datos del cliente.', 'error');
        });

    } else {
        showAlert('El código de barras debe tener 30 dígitos', 'error');
    }
}

// Función para agregar un recibo
function addRecibo() {
    const montoReciboInput = document.getElementById('monto_recibo');
    const clienteInput = document.getElementById('no_cliente');
    const nombresInput = document.getElementById('nombres');
    const ApPatInput = document.getElementById('ap_pat');
    const ApMatInput = document.getElementById('ap_mat');

    // Obtenemos los valores (limpios)
    const montoRecibo = montoReciboInput.value.trim();
    const noCliente = clienteInput.value.trim();
    const nombres = nombresInput.value.trim();
    const apPat = ApPatInput.value.trim();
    const apMat = ApMatInput.value.trim();

   

    if (montoRecibo === "" || noCliente === "" || nombres === "" || apPat === "" || apMat === "") {
        showAlert('Verifique que los datos no esten vacíos', 'warning');
        return;
    }

    const montoReciboFloat = parseFloat(montoRecibo);
    const noClienteFloat = parseFloat(noCliente);

    recibos.push(montoReciboFloat);

    // Creamos objeto cliente
    const nuevoCliente = {
        numero: noClienteFloat,
        nombres: nombres,
        apPat: apPat,
        apMat: apMat
    };

    clientes.push(noClienteFloat);
    infoclientes.push(nuevoCliente);
    
    updateRecibosTable();
    montoReciboInput.value = ''; // Limpia el input después de agregar el recibo
    clienteInput.value = ''; // Limpia el input después de agregar el recibo
    nombresInput.value = '';
    ApPatInput.value = '';
    ApMatInput.value = '';
    updateTotalRecibos(); // Actualiza el total de recibos
}

// Función para eliminar un recibo
function removeRecibo(index) {
    recibos.splice(index, 1); // Elimina el recibo del array
    clientes.splice(index, 1); // Elimina el recibo del array
    
    updateRecibosTable();
    updateTotalRecibos(); // Actualiza el total después de eliminar
}
function updateRecibosTable(){
    const recibosTableBody = document.getElementById('recibos-table-body');
    recibosTableBody.innerHTML = ''; // Limpia la tabla

    recibos.forEach((monto, index) => {
        
        const newRow = document.createElement('tr');
        const cliente = clientes[index];
        const infocliente = infoclientes[index];
        newRow.innerHTML = `
            <td>${monto}</td>
            <td>${cliente}</td>
            <td>${infocliente.nombres} ${infocliente.apPat} ${infocliente.apMat}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRecibo(${index})"><i class="bi bi-trash"></i>
                </button>
            </td>`;
        recibosTableBody.appendChild(newRow);
    });
 
}
// Función para actualizar el total de recibos
function updateTotalRecibos() {
    const totalRecibos = recibos.reduce((acc, current) => acc + current, 0);
    document.getElementById('total_recibos').value = totalRecibos;
    calculateChange(); // Calcula el cambio
}

// Función para calcular el cambio
function calculateChange() {
    const totalRecibos = parseFloat(document.getElementById('total_recibos').value) || 0;
    const efectivoStr = document.getElementById('efectivo').value;

    if (efectivoStr !== "") {
        const efectivo = parseFloat(efectivoStr);
        const cambio = efectivo - totalRecibos;
        document.getElementById('cambio').value = cambio;
    } else {
        document.getElementById('cambio').value = '';
    }
}

// Maneja el envío del formulario
document.getElementById('submitBtn').addEventListener('click', function() {
    // Simula el evento 'submit' del formulario
    document.getElementById('reciboForm').dispatchEvent(new Event('submit'));
});

document.getElementById('reciboForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario si no se cumple la condición
    if (recibos.length === 0) {
        showAlert('Debe agregar al menos un recibo antes de registrar.', 'error');
        return;
    }
    showAlert('Los datos han sido registrados correctamente.', 'exitoso', function() {
        prepareData(); // Prepara los datos para enviarlos
        document.getElementById('reciboForm').submit(); // Envía el formulario
    });
});


// Prepara los datos para el envío
function prepareData() {
    document.getElementById('recibos_hidden').value = JSON.stringify(recibos); //array recibos
    document.getElementById('clientes_hidden').value = JSON.stringify(clientes); //array clientes
    document.getElementById('total_recibos_hidden').value = document.getElementById('total_recibos').value;
}

// Función para mostrar alertas


// Asocia el manejador del input al campo de código de barras
document.getElementById('barcodeInput').addEventListener('input', handleBarcodeInput);

// Prevenir el envío del formulario con Enter
document.getElementById('barcodeInput').addEventListener('keypress', function(event) {
    if (event.key === 'Enter' || event.code === 'Enter' || event.key === 'NumpadEnter') {
        event.preventDefault();
    }
});
