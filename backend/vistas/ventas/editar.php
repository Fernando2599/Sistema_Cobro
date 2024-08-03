<div class="container d-flex justify-content-center">
<div id="alert-container" class="alert-container"></div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Recibos</div>
                <div class="card-body">

                    

                    <form id="editar_reciboForm" action="?controlador=ventas&accion=editar&id=<?php echo $_GET['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="monto_recibo" class="form-label">Monto Recibo:</label>
                            <input type="number" class="form-control" id="monto_recibo" name="monto_recibo" value="<?php echo $recibo->monto_recibo; ?>">
                        </div>
                        
                        <input name="" id="" class="btn btn-success" type="submit" value="Guardar"/>
                            
                        <a href="?controlador=ventas&accion=informacion" class="btn btn-danger">Cancelar</a>
                        
                        
                    </form>
                </div>
            </div>
        </div>
</div>
<script src="public/js/venta_editar.js"></script>

