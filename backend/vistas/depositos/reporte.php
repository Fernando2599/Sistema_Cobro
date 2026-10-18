<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Información de reportes generados
            </div>
            <div class="card-body">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Reporte</th>
                            <th scope="col">Reporte creado</th>
                            <th scope="col">Fechas a depositar</th>
                            <th scope="col">Talonarios</th>
                            <th scope="col">Monto del deposito</th>
                            <th scope="col">Efectivo</th>

                            <?php if ($_SESSION['rol_id'] == '1'): ?>
                            <th scope="col" class="text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportes as $reporte) { ?>
                        <tr>
                            <td>No. <?php echo $reporte->id; ?></td>
                            <td><?php echo $reporte->fecha;?></td>
                            
                            <td>
                                <ul>
                                    <?php foreach ($reporte->fechas_venta as $index => $fecha_venta) { ?>
                                    <li><?php echo $fecha_venta . " - Faltante: " . $reporte->faltante[$index]; ?></li>
                                    <?php } ?>
                                </ul>
                            </td>
                            <td><?php echo $reporte->conteo_registros; ?></td>
                            <td>$<?php echo number_format((float)$reporte->total_efectivo, 2); ?></td>
                            <td>$<?php echo number_format((float)$reporte->conteo_efectivo, 2); ?></td>
                            
                            <?php if ($_SESSION['rol_id'] == '1'): ?>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Acciones">
                                    <!-- Botón para generar el PDF con el detalle de clientes/recibos -->
                                    <a href="?controlador=depositos&accion=generarPdfDetallado&id=<?php echo $reporte->id; ?>" target="_blank" class="btn btn-danger btn-sm" title="Descargar PDF Detallado (Clientes)">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>

                                    <!-- Botón Editar -->
                                    <a href="?controlador=depositos&accion=editarReporte&id=<?php echo $reporte->id; ?>" class="btn btn-info btn-sm text-white" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Botón Eliminar -->
                                    <a href="?controlador=depositos&accion=borrarReporte&id=<?php echo $reporte->id; ?>" class="btn btn-warning btn-sm" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este reporte?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="public/js/deposito_reporte.js"></script>