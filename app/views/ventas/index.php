<div class="topbar">
    <div>
        <h2>Ventas</h2>
        <div class="breadcrumb-sub">Historial de transacciones registradas</div>
    </div>
    <a href="<?= BASE_URL ?>/ventas/create" class="btn btn-gradient"><i class="bi bi-plus-lg me-1"></i> Nueva Venta</a>
</div>

<div class="card-panel">
    <div class="table-responsive-wrap">
        <table class="table-dark-custom mb-0">
            <thead>
                <tr><th>N° Factura</th><th>Fecha</th><th>Cliente</th><th>Vendedor</th><th>Total</th><th>Estado</th><th class="text-end">Acciones</th></tr>
            </thead>
            <tbody>
            <?php if (empty($ventas)): ?>
                <tr><td colspan="7"><div class="empty-state"><i class="bi bi-cart-x"></i>Aún no hay ventas registradas.</div></td></tr>
            <?php else: foreach ($ventas as $v): ?>
                <tr>
                    <td><code><?= htmlspecialchars($v['numero_factura']) ?></code></td>
                    <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                    <td><?= htmlspecialchars($v['cliente_nombre']) ?></td>
                    <td><?= htmlspecialchars($v['vendedor_nombre']) ?></td>
                    <td><strong>Bs <?= number_format($v['total'], 2) ?></strong></td>
                    <td>
                        <span class="badge-soft <?= $v['estado'] === 'completada' ? 'badge-success-soft' : 'badge-danger-soft' ?>">
                            <?= ucfirst($v['estado']) ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?= BASE_URL ?>/ventas/show/<?= $v['id'] ?>" class="btn btn-sm btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
