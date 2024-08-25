
<div class="container d-flex justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Periodos</div>
            <div class="card-body">
                <form id="corteForm" action="?controlador=periodos&accion=crear" method="POST">
                    <div class="mb-3">
                        <label for="limite" class="form-label">Fecha limite de corte:</label>
                        <input type="date" class="form-control" id="fecha_limite" name="fecha_limite" required>

                        <label for="inicio" class="form-label">Fecha inicial (periodo facturado):</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>

                        <label for="fin" class="form-label">Fecha final (periodo facturado):</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>

                        
                    </div>

                    <input name="" id="" class="btn btn-success" type="submit" value="Registrar periodo"/>

                    <a href="?controlador=periodos&accion=crear" class="btn btn-danger">Cancelar</a>

                </form>
            </div>
        </div>
    </div>
</div>

