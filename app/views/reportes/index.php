<div class="topbar">
    <div>
        <h2>Reportes</h2>
        <div class="breadcrumb-sub">Análisis de ventas, desempeño e inventario</div>
    </div>
</div>

<div class="card-panel mb-3">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control form-control-dark" value="<?= htmlspecialchars($desde) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control form-control-dark" value="<?= htmlspecialchars($hasta) ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-gradient"><i class="bi bi-funnel me-1"></i> Filtrar</button>
        </div>
    </form>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon icon-indigo"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">Bs <?= number_format($totalPeriodo, 2) ?></div>
            <div class="stat-label">Total Vendido en el Periodo</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon icon-cyan"><i class="bi bi-receipt"></i></div>
            <div class="stat-value"><?= count($ventasRango) ?></div>
            <div class="stat-label">Transacciones Realizadas</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="bi bi-archive"></i></div>
            <div class="stat-value">Bs <?= number_format($valorInventario, 2) ?></div>
            <div class="stat-label">Valor Total del Inventario</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card-panel h-100">
            <div class="panel-title"><i class="bi bi-person-badge-fill text-info"></i> Ventas por Vendedor</div>
            <div class="panel-sub">Desempeño de cada usuario en el periodo seleccionado</div>
            <div class="table-responsive-wrap">
                <table class="table-dark-custom mb-0">
                    <thead><tr><th>Vendedor</th><th># Ventas</th><th>Total Vendido</th></tr></thead>
                    <tbody>
                    <?php if (empty($ventasPorVendedor)): ?>
                        <tr><td colspan="3" class="text-center text-secondary py-3">Sin datos en este periodo.</td></tr>
                    <?php else: foreach ($ventasPorVendedor as $vv): ?>
                        <tr>
                            <td><?= htmlspecialchars($vv['vendedor']) ?></td>
                            <td><?= $vv['num_ventas'] ?></td>
                            <td>Bs <?= number_format($vv['total_vendido'], 2) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-panel h-100">
            <div class="panel-title"><i class="bi bi-trophy-fill text-warning"></i> Productos Más Vendidos</div>
            <div class="panel-sub">Top 10 histórico por cantidad</div>
            <div class="table-responsive-wrap">
                <table class="table-dark-custom mb-0">
                    <thead><tr><th>Producto</th><th>Cant.</th><th>Total</th></tr></thead>
                    <tbody>
                    <?php if (empty($masVendidos)): ?>
                        <tr><td colspan="3" class="text-center text-secondary py-3">Sin ventas registradas.</td></tr>
                    <?php else: foreach ($masVendidos as $mv): ?>
                        <tr>
                            <td><?= htmlspecialchars($mv['nombre']) ?></td>
                            <td><?= (int)$mv['cantidad_vendida'] ?></td>
                            <td>Bs <?= number_format($mv['total_generado'], 2) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card-panel mb-3">
    <div class="panel-title"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Productos con Stock Bajo</div>
    <div class="panel-sub">Requieren reposición inmediata</div>
    <div class="table-responsive-wrap">
        <table class="table-dark-custom mb-0">
            <thead><tr><th>Producto</th><th>Categoría</th><th>Stock Actual</th><th>Stock Mínimo</th></tr></thead>
            <tbody>
            <?php if (empty($stockBajo)): ?>
                <tr><td colspan="4" class="text-center text-secondary py-3">Inventario en niveles saludables.</td></tr>
            <?php else: foreach ($stockBajo as $sb): ?>
                <tr>
                    <td><?= htmlspecialchars($sb['nombre']) ?></td>
                    <td><?= htmlspecialchars($sb['categoria_nombre'] ?: '—') ?></td>
                    <td><span class="badge-soft <?= $sb['stock'] == 0 ? 'badge-danger-soft' : 'badge-warning-soft' ?>"><?= $sb['stock'] ?></span></td>
                    <td><?= $sb['stock_minimo'] ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card-panel">
    <div class="panel-title"><i class="bi bi-list-columns-reverse text-secondary"></i> Detalle de Ventas del Periodo</div>
    <div class="table-responsive-wrap">
        <table class="table-dark-custom mb-0">
            <thead><tr><th>Factura</th><th>Fecha</th><th>Cliente</th><th>Vendedor</th><th class="text-end">Total</th></tr></thead>
            <tbody>
            <?php if (empty($ventasRango)): ?>
                <tr><td colspan="5" class="text-center text-secondary py-3">Sin ventas en este periodo.</td></tr>
            <?php else: foreach ($ventasRango as $v): ?>
                <tr>
                    <td><code><?= htmlspecialchars($v['numero_factura']) ?></code></td>
                    <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                    <td><?= htmlspecialchars($v['cliente_nombre']) ?></td>
                    <td><?= htmlspecialchars($v['vendedor_nombre']) ?></td>
                    <td class="text-end">Bs <?= number_format($v['total'], 2) ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
