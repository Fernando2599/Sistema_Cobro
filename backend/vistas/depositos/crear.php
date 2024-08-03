<div class="container">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Depositos</div>
                <div class="card-body">
                    <form id="depositosForm" action="?controlador=depositos&accion=crear" method="POST">
                        <div class="mb-3">
                            <div class="col-md-6">
                                <label for="fecha_depositar" class="form-label">Seleccione la fecha de la venta:</label>
                                
                                <input type="date" class="form-control" id="fecha_depositar">     
                            </div>                       
                        </div>
                        <!--hidden para poder mandar las fechas que estan en la tabla -->
                        <input type="hidden" name="fechas_depositos" id="fechas_hidden">
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary me-3" onclick="addFecha()">Agregar</button>
                                <button type="submit" class="btn btn-success me-3">Generar reporte</button>
                                <a href="?controlador=depositos&accion=crear" class="btn btn-danger">Limpiar</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Fechas Agregados</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Fechas a depositar</th>
                            </tr>
                        </thead>
                            <tbody id="fechas-table-body">
                                <!-- Aquí se agregarán dinámicamente las filas de recibos -->
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="public/js/depositados.js"></script>

