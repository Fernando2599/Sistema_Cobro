
<div class="container">
    <div class="row">
        <!-- Columna principal para "Recibos" y "Recibos Agregados" -->
        <div class="col-md-7">
            <!-- Bloque para "Recibos" -->
            <div class="card mb-3"> <!-- Usamos mb-2 para un pequeño espacio entre los dos bloques -->
                <div class="card-header">Recibos</div>
                <div class="card-body">
                    <form id="reciboForm" action="?controlador=ventas&accion=guardar" method="POST">
                        <div class="mb-3 d-flex align-items-center">
                            <label for="monto_recibo" class="form-label me-2">Monto Recibo:</label>
                            <input type="number" class="form-control me-2" id="monto_recibo" style="flex: 1;">
                            <button type="button" class="btn btn-secondary me-2" onclick="addRecibo()">Agregar Recibo</button>
                            <button type="submit" class="btn btn-success">Registrar Recibos</button>
                        </div>
                        <input type="hidden" name="recibos" id="recibos_hidden">
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
                </div>
            </div>
        </div>
    </div>
</div>

<script src="public/js/venta_crear_modificado.js"></script>