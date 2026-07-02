<div class="topbar">
    <div>
        <h2>Productos</h2>
        <div class="breadcrumb-sub">Inventario y catálogo de productos</div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/productos/movimientos" class="btn btn-outline-soft"><i class="bi bi-clock-history me-1"></i> Movimientos</a>
        <a href="<?= BASE_URL ?>/productos/create" class="btn btn-gradient"><i class="bi bi-plus-lg me-1"></i> Nuevo Producto</a>
    </div>
</div>

<div class="card-panel">
    <form method="GET" class="search-box mb-3">
        <i class="bi bi-search"></i>
        <input type="text" name="q" class="form-control form-control-dark" placeholder="Buscar por nombre o código..." value="<?= htmlspecialchars($busqueda) ?>">
    </form>

    <div class="table-responsive-wrap">
        <table class="table-dark-custom mb-0">
            <thead>
                <tr>
                    <th>Producto</th><th>Código</th><th>Categoría</th>
                    <th>Precio Compra</th><th>Precio Venta</th><th>Stock</th><th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($productos)): ?>
                <tr><td colspan="7"><div class="empty-state"><i class="bi bi-box-seam"></i>No se encontraron productos.</div></td></tr>
            <?php else: foreach ($productos as $p):
                $critico = $p['stock'] == 0;
                $bajo = $p['stock'] > 0 && $p['stock'] <= $p['stock_minimo'];
            ?>
                <tr>
                    <td class="d-flex align-items-center gap-2">
                        <img src="<?= BASE_URL ?>/public/img/products/<?= htmlspecialchars($p['imagen'] ?: 'no-image.svg') ?>" class="product-thumb">
                        <div>
                            <div style="font-weight:600;"><?= htmlspecialchars($p['nombre']) ?></div>
                            <div style="font-size:.74rem; color:var(--text-muted);"><?= htmlspecialchars(substr($p['descripcion'] ?? '', 0, 40)) ?></div>
                        </div>
                    </td>
                    <td><code><?= htmlspecialchars($p['codigo']) ?></code></td>
                    <td><?= htmlspecialchars($p['categoria_nombre'] ?: '—') ?></td>
                    <td>Bs <?= number_format($p['precio_compra'], 2) ?></td>
                    <td><strong>Bs <?= number_format($p['precio_venta'], 2) ?></strong></td>
                    <td>
                        <span class="badge-soft <?= $critico ? 'badge-danger-soft' : ($bajo ? 'badge-warning-soft' : 'badge-success-soft') ?>">
                            <?= $p['stock'] ?> unid. <?= $critico ? '· Agotado' : ($bajo ? '· Bajo' : '') ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-soft" data-bs-toggle="modal" data-bs-target="#modalStock<?= $p['id'] ?>" title="Añadir stock">
                            <i class="bi bi-box-arrow-in-down text-success"></i>
                        </button>
                        <a href="<?= BASE_URL ?>/productos/edit/<?= $p['id'] ?>" class="btn btn-sm btn-outline-soft"><i class="bi bi-pencil"></i></a>
                        <form id="delp-<?= $p['id'] ?>" action="<?= BASE_URL ?>/productos/eliminar/<?= $p['id'] ?>" method="POST" class="d-inline">
                            <button type="button" onclick="confirmarEliminacion('delp-<?= $p['id'] ?>','¿Eliminar el producto <?= htmlspecialchars($p['nombre']) ?>?')" class="btn btn-sm btn-outline-soft text-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>

                <!-- Modal entrada de stock -->
                <div class="modal fade" id="modalStock<?= $p['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content" style="background: var(--bg-card); border:1px solid var(--border-color); color: var(--text-primary);">
                            <form action="<?= BASE_URL ?>/productos/entradaStock/<?= $p['id'] ?>" method="POST">
                                <div class="modal-header" style="border-color: var(--border-color);">
                                    <h5 class="modal-title">Añadir Stock — <?= htmlspecialchars($p['nombre']) ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Cantidad a ingresar</label>
                                    <input type="number" min="1" name="cantidad" class="form-control form-control-dark mb-3" required>
                                    <label class="form-label">Motivo</label>
                                    <input type="text" name="motivo" class="form-control form-control-dark" placeholder="Ej: Compra a proveedor">
                                </div>
                                <div class="modal-footer" style="border-color: var(--border-color);">
                                    <button type="submit" class="btn btn-gradient w-100"><i class="bi bi-check-lg me-1"></i>Confirmar Ingreso</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
