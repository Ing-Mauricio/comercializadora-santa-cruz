<?php
$currentUrl = trim($_GET['url'] ?? 'dashboard', '/');
$currentModule = explode('/', $currentUrl)[0] ?: 'dashboard';
function navActive($mod, $current) { return $mod === $current ? 'active' : ''; }
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo-box">C</div>
        <span>Comercializadora</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Principal</div>
        <a href="<?= BASE_URL ?>/dashboard" class="<?= navActive('dashboard', $currentModule) ?>">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
            <?php if (count($__stockBajoHeader) > 0): ?>
                <span class="ms-auto badge-soft badge-danger-soft"><?= count($__stockBajoHeader) ?></span>
            <?php endif; ?>
        </a>

        <div class="nav-section-title">Gestión</div>
        <a href="<?= BASE_URL ?>/clientes" class="<?= navActive('clientes', $currentModule) ?>">
            <i class="bi bi-people-fill"></i> Clientes
        </a>
        <a href="<?= BASE_URL ?>/productos" class="<?= navActive('productos', $currentModule) ?>">
            <i class="bi bi-box-seam-fill"></i> Productos
        </a>
        <a href="<?= BASE_URL ?>/ventas" class="<?= navActive('ventas', $currentModule) ?>">
            <i class="bi bi-cart-check-fill"></i> Ventas
        </a>

        <div class="nav-section-title">Análisis</div>
        <a href="<?= BASE_URL ?>/reportes" class="<?= navActive('reportes', $currentModule) ?>">
            <i class="bi bi-bar-chart-line-fill"></i> Reportes
        </a>
        <a href="<?= BASE_URL ?>/auditoria" class="<?= navActive('auditoria', $currentModule) ?>">
            <i class="bi bi-shield-check"></i> Auditoría
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="avatar"><?= strtoupper(substr($_SESSION['usuario_nombre'] ?? 'U', 0, 1)) ?></div>
        <div class="flex-grow-1">
            <div class="user-name"><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?></div>
            <div class="user-role"><?= htmlspecialchars($_SESSION['usuario_rol'] ?? '') ?></div>
        </div>
        <a href="<?= BASE_URL ?>/login/logout" title="Cerrar sesión" class="text-danger">
            <i class="bi bi-box-arrow-right fs-5"></i>
        </a>
    </div>
</aside>
