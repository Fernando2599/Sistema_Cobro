<div class="container">
    <div class="row">
        <!-- Columna principal para "Recibos" y "Recibos Agregados" -->
        <div class="col-md-7">
            <!-- Bloque para "Recibos" -->
            <div class="card mb-3">
                <div class="card-header">Recibos</div>
                <div class="card-body">
                    <form id="reciboForm" action="?controlador=ventas&accion=guardar" method="POST">
                        <!-- Campo para escanear el código de barras -->
                        <div class="mb-2">
                            <label for="barcodeInput" class="form-label me-2">Escanear Código de Barras:</label>
                            <input type="text" class="form-control" id="barcodeInput" maxlength="30" oninput="handleBarcodeInput(event)" autofocus>

                        </div>
    
                        <!-- Campo para el monto del recibo (rellenado automáticamente) -->
                        <input type="hidden" id="monto_recibo">
                        <input type="hidden" id="no_cliente">
                        <!-- input para el array recibos -->
                        <input type="hidden" name="recibos" id="recibos_hidden"> 
                        <!-- input para el array clientes -->
                        <input type="hidden" name="clientes" id="clientes_hidden"> 
                        <input type="hidden" name="total_recibos" id="total_recibos_hidden">
                    </form>
                </div>
            </div>

            <!-- Bloque para "Recibos Agregados" -->
            <div class="card">
                <div class="card-header">Recibos Agregados</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Monto Recibo</th>
                                <th scope="col">No. de Servicio</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="recibos-table-body">
                            <!-- Aquí se agregarán dinámicamente las filas de recibos -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Columna secundaria para "Información de la venta" -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Información de la venta</div>
                <div class="card-body">
                    <div class="mb-6">
                        <label for="total_recibos" class="form-label">Precio total:</label>
                        <input type="number" class="form-control" id="total_recibos" name="total_recibos" readonly>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div style="flex-grow: 1;">
                            <label for="efectivo" class="form-label">Efectivo:</label>
                            <input type="number" class="form-control" id="efectivo" oninput="calculateChange()">
                        </div>
                        <div style="flex-grow: 1; margin-left: 10px;">
                            <label for="cambio" class="form-label">Cambio:</label>
                            <input type="number" class="form-control" id="cambio" readonly>
                        </div>
                    </div>

                    <!-- Botón fuera del formulario -->
                    <div class="d-flex justify-content mb-2">
                        <button type="button" class="btn btn-success" id="submitBtn">Registrar Recibos</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="public/js/venta_crear_f.js"></script>
