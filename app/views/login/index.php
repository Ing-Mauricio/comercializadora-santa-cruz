<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar Sesión | Comercializadora</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-brand">
            <div class="brand-icon"><i class="bi bi-boxes"></i></div>
            <h1>Comercializadora</h1>
            <p>Plataforma integral de gestión comercial: inventario, ventas, clientes, reportes y auditoría en un solo lugar.</p>
            <ul class="list-unstyled mt-4 text-white-50" style="font-size:.85rem;">
                <li class="mb-2"><i class="bi bi-check2-circle me-2"></i>Control de stock en tiempo real</li>
                <li class="mb-2"><i class="bi bi-check2-circle me-2"></i>Auditoría de usuarios y movimientos</li>
                <li class="mb-2"><i class="bi bi-check2-circle me-2"></i>Reportes y estadísticas de ventas</li>
            </ul>
        </div>
        <div class="login-form-wrap">
            <h3>Bienvenido de nuevo</h3>
            <p class="subtitle">Ingresa tus credenciales para acceder al panel</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2" style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.35);color:#f87171;">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/login/autenticar" method="POST">
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control form-control-dark" placeholder="correo@empresa.com" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-dark" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-gradient w-100 py-2">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                </button>
            </form>

            <div class="demo-credentials">
                <strong><i class="bi bi-info-circle me-1"></i>Credenciales de prueba</strong><br>
                Admin: admin@comercializadora.com / 123456<br>
                Vendedor: vendedor@comercializadora.com / 123456
            </div>
        </div>
    </div>
</div>
</body>
</html>
