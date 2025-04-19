<div class="card">
    <div class="card-header">Datos del cliente</div>
    <div class="card-body">
                    
        <form id="datosForm" action="" method="post">
            <div class="mb-3">
                <div class="mb-2">
                    
                    <input type="hidden" class="form-control" id="idCliente" name="idCliente" value="<?php echo $_GET['id']; ?>">
                    <input type="hidden" class="form-control" id="idClientehasPeriodos" name="idClientehasPeriodos" value="<?php echo $_GET['id']; ?>">
                    <input type="hidden" class="form-control" id="idUltimoPeriodo" name="idUltimoPeriodo" value="<?php echo $idUltimoPeriodo; ?>">

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

                <input type="text" class="form-control" name="monto_pago" id="monto_pago" placeholder="Ingrese el monto"/>

                <label for="" class="form-label">Estado:</label>

                <input type="text" class="form-control" name="estado" id="estado" placeholder="Ingrese el no. de servicio"/>

            </div>  
            <input name="" id="" class="btn btn-success" type="submit" value="Cancelar Venta"/>
            <a href="?controlador=clientes&accion=inicio" class="btn btn-danger">Cancelar</a>
                
        
            
        </form>
    </div>
</div>

<script src="public/js/actualizar_datos_client.js"></script>