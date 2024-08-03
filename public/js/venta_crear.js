let recibos = [];

function addRecibo() {
    const montoRecibo = document.getElementById('monto_recibo').value.trim();
    
    if (montoRecibo === "") {
        showAlert('El monto del recibo no puede estar vacío', 'warning');
        return; // Evita agregar recibos vacíos
    }
    
    recibos.push(parseFloat(montoRecibo));
    
    const recibosTableBody = document.getElementById('recibos-table-body');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `<td>${montoRecibo}</td>`;
    recibosTableBody.appendChild(newRow);
    
    document.getElementById('monto_recibo').value = '';
    updateTotalRecibos();
}

function updateTotalRecibos() {
    const totalRecibos = recibos.reduce((acc, current) => acc + current, 0);
    document.getElementById('total_recibos').value = totalRecibos.toFixed(2);
    calculateChange();
}

function calculateChange() {
    const totalRecibos = parseFloat(document.getElementById('total_recibos').value) || 0;
    const efectivoStr = document.getElementById('efectivo').value;

    if (efectivoStr !== "") {
        const efectivo = parseFloat(efectivoStr);
        const cambio = efectivo - totalRecibos;
        document.getElementById('cambio').value = cambio.toFixed(2);
    } else {
        document.getElementById('cambio').value = '';
    }
}

document.getElementById('reciboForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario si no se cumple la condición
    if (recibos.length === 0) {
        showAlert('Debe agregar al menos un recibo antes de registrar.', 'error');
        return;
    }
    showAlert('Los datos han sido registrados correctamente.', 'exitoso', function() {
        prepareData(); // Prepara los datos para enviarlos
        // Envía el formulario
        document.getElementById('reciboForm').submit();
    });
});

function prepareData() {
    
    document.getElementById('recibos_hidden').value = JSON.stringify(recibos);
    document.getElementById('total_recibos_hidden').value = document.getElementById('total_recibos').value;
    
}
//editar recibos

