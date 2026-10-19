<div class="card">
    <div class="card-header">
        <h4 class="d-inline">
            <?php
            if ($nombre_cliente) {
                echo htmlspecialchars($nombre_cliente['nombres']) . ' ' . htmlspecialchars($nombre_cliente['ap_pat']) . ' ' . htmlspecialchars($nombre_cliente['ap_mat']);
            }
            ?>
        </h4>
        <a class="btn btn-success float-end" href="?controlador=clientes&accion=inicio" role="button">Regresar</a>
    </div>

    <div class="card-body">
        <?php if (!empty($historial)): ?>
            <?php
            // Resaltar el primer período como el más reciente
            $primer_periodo = array_shift($historial);
            
            // Estado del periodo actual
            $estado_actual = strtolower(trim($primer_periodo['estado']));
            $badge_actual = ($estado_actual === 'pagado') ? 'badge bg-success' : 'badge bg-danger';
            ?>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card border-primary mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Periodo Actual</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>No. de servicio:</strong> <?php echo htmlspecialchars($nombre_cliente['numero_servicio']); ?></p>
                            <p><strong>Periodo Facturado:</strong> <?php echo htmlspecialchars($primer_periodo['periodo_inicio']) . ' - ' . htmlspecialchars($primer_periodo['periodo_fin']); ?></p>
                            <p><strong>Fecha Límite de Pago:</strong> <?php echo htmlspecialchars($primer_periodo['limite_pago']); ?></p>
                            <p><strong>Fecha de Pago Realizado:</strong> 
                                <?php if (!empty($primer_periodo['fecha_pago'])): ?>
                                    <span class="badge bg-success px-2 py-1"><?php echo htmlspecialchars($primer_periodo['fecha_pago']); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger px-2 py-1">Pendiente</span>
                                <?php endif; ?>
                            </p>
                            <p><strong>Estado:</strong> <span class="<?php echo $badge_actual; ?>"><?php echo ucfirst(htmlspecialchars($primer_periodo['estado'])); ?></span></p>
                            <p><strong>Monto:</strong> <?php echo htmlspecialchars($primer_periodo['monto_recibo']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-3">Historial de Pagos:</h5>
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th scope="col">Periodo Facturado</th>
                        <th scope="col">Fecha Límite de Pago</th>
                        <th scope="col">Fecha de Pago Realizado</th>
                        <th scope="col">Monto</th>
                        <th scope="col">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $pago): ?>
                        <?php 
                        // Estado de cada fila
                        $estado_pago = strtolower(trim($pago['estado']));
                        $badge_pago = ($estado_pago === 'pagado') ? 'badge bg-success' : 'badge bg-danger';
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pago['periodo_inicio']) . ' - ' . htmlspecialchars($pago['periodo_fin']); ?></td>
                            <td><?php echo htmlspecialchars($pago['limite_pago']); ?></td>
                            <td>
                                <?php if (!empty($pago['fecha_pago'])): ?>
                                    <span class="badge bg-success"><?php echo htmlspecialchars($pago['fecha_pago']); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($pago['monto_recibo']); ?></td>
                            <td><span class="<?php echo $badge_pago; ?>"><?php echo ucfirst(htmlspecialchars($pago['estado'])); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay periodos registrados.</p>
        <?php endif; ?>
    </div>
</div>