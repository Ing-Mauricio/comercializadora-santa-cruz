<div class="topbar">
    <div>
        <h2>Movimientos de Stock</h2>
        <div class="breadcrumb-sub">Historial de entradas y salidas de inventario</div>
    </div>
    <a href="<?= BASE_URL ?>/productos" class="btn btn-outline-soft"><i class="bi bi-arrow-left me-1"></i> Volver</a>
</div>

<div class="card-panel">
    <div class="table-responsive-wrap">
        <table class="table-dark-custom mb-0">
            <thead>
                <tr><th>Fecha</th><th>Producto</th><th>Tipo</th><th>Cantidad</th><th>Stock Anterior</th><th>Stock Nuevo</th><th>Usuario</th><th>Motivo</th></tr>
            </thead>
            <tbody>
            <?php if (empty($movimientos)): ?>
                <tr><td colspan="8"><div class="empty-state"><i class="bi bi-clock-history"></i>Aún no hay movimientos registrados.</div></td></tr>
            <?php else: foreach ($movimientos as $m): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></td>
                    <td><?= htmlspecialchars($m['producto_nombre']) ?></td>
                    <td>
                        <span class="badge-soft <?= $m['tipo'] === 'entrada' ? 'badge-success-soft' : 'badge-info-soft' ?>">
                            <i class="bi bi-arrow-<?= $m['tipo'] === 'entrada' ? 'down' : 'up' ?>-circle"></i> <?= ucfirst($m['tipo']) ?>
                        </span>
                    </td>
                    <td><?= $m['cantidad'] ?></td>
                    <td><?= $m['stock_anterior'] ?></td>
                    <td><?= $m['stock_nuevo'] ?></td>
                    <td><?= htmlspecialchars($m['usuario_nombre']) ?></td>
                    <td class="text-secondary"><?= htmlspecialchars($m['motivo']) ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
