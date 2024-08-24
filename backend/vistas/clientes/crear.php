<div class="card">
    <div class="card-header">Datos del cliente</div>
    <div class="card-body">
                    
        <form action="" method="post">
            <div class="mb-3">
                <label for="" class="form-label">No. de servicio:</label>

                <input type="text" class="form-control" name="no_servicio" aria-describedby="helpId" placeholder="Ingrese el no. de servicio" maxlength="30" required />
                
                <label for="" class="form-label">Nombre(s):</label>

                <input type="text" class="form-control" name="nombre" aria-describedby="helpId" placeholder="Ingrese el nombre(s)" maxlength="45" required />

                <label for="" class="form-label">Apellido Paterno:</label>

                <input type="text" class="form-control" name="ap_pat" aria-describedby="helpId" placeholder="Ingrese el apellido parterno" maxlength="40" required />
                <label for="" class="form-label">Apellido Materno:</label>

                <input type="text" class="form-control" name="ap_mat" aria-describedby="helpId" placeholder="Ingrese el apellido materno" maxlength="40" required />

            </div>  
            <input name="" id="" class="btn btn-success" type="submit" value="Agregar Cliente"/>
            <a href="?controlador=clientes&accion=inicio" class="btn btn-danger">Cancelar</a>
                
        
            
        </form>
    </div>
</div>
<script src="public/js/clientes.js"></script>