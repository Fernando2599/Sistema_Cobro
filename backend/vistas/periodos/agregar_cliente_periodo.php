<div class="card">
    <div class="card-header">Datos del cliente</div>
    <div class="card-body">
                    
        <form id="datosForm" action="" method="post">
            <div class="mb-3">
                <div class="mb-2">
                    <label for="barcodeInput" class="form-label me-2">Escanear Código de Barras:</label>
                    <input type="text" class="form-control" id="barcodeInput" maxlength="30" oninput="handleBarcodeInput(event)" autofocus>

                </div>
                
                <label for="" class="form-label">Nombre(s):</label>

                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese el nombre(s)" maxlength="45" readonly required />

                <label for="" class="form-label">Apellido Paterno:</label>

                <input type="text" class="form-control" name="ap_pat" id="ap_pat" placeholder="Ingrese el apellido parterno" maxlength="40" readonly required />
                <label for="" class="form-label">Apellido Materno:</label>

                <input type="text" class="form-control" name="ap_mat" id="ap_mat" placeholder="Ingrese el apellido materno" maxlength="40" readonly />

                <label for="" class="form-label">No. de servicio:</label>

                <input type="text" class="form-control" name="no_servicio" id="no_servicio" placeholder="Ingrese el no. de servicio" maxlength="30" readonly />

                <label for="" class="form-label">Monto:</label>

                <input type="text" class="form-control" name="monto_pago" id="monto_pago" placeholder="Ingrese el no. de servicio" maxlength="6" readonly />

            </div>  
            <input name="" id="" class="btn btn-success" type="submit" value="Agregar Cliente"/>
            <a href="?controlador=clientes&accion=inicio" class="btn btn-danger">Cancelar</a>
                
        
            
        </form>
    </div>
</div>

<script src="public/js/actualizar_m.js"></script>