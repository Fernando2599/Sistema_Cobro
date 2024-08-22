<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                Información de recibos
            </div>
            <div class="card-body">
                <!-- Formulario para seleccionar la fecha de consulta -->
                <div class="card">
                    <div class="card-body">
                        <form action="?controlador=ventas&accion=informacion" method="POST">
                            <div class="mb-3">
                                <label for="fecha_consulta">Seleccione una fecha para consultar:</label>
                                <input type="date" id="fecha_consulta" name="fecha_consulta" class="form-control" value="<?php echo isset($_POST['fecha_consulta']) ? $_POST['fecha_consulta'] : date('Y-m-d'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Consultar</button>
                        </form>
                    </div>
                </div>
                <br>
                <table class="table table-bordered" id="recibosTable">
                    <thead>
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Hora</th>
                            <th scope="col">Precio del recibo</th>
                            <?php if ($_SESSION['rol_id'] == '2'): ?>
                                <th scope="col">Accion</th>
                            <?php endif;?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($ventas as $info): ?>
                            <tr>
                                <td><?php echo $info->fecha; ?></td>
                                <td><?php echo $info->hora; ?></td>
                                <td><?php echo $info->monto_recibo; ?></td>
                                
                                <?php if ($_SESSION['rol_id'] == '2'):?>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group name">
                                    <a href="?controlador=ventas&accion=editar&id=<?php echo $info->id;?>"    class="btn btn-info">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?controlador=ventas&accion=borrar&id=<?php echo $info->id;?>" class="btn btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    </div>
                                </td>
                                <?php endif;?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination" id="pagination">
                        <!-- Aquí se agregarán los botones de paginación -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>



    <div class="col-md-5">
        <div class="card">
                    <div class="card-header">
                            Información de caja
                    </div>
                    <div class="card-body">
                            <table
                                class="table table-bordered"
                            >
                                <thead>
                                    <tr>
                                        <th scope="col">Efectivo en caja</th>
                                        <th scope="col">Talonarios en caja</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    
                                    <!-- Datos para informacion de la caja-->
                                    <tr>
                                        <td><?php echo $venta_total - $cortes_total; ?></td>
                                        <td><?php echo $talonarios_total - $monto_total_talonarios; ?></td>
                                    </tr>   
                                </tbody>
                            </table>
                    </div>
        </div>
        <br>
        <div class="card">
                    <div class="card-header">
                        Información de cortes
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Talonarios</th>
                                    <?php if ($_SESSION['rol_id'] == '2'): ?>
                                        <th scope="col">Accion</th>
                                    <?php endif;?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cortes as $corte): ?>
                                <tr>
                                    
                                    <td><?php echo $corte->corte_cantidad; ?></td>
                                    <td><?php echo $corte->talonarios; ?></td>
                                    <?php if ($_SESSION['rol_id'] == '2'):?>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group name">
                                                <a href="?controlador=cortes&accion=editar&id=<?php echo $corte->id;?>" class="btn btn-info"><i class="bi bi-pencil"></i></a>
                                                <a href="?controlador=cortes&accion=borrar&id=<?php echo $corte->id;?>" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </td>
                                    <?php endif;?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
        </div>
        <br>
        <div class="card">
                    <div class="card-header">
                            Información del día
                    </div>
                    <div class="card-body">
                            <table
                                class="table table-bordered"
                            >
                                <thead>
                                    <tr>
                                        <th scope="col">Venta total</th>
                                        <th scope="col">Total de talonarios</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    
                                    <!-- Datos para informacion de la caja-->
                                    <tr>
                                        <td><?php echo $venta_total; ?></td>
                                        <td><?php echo $talonarios_total; ?></td> 
                                    </tr>   
                                </tbody>
                            </table>
                    </div>
        </div> 
    </div>

</div>
<script src="public/js/venta_informacion_pag.js"></script>