<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Información de reportes generados
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Reporte</th>
                            <th scope="col">Reporte creado el</th>
                            <th scope="col">Reporte creado a las</th>
                            <th scope="col">Fechas a depositar</th>
                            <th scope="col">Talonarios</th>
                            <th scope="col">Monto total del deposito</th>
                            <th scope="col">Efectivo</th>

                            <?php if ($_SESSION['rol_id'] == '1'): ?>
                            <th scope="col">Accion</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportes as $reporte) { ?>
                        <tr>
                            <td>No. <?php echo $reporte->id; ?></td>
                            <td><?php echo $reporte->fecha; ?></td>
                            <td><?php echo $reporte->hora; ?></td>
                            <td>
                                <ul>
                                    <?php foreach ($reporte->fechas_venta as $fecha_venta) { ?>
                                    <li><?php echo $fecha_venta; ?></li>
                                    <?php } ?>
                                </ul>
                            </td>
                            <td><?php echo $reporte->conteo_registros; ?></td>
                            <td><?php echo $reporte->total_efectivo; ?></td>
                            <td><?php echo $reporte->conteo_efectivo; ?></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Button group name">
                                    <a href="?controlador=depositos&accion=editarReporte&id=<?php echo $reporte->id; ?>" class="btn btn-info"> Editar </a>
                                    <a href="?controlador=depositos&accion=borrarReporte&id=<?php echo $reporte->id; ?>" class="btn btn-danger"> Borrar </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="public/js/deposito_reporte.js"></script>