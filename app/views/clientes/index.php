<div class="topbar">
    <div>
        <h2>Clientes</h2>
        <div class="breadcrumb-sub">Gestión de clientes registrados</div>
    </div>
    <a href="<?= BASE_URL ?>/clientes/create" class="btn btn-gradient"><i class="bi bi-plus-lg me-1"></i> Nuevo Cliente</a>
</div>

<div class="card-panel">
    <form method="GET" class="search-box mb-3">
        <i class="bi bi-search"></i>
        <input type="text" name="q" class="form-control form-control-dark" placeholder="Buscar por nombre o NIT/CI..." value="<?= htmlspecialchars($busqueda) ?>">
    </form>

    <div class="table-responsive-wrap">
        <table class="table-dark-custom mb-0">
            <thead>
                <tr>
                    <th>Cliente</th><th>NIT/CI</th><th>Contacto</th><th>Dirección</th><th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($clientes)): ?>
                <tr><td colspan="5"><div class="empty-state"><i class="bi bi-people"></i>No se encontraron clientes.</div></td></tr>
            <?php else: foreach ($clientes as $c): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></strong></td>
                    <td><?= htmlspecialchars($c['nit_ci'] ?: '—') ?></td>
                    <td>
                        <?= htmlspecialchars($c['telefono'] ?: '—') ?><br>
                        <span class="text-secondary" style="font-size:.78rem;"><?= htmlspecialchars($c['email'] ?: '') ?></span>
                    </td>
                    <td><?= htmlspecialchars($c['direccion'] ?: '—') ?></td>
                    <td class="text-end">
                        <a href="<?= BASE_URL ?>/clientes/edit/<?= $c['id'] ?>" class="btn btn-sm btn-outline-soft"><i class="bi bi-pencil"></i></a>
                        <form id="del-<?= $c['id'] ?>" action="<?= BASE_URL ?>/clientes/eliminar/<?= $c['id'] ?>" method="POST" class="d-inline">
                            <button type="button" onclick="confirmarEliminacion('del-<?= $c['id'] ?>','¿Eliminar a <?= htmlspecialchars($c['nombre']) ?>?')" class="btn btn-sm btn-outline-soft text-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
