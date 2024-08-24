<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-success" href="?controlador=clientes&accion=crear" 
            role="button">Agregar Cliente
        </a>
    </div>
    <div class="card-body">

        <!-- Formulario de filtrado -->
        <form method="GET" action="">
            <input type="hidden" name="controlador" value="user">
            <input type="hidden" name="accion" value="index">

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th >Id</th>
                        <th >No. de Servicio</th>
                        <th >Nombres</th>
                        <th >Apellido Paterno</th>
                        <th >Apellido Materno</th>
                        <th >Direccion</th>
                        <th >Accion</th>
                    </tr>
                    <tr class="filters">
                        <td></td>
                        <td></td>
                        <td><input type="text" class="form-control" name="filter_nombres" placeholder="Nombres"></td>
                        <td><input type="text" class="form-control" name="filter_apellido_paterno" placeholder="Apellido Paterno"></td>
                        <td><input type="text" class="form-control" name="filter_apellido_materno" placeholder="Apellido Materno"></td>
                        <td></td>
                        <td>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </td>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($clientes as $cliente) { 
                    //var_dump($cliente); ?>
                    <tr>
                        <td><?php echo $cliente->id;?></td>
                        <td><?php echo $cliente->numero_servicio;?></td>                        
                        <td><?php echo $cliente->nombres;?></td>
                        <td><?php echo $cliente->ap_pat;?></td>
                        <td><?php echo $cliente->ap_mat;?></td>
                        <td><?php echo $cliente->direccion;?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Button group name">
                                <a href="?controlador=user&accion=borrar&id=<?php echo $cliente->id; ?>" class="btn btn-primary" onclick="return confirm('¿Seguro que quieres eliminar este registro?');">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="?controlador=user&accion=editar&id=<?php echo $cliente->id; ?>" class="btn btn-info">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="?controlador=user&accion=borrar&id=<?php echo $cliente->id; ?>" class="btn btn-danger" onclick="return confirm('¿Seguro que quieres eliminar este registro?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                                
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
        
    </div>
</div>
<script src="public/js/user.js"></script>
