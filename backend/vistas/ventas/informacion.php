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
                        $suma_total_venta = 0;
                        $suma_total_talonarios = 0; // Inicializar la suma
                        foreach ($ventas as $info): ?>
                            <tr>
                                <td><?php echo $info->fecha; ?></td>
                                <td><?php echo $info->hora; ?></td>
                                <td><?php echo $info->monto_recibo; ?></td>
                                
                                <?php if ($_SESSION['rol_id'] == '2'):?>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group name">
                                        <a href="?controlador=ventas&accion=editar&id=<?php echo $info->id;?>" class="btn btn-info"> Editar </a>
                                        <a href="?controlador=ventas&accion=borrar&id=<?php echo $info->id;?>" class="btn btn-danger"> Borrar </a>
                                    </div>
                                </td>
                                <?php endif;?>
                            </tr>
                            <?php 
                                $suma_total_venta += $info->monto_recibo; // Sumar el total_venta
                                $suma_total_talonarios += 1;
                            ?>
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
                            Información de ventas
                    </div>
                    <div class="card-body">
                            <table
                                class="table table-bordered"
                            >
                                <thead>
                                    <tr>
                                        <th scope="col">Total en caja</th>
                                        <th scope="col">Talonarios</th>
                                        <th scope="col">Venta total del día</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                    $suma_total_cortes = 0;
                                    foreach ($cortes as $corte) {
                                        $suma_total_cortes += $corte->corte_cantidad;
                                    }
                                    $total_en_caja = $suma_total_venta - $suma_total_cortes;
                                    ?>
                                
                                    <tr>
                                        <td><?php echo $total_en_caja; ?></td>
                                        <td><?php echo $suma_total_talonarios; ?></td>
                                        <td><?php echo $suma_total_venta; ?></td>
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
                                    <th scope="col">#corte</th>
                                    <th scope="col">Cantidad del corte</th>
                                    <?php if ($_SESSION['rol_id'] == '2'): ?>
                                        <th scope="col">Accion</th>
                                     <?php endif;?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cortes as $corte): ?>
                                <tr>
                                    <td><?php echo $corte->id; ?></td>
                                    <td><?php echo $corte->corte_cantidad; ?></td>
                                    <?php if ($_SESSION['rol_id'] == '2'):?>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group name">
                                                <a href="?controlador=cortes&accion=editar&id=<?php echo $corte->id;?>" class="btn btn-info"> Editar </a>
                                                <a href="?controlador=cortes&accion=borrar&id=<?php echo $corte->id;?>" class="btn btn-danger"> Borrar </a>
                                            </div>
                                        </td>
                                    <?php endif;?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
        </div>
    </div>

</div>
<script src="public/js/venta_informacion_pag.js"></script>