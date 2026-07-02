<div class="topbar">
    <div>
        <h2>Editar Cliente</h2>
        <div class="breadcrumb-sub">Actualizar información de <?= htmlspecialchars($cliente['nombre']) ?></div>
    </div>
    <a href="<?= BASE_URL ?>/clientes" class="btn btn-outline-soft"><i class="bi bi-arrow-left me-1"></i> Volver</a>
</div>

<div class="card-panel" style="max-width: 720px;">
    <form action="<?= BASE_URL ?>/clientes/actualizar/<?= $cliente['id'] ?>" method="POST">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control form-control-dark" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellido *</label>
                <input type="text" name="apellido" class="form-control form-control-dark" value="<?= htmlspecialchars($cliente['apellido']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">NIT / CI</label>
                <input type="text" name="nit_ci" class="form-control form-control-dark" value="<?= htmlspecialchars($cliente['nit_ci']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control form-control-dark" value="<?= htmlspecialchars($cliente['telefono']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control form-control-dark" value="<?= htmlspecialchars($cliente['email']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control form-control-dark" value="<?= htmlspecialchars($cliente['direccion']) ?>">
            </div>
        </div>
        <hr class="section-divider">
        <button type="submit" class="btn btn-gradient"><i class="bi bi-save me-1"></i> Actualizar Cliente</button>
        <a href="<?= BASE_URL ?>/clientes" class="btn btn-outline-soft">Cancelar</a>
    </form>
</div>
