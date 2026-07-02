<div class="topbar">
    <div>
        <h2>Factura <?= htmlspecialchars($venta['numero_factura']) ?></h2>
        <div class="breadcrumb-sub">Detalle de la venta</div>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-soft"><i class="bi bi-printer me-1"></i> Imprimir</button>
        <a href="<?= BASE_URL ?>/ventas" class="btn btn-outline-soft"><i class="bi bi-arrow-left me-1"></i> Volver</a>
    </div>
</div>

<div class="card-panel" style="max-width: 850px;">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="text-secondary" style="font-size:.78rem;">CLIENTE</div>
            <div style="font-weight:700;"><?= htmlspecialchars($venta['cliente_nombre']) ?></div>
            <?php if (!empty($venta['nit_ci'])): ?><div class="text-secondary" style="font-size:.82rem;">NIT/CI: <?= htmlspecialchars($venta['nit_ci']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="text-secondary" style="font-size:.78rem;">FECHA</div>
            <div style="font-weight:700;"><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></div>
            <div class="text-secondary" style="font-size:.82rem;">Vendedor: <?= htmlspecialchars($venta['vendedor_nombre']) ?></div>
        </div>
    </div>

    <div class="table-responsive-wrap mb-4">
        <table class="table-dark-custom mb-0">
            <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio Unit.</th><th class="text-end">Subtotal</th></tr></thead>
            <tbody>
            <?php foreach ($detalle as $d): ?>
                <tr>
                    <td class="d-flex align-items-center gap-2">
                        <img src="<?= BASE_URL ?>/public/img/products/<?= htmlspecialchars($d['imagen'] ?: 'no-image.svg') ?>" class="product-thumb">
                        <?= htmlspecialchars($d['producto_nombre']) ?>
                    </td>
                    <td><?= $d['cantidad'] ?></td>
                    <td>Bs <?= number_format($d['precio_unitario'], 2) ?></td>
                    <td class="text-end">Bs <?= number_format($d['subtotal'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="ms-auto" style="max-width: 280px;">
        <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Subtotal</span><span>Bs <?= number_format($venta['subtotal'], 2) ?></span></div>
        <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Descuento</span><span>Bs <?= number_format($venta['descuento'], 2) ?></span></div>
        <hr class="section-divider">
        <div class="d-flex justify-content-between"><strong>Total</strong><strong style="color: var(--accent-secondary); font-size:1.2rem;">Bs <?= number_format($venta['total'], 2) ?></strong></div>
    </div>
</div>
