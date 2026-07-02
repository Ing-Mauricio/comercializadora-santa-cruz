<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comercializadora | Sistema de Gestión</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
<?php
// Alertas de stock bajo disponibles en toda vista autenticada
if (!class_exists('Producto')) {
    require_once APP_PATH . '/models/Producto.php';
}
$__productoModelHeader = new Producto();
$__stockBajoHeader = isset($_SESSION['usuario_id']) ? $__productoModelHeader->stockBajo() : [];
?>
<button class="btn btn-gradient btn-toggle-sidebar position-fixed m-2" style="z-index:1100;" onclick="document.querySelector('.sidebar').classList.toggle('show')">
    <i class="bi bi-list"></i>
</button>
