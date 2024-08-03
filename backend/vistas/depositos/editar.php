<div class="container d-flex justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Reportes</div>
            <div class="card-body">
                <form id="depositoForm" action="?controlador=depositos&accion=editarReporte&id=<?php echo $_GET['id']; ?>" method="POST">
                    <div class="mb-3">
                        <label for="monto_efectivo" class="form-label">Monto del Efectivo:</label>
                        <input type="number" class="form-control" id="monto_efectivo" name="monto_efectivo" value="<?php echo $reporte->conteo_efectivo; ?>">
                    </div>

                    <input name="" id="" class="btn btn-success" type="submit" value="Registrar"/>

                    <a href="?controlador=depositos&accion=reportes" class="btn btn-danger">Cancelar</a>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="public/js/deposito_editar.js"></script>