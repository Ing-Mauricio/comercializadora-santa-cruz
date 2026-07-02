<div class="topbar">
    <div>
        <h2>Dashboard</h2>
        <div class="breadcrumb-sub">Resumen general del negocio · <?= date('d/m/Y') ?></div>
    </div>
    <a href="<?= BASE_URL ?>/ventas/create" class="btn btn-gradient"><i class="bi bi-plus-lg me-1"></i> Nueva Venta</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-indigo"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-value">Bs <?= number_format($ventasHoy, 2) ?></div>
            <div class="stat-label">Ventas de Hoy (<?= $numVentasHoy ?> transacciones)</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-cyan"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="stat-value">Bs <?= number_format($ventasMes, 2) ?></div>
            <div class="stat-label">Ventas del Mes</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="bi bi-box-seam"></i></div>
            <div class="stat-value"><?= $totalProductos ?></div>
            <div class="stat-label">Productos Activos</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-amber"><i class="bi bi-people"></i></div>
            <div class="stat-value"><?= $totalClientes ?></div>
            <div class="stat-label">Clientes Registrados</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl-8">
        <div class="card-panel h-100">
            <div class="panel-title"><i class="bi bi-bar-chart-line text-info"></i> Ventas de los últimos 7 días</div>
            <div class="panel-sub">Comportamiento de ingresos diarios</div>
            <canvas id="chartVentas" height="110"></canvas>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card-panel h-100">
            <div class="panel-title">
                <i class="bi bi-exclamation-triangle-fill text-danger"></i> Alertas de Stock
            </div>
            <div class="panel-sub">Productos que necesitan reposición</div>

            <?php if (empty($stockBajo)): ?>
                <div class="empty-state py-4">
                    <i class="bi bi-check-circle"></i>
                    <p class="mb-0">Todo el inventario está en niveles saludables.</p>
                </div>
            <?php else: ?>
                <div style="max-height: 260px; overflow-y:auto;">
                <?php foreach ($stockBajo as $p): ?>
                    <div class="alert-stock">
                        <span class="badge-soft <?= $p['stock'] == 0 ? 'badge-critico' : 'badge-bajo' ?> text-white">
                            <?= $p['stock'] == 0 ? 'AGOTADO' : 'BAJO' ?>
                        </span>
                        <div class="flex-grow-1">
                            <div style="font-size:.85rem; font-weight:600;"><?= htmlspecialchars($p['nombre']) ?></div>
                            <div style="font-size:.75rem; color:var(--text-secondary);">
                                Quedan <strong><?= $p['stock'] ?></strong> / mínimo <?= $p['stock_minimo'] ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
                <a href="<?= BASE_URL ?>/productos" class="btn btn-outline-soft btn-sm w-100 mt-2">Ver inventario completo</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card-panel">
            <div class="panel-title"><i class="bi bi-trophy-fill text-warning"></i> Productos más vendidos</div>
            <div class="panel-sub">Top 5 productos con mayor rotación</div>
            <div class="table-responsive-wrap">
                <table class="table-dark-custom mb-0">
                    <thead><tr><th>Producto</th><th>Cantidad Vendida</th><th>Total Generado</th></tr></thead>
                    <tbody>
                    <?php if (empty($masVendidos)): ?>
                        <tr><td colspan="3" class="text-center text-secondary py-4">Aún no hay ventas registradas.</td></tr>
                    <?php else: foreach ($masVendidos as $mv): ?>
                        <tr>
                            <td class="d-flex align-items-center gap-2">
                                <img src="<?= BASE_URL ?>/public/img/products/<?= htmlspecialchars($mv['imagen'] ?: 'no-image.svg') ?>" class="product-thumb">
                                <?= htmlspecialchars($mv['nombre']) ?>
                            </td>
                            <td><?= (int)$mv['cantidad_vendida'] ?> unid.</td>
                            <td>Bs <?= number_format($mv['total_generado'], 2) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="app-footer">Sistema Comercializadora &copy; <?= date('Y') ?> · Panel de administración</footer>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const ventasData = <?= json_encode($ventasSemana) ?>;
const labels = [];
const values = [];
const today = new Date();
for (let i = 6; i >= 0; i--) {
    const d = new Date();
    d.setDate(today.getDate() - i);
    const iso = d.toISOString().split('T')[0];
    labels.push(d.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' }));
    const found = ventasData.find(v => v.dia === iso);
    values.push(found ? parseFloat(found.total) : 0);
}

new Chart(document.getElementById('chartVentas'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Ventas (Bs)',
            data: values,
            borderColor: '#22d3ee',
            backgroundColor: 'rgba(99,102,241,0.15)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#6366f1',
            pointRadius: 4,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: '#22293f' }, ticks: { color: '#8b93b0' } },
            y: { grid: { color: '#22293f' }, ticks: { color: '#8b93b0' } }
        }
    }
});
</script>
