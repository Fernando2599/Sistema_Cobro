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
                            <p><strong>Estado:</strong> <?php echo ucfirst(htmlspecialchars($primer_periodo['estado'])); ?></p>
                            <p><strong>Monto:</strong> <?php echo htmlspecialchars($primer_periodo['monto_recibo']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-3">Historial de Pagos:</h5>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Periodo Facturado</th>
                        <th scope="col">Fecha Límite de Pago</th>
                        <th scope="col">Monto</th>
                        <th scope="col">Estado</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $pago): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pago['periodo_inicio']) . ' - ' . htmlspecialchars($pago['periodo_fin']); ?></td>
                            <td><?php echo htmlspecialchars($pago['limite_pago']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($pago['monto_recibo'])); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($pago['estado'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay periodos registrados.</p>
        <?php endif; ?>
    </div>
</div>
