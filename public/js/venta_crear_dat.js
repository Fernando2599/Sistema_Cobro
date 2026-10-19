// let recibos = [];
// let clientes = [];
// let infoclientes = [];

// Usarías uno solo:
let listaRecibos = [];
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
// Procesa el código de barras
function processBarcode(barcode) {
    barcode = barcode.trim();

    if (barcode.length === 30) {
        const identificadorInicial = barcode.substring(0, 2);
        const numeroCliente = barcode.substring(2, 14).trim();
        const fecha = barcode.substring(14, 20);
        const costo = barcode.substring(20, 29);
        const identificadorFinal = barcode.substring(29);

        const costoBarcode = parseFloat(costo);

        // Recuperar el nombre y estado del cliente
        fetch('obtener_datos_cliente.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `numeroCliente=${numeroCliente}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data);
            if (data && !data.error) {
                
                // --- VALIDACIÓN DE ESTADO PAGADO ---
                if (data.estado && data.estado.toLowerCase() === 'pagado') {
                    showAlert('El recibo de este cliente ya se encuentra en estado "Pagado". No se puede volver a cobrar.', 'warning');
                    return; // Detiene la ejecución y no lo agrega
                }

                document.getElementById('nombres').value = data.nombres;
                document.getElementById('ap_pat').value = data.ap_pat;
                document.getElementById('ap_mat').value = data.ap_mat;

                document.getElementById('monto_recibo').value = costoBarcode;
                document.getElementById('no_cliente').value = numeroCliente;
                
                // Agrega el recibo (incluyendo la validación previa de duplicados en lista)
                addRecibo(barcode);
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
// Función para agregar un recibo (ahora recibe el código de barras opcionalmente)
function addRecibo(barcodeFull = '') {
    const montoReciboInput = document.getElementById('monto_recibo');
    const clienteInput = document.getElementById('no_cliente');
    const nombresInput = document.getElementById('nombres');
    const ApPatInput = document.getElementById('ap_pat');
    const ApMatInput = document.getElementById('ap_mat');

    const montoRecibo = montoReciboInput.value.trim();
    const noCliente = clienteInput.value.trim();
    const nombres = nombresInput.value.trim();
    const apPat = ApPatInput.value.trim();
    const apMat = ApMatInput.value.trim();

    if (montoRecibo === "" || noCliente === "" || nombres === "" || apPat === "") {
        showAlert('Verifique que los datos no esten vacíos', 'warning');
        return;
    }

    // --- VALIDACIÓN DE DUPLICADOS ---
    // Si tenemos el código de barras completo, verificamos si ya existe en la lista
    if (barcodeFull !== "") {
        const yaExiste = listaRecibos.some(item => item.barcode === barcodeFull);
        if (yaExiste) {
            showAlert('Este recibo ya ha sido agregado a la lista de cobro.', 'warning');
            // Limpiamos los inputs y salimos
            montoReciboInput.value = '';
            clienteInput.value = '';
            nombresInput.value = '';
            ApPatInput.value = '';
            ApMatInput.value = '';
            return;
        }
    }

    // Guardamos todo en un solo objeto dentro del array (incluyendo el barcode para futuros chequeos)
    listaRecibos.push({
        barcode: barcodeFull,
        monto: parseFloat(montoRecibo),
        numero: parseFloat(noCliente),
        nombres: nombres,
        apPat: apPat,
        apMat: apMat
    });

    updateRecibosTable();
    montoReciboInput.value = '';
    clienteInput.value = '';
    nombresInput.value = '';
    ApPatInput.value = '';
    ApMatInput.value = '';
    updateTotalRecibos();
}

// Función para eliminar un recibo
function removeRecibo(index) {
    listaRecibos.splice(index, 1); 
    updateRecibosTable();
    updateTotalRecibos();
}

function updateRecibosTable(){
    const recibosTableBody = document.getElementById('recibos-table-body');
    recibosTableBody.innerHTML = '';

    listaRecibos.forEach((item, index) => {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${item.monto}</td>
            <td>${item.numero}</td>
            <td>${item.nombres} ${item.apPat} ${item.apMat}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRecibo(${index})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        recibosTableBody.appendChild(newRow);
    });
}

// Función para actualizar el total de recibos
function updateTotalRecibos() {
    const totalRecibos = listaRecibos.reduce((acc, current) => acc + current.monto, 0);
    document.getElementById('total_recibos').value = totalRecibos;
    calculateChange();
}

function prepareData() {
    const recibosArray = listaRecibos.map(item => item.monto);
    const clientesArray = listaRecibos.map(item => item.numero);

    document.getElementById('recibos_hidden').value = JSON.stringify(recibosArray);
    document.getElementById('clientes_hidden').value = JSON.stringify(clientesArray);
    document.getElementById('total_recibos_hidden').value = document.getElementById('total_recibos').value;
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
    document.getElementById('reciboForm').dispatchEvent(new Event('submit'));
});

document.getElementById('reciboForm').addEventListener('submit', function(event) {
    event.preventDefault(); 
    // Se corrigió 'recibos.length' por 'listaRecibos.length' que es el array correcto en uso
    if (listaRecibos.length === 0) {
        showAlert('Debe agregar al menos un recibo antes de registrar.', 'error');
        return;
    }
    showAlert('Los datos han sido registrados correctamente.', 'exitoso', function() {
        prepareData(); 
        document.getElementById('reciboForm').submit(); 
    });
});

// Asocia el manejador del input al campo de código de barras
document.getElementById('barcodeInput').addEventListener('input', handleBarcodeInput);

// Prevenir el envío del formulario con Enter
document.getElementById('barcodeInput').addEventListener('keypress', function(event) {
    if (event.key === 'Enter' || event.code === 'Enter' || event.key === 'NumpadEnter') {
        event.preventDefault();
    }
});