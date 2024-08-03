
<div class="container d-flex justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Cortes</div>
            <div class="card-body">
                <form id="corteForm" action="?controlador=cortes&accion=crear" method="POST">
                    <div class="mb-3">
                        <label for="monto_recibo" class="form-label">Monto del Corte:</label>
                        <input type="number" class="form-control" id="monto_corte" name="monto_corte">
                    </div>

                    <input name="" id="" class="btn btn-success" type="submit" value="Registrar corte"/>

                    <a href="?controlador=ventas&accion=informacion" class="btn btn-danger">Cancelar</a>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="public/js/corte_crear.js"></script>
