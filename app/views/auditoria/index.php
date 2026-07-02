<div class="topbar">
    <div>
        <h2>Auditoría del Sistema</h2>
        <div class="breadcrumb-sub">Bitácora de acciones: quién vendió, quién modificó stock, accesos, etc.</div>
    </div>
</div>

<div class="card-panel mb-3">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Módulo</label>
            <select name="modulo" class="form-select form-control-dark">
                <option value="">Todos</option>
                <?php foreach ($modulos as $m): ?>
                    <option value="<?= htmlspecialchars($m) ?>" <?= $filtros['modulo'] === $m ? 'selected' : '' ?>><?= htmlspecialchars($m) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Usuario</label>
            <select name="usuario_id" class="form-select form-control-dark">
                <option value="">Todos</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= (string)$filtros['usuario_id'] === (string)$u['id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control form-control-dark" value="<?= htmlspecialchars($filtros['desde']) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control form-control-dark" value="<?= htmlspecialchars($filtros['hasta']) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-gradient w-100"><i class="bi bi-funnel me-1"></i> Filtrar</button>
        </div>
    </form>
</div>

<div class="card-panel">
    <div class="table-responsive-wrap">
        <table class="table-dark-custom mb-0">
            <thead><tr><th>Fecha</th><th>Usuario</th><th>Módulo</th><th>Acción</th><th>Descripción</th><th>IP</th></tr></thead>
            <tbody>
            <?php if (empty($registros)): ?>
                <tr><td colspan="6"><div class="empty-state"><i class="bi bi-shield-check"></i>No hay registros de auditoría con estos filtros.</div></td></tr>
            <?php else: foreach ($registros as $r): ?>
                <tr>
                    <td style="white-space:nowrap;"><?= date('d/m/Y H:i', strtotime($r['fecha'])) ?></td>
                    <td><?= htmlspecialchars($r['usuario_nombre'] ?: 'Sistema') ?></td>
                    <td><span class="badge-soft badge-info-soft"><?= htmlspecialchars($r['modulo']) ?></span></td>
                    <td><?= htmlspecialchars($r['accion']) ?></td>
                    <td class="text-secondary"><?= htmlspecialchars($r['descripcion']) ?></td>
                    <td class="text-secondary"><?= htmlspecialchars($r['ip'] ?: '—') ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
