
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-success" href="?controlador=user&accion=crear" 
            role="button">Agregar Usuario
        </a>
    </div>
    <div class="card-body">
        <table
            class="table table-bordered"
        >
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Creado el</th>
                    <th scope="col">Actualizado el</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Accion</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($usuarios as $user) {?>
                <tr>
                    <td><?php echo $user->id;?></td>
                    <td><?php echo $user->username;?></td>
                    <td><?php echo $user->created_at;?></td>
                    <td><?php echo $user->updated_at;?></td>
                    <td><?php echo $user->rol;?></td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Button group name">
                            <a href="?controlador=user&accion=editar&id=<?php echo $user->id;?>" class="btn btn-info"> Editar </a>
                            <a href="?controlador=user&accion=borrar&id=<?php echo $user->id;?>" class="btn btn-danger"> Borrar </a>
                        </div>
                        
                    </td>
            <?php } ?>
            </tbody>
        </table>
        
    </div>
</div>
<script src="public/js/user.js"></script>