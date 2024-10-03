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
        const numeroCliente = barcode.substring(2, 14);
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

        // Establece el valor del input y agrega el recibo
        //document.getElementById('monto_recibo').value = costoBarcode;
        document.getElementById('no_servicio').value = numeroCliente;
        //addRecibo();
    } else {
        showAlert('El código de barras debe tener 30 dígitos', 'error');
    }
}