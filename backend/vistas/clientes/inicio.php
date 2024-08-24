<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-success" href="?controlador=clientes&accion=crear" 
            role="button">Agregar Cliente
        </a>
    </div>
    <div class="card-body">

        <!-- Formulario de filtrado -->
        <form method="GET" action="">
            <input type="hidden" name="controlador" value="clientes">
            <input type="hidden" name="accion" value="inicio">

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>No. de Servicio</th>
                        <th>Nombres</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Direccion</th>
                        <th>Accion</th>
                    </tr>
                    <tr class="filters">
                        <td></td>
                        <td></td>
                        <td><input type="text" class="form-control" name="filter_nombres" value="<?php echo htmlspecialchars($filtro_nombres); ?>" placeholder="Nombres"></td>
                        <td><input type="text" class="form-control" name="filter_ap_pat" value="<?php echo htmlspecialchars($filtro_ap_pat); ?>" placeholder="Apellido Paterno"></td>
                        <td><input type="text" class="form-control" name="filter_ap_mat" value="<?php echo htmlspecialchars($filtro_ap_mat); ?>" placeholder="Apellido Materno"></td>
                        <td></td>
                        <td>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </td>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($clientes as $cliente) { ?>
                    <tr>
                        <td><?php echo $cliente->id; ?></td>
                        <td><?php echo $cliente->numero_servicio; ?></td>                        
                        <td><?php echo $cliente->nombres; ?></td>
                        <td><?php echo $cliente->ap_pat; ?></td>
                        <td><?php echo $cliente->ap_mat; ?></td>
                        <td><?php echo $cliente->direccion; ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Button group name">
                                <a href="?controlador=clientes&accion=ver&id=<?php echo $cliente->id; ?>" class="btn btn-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="?controlador=clientes&accion=editar&id=<?php echo $cliente->id; ?>" class="btn btn-info">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="?controlador=clientes&accion=borrar&id=<?php echo $cliente->id; ?>" class="btn btn-danger" onclick="return confirm('¿Seguro que quieres eliminar este registro?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>

        <!-- Controles de paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($pagina_actual > 1) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?controlador=clientes&accion=inicio&filter_nombres=<?php echo htmlspecialchars($filtro_nombres); ?>&filter_ap_pat=<?php echo htmlspecialchars($filtro_ap_pat); ?>&filter_ap_mat=<?php echo htmlspecialchars($filtro_ap_mat); ?>&pagina=<?php echo $pagina_actual - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php } ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                    <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                        <a class="page-link" href="?controlador=clientes&accion=inicio&filter_nombres=<?php echo htmlspecialchars($filtro_nombres); ?>&filter_ap_pat=<?php echo htmlspecialchars($filtro_ap_pat); ?>&filter_ap_mat=<?php echo htmlspecialchars($filtro_ap_mat); ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>

                <?php if ($pagina_actual < $total_paginas) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?controlador=clientes&accion=inicio&filter_nombres=<?php echo htmlspecialchars($filtro_nombres); ?>&filter_ap_pat=<?php echo htmlspecialchars($filtro_ap_pat); ?>&filter_ap_mat=<?php echo htmlspecialchars($filtro_ap_mat); ?>&pagina=<?php echo $pagina_actual + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
