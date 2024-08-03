document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const errorMessage = urlParams.get('error');
    const successMessage = urlParams.get('success');

    if (errorMessage) {
        showAlert(errorMessage, 'error');
    }

    if (successMessage) {
        showAlert(successMessage, 'exitoso', function() {
            window.location.href = './?controlador=depositos&accion=reportes';
        });
    }
});
let fechas = [];

function addFecha() {
    const fechaDepositar = document.getElementById('fecha_depositar').value.trim();
    //se obtiene el valor del input
    
    if (fechaDepositar === "") {
        showAlert('La fecha no puede estar vacía', 'warning');
        return; // Evita agregar fechas vacías
    }
    
    // Evita duplicados
    if (fechas.includes(fechaDepositar)) {
        showAlert('La fecha ya ha sido agregada.', 'warning');
        return;
    }

    // se agrega el valor a la tabla
    fechas.push(fechaDepositar);
    
    const recibosTableBody = document.getElementById('fechas-table-body');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `<td>${fechaDepositar}</td>`;
    recibosTableBody.appendChild(newRow);
    
    document.getElementById('fecha_depositar').value = '';
}

document.getElementById('depositosForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío del formulario si no se cumple la condición

    //se verifica el tamaño de la matriz declarada al inicio
    if (fechas.length === 0) {
        showAlert('Debe agregar al menos una fecha antes de registrar.', 'error');
        return;
    }

    // Preparar los datos antes de enviar
    prepareData();
    // Enviar el formulario después de preparar los datos
    this.submit();
});

function prepareData() {
    // se agregan los valores del arreglo al input oculto
    document.getElementById('fechas_hidden').value = JSON.stringify(fechas);
}
