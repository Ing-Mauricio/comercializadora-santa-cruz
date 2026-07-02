<div class="topbar">
    <div>
        <h2>Editar Producto</h2>
        <div class="breadcrumb-sub">Actualizar <?= htmlspecialchars($producto['nombre']) ?></div>
    </div>
    <a href="<?= BASE_URL ?>/productos" class="btn btn-outline-soft"><i class="bi bi-arrow-left me-1"></i> Volver</a>
</div>

<div class="card-panel" style="max-width: 900px;">
    <form action="<?= BASE_URL ?>/productos/actualizar/<?= $producto['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-4 text-center">
                <label class="form-label d-block">Imagen del Producto</label>
                <img id="previewImg" src="<?= BASE_URL ?>/public/img/products/<?= htmlspecialchars($producto['imagen'] ?: 'no-image.svg') ?>" class="product-thumb-lg mb-2">
                <input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp" class="form-control form-control-dark" onchange="previewImagen(this)">
            </div>
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Código *</label>
                        <input type="text" name="codigo" class="form-control form-control-dark" value="<?= htmlspecialchars($producto['codigo']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Categoría</label>
                        <select name="categoria_id" class="form-select form-control-dark">
                            <option value="">Sin categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $producto['categoria_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control form-control-dark" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control form-control-dark" rows="2"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <hr class="section-divider">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Precio de Compra (Bs) *</label>
                <input type="number" step="0.01" min="0" name="precio_compra" class="form-control form-control-dark" value="<?= $producto['precio_compra'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Precio de Venta (Bs) *</label>
                <input type="number" step="0.01" min="0" name="precio_venta" class="form-control form-control-dark" value="<?= $producto['precio_venta'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock Mínimo (alerta)</label>
                <input type="number" min="0" name="stock_minimo" class="form-control form-control-dark" value="<?= $producto['stock_minimo'] ?>">
            </div>
        </div>
        <div class="form-text text-secondary mt-2" style="font-size:.78rem;">
            Stock actual: <strong><?= $producto['stock'] ?></strong> unidades. Para aumentar el stock usa el botón "Añadir stock" en el listado (queda registrado en auditoría).
        </div>

        <hr class="section-divider">
        <button type="submit" class="btn btn-gradient"><i class="bi bi-save me-1"></i> Actualizar Producto</button>
        <a href="<?= BASE_URL ?>/productos" class="btn btn-outline-soft">Cancelar</a>
    </form>
</div>

<script>
function previewImagen(input) {
    if (input.files && input.files[0]) {
        document.getElementById('previewImg').src = URL.createObjectURL(input.files[0]);
    }
}
</script>
