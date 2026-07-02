<div class="topbar">
    <div>
        <h2>Nueva Venta</h2>
        <div class="breadcrumb-sub">Selecciona los productos y confirma la transacción</div>
    </div>
    <a href="<?= BASE_URL ?>/ventas" class="btn btn-outline-soft"><i class="bi bi-arrow-left me-1"></i> Volver</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card-panel">
            <div class="panel-title"><i class="bi bi-grid-3x3-gap-fill text-info"></i> Catálogo de Productos</div>
            <input type="text" id="filtroProducto" class="form-control form-control-dark mb-3" placeholder="Buscar producto por nombre o código...">
            <div class="row g-2" id="gridProductos">
                <?php foreach ($productos as $p): ?>
                <div class="col-6 col-md-4 col-xl-3 producto-item" data-nombre="<?= strtolower(htmlspecialchars($p['nombre'])) ?>" data-codigo="<?= strtolower(htmlspecialchars($p['codigo'])) ?>">
                    <div class="product-pick-card" onclick='agregarAlCarrito(<?= json_encode(["id"=>$p['id'],"nombre"=>$p['nombre'],"precio"=>(float)$p['precio_venta'],"stock"=>(int)$p['stock'],"imagen"=>$p['imagen'] ?: "no-image.svg"]) ?>)">
                        <img src="<?= BASE_URL ?>/public/img/products/<?= htmlspecialchars($p['imagen'] ?: 'no-image.svg') ?>" class="product-thumb-lg mb-2">
                        <div class="pname"><?= htmlspecialchars($p['nombre']) ?></div>
                        <div class="price">Bs <?= number_format($p['precio_venta'], 2) ?></div>
                        <div class="mt-1">
                            <span class="badge-soft <?= $p['stock'] == 0 ? 'badge-danger-soft' : ($p['stock'] <= $p['stock_minimo'] ? 'badge-warning-soft' : 'badge-secondary-soft') ?>">
                                Stock: <?= $p['stock'] ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-panel">
            <div class="panel-title"><i class="bi bi-cart3 text-success"></i> Carrito de Venta</div>

            <div class="mb-3">
                <label class="form-label">Cliente</label>
                <select id="cliente_id" class="form-select form-control-dark">
                    <option value="0">Consumidor Final</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="carritoItems" style="max-height: 300px; overflow-y:auto;">
                <div class="empty-state py-4" id="carritoVacio">
                    <i class="bi bi-cart"></i>
                    <p class="mb-0" style="font-size:.85rem;">Aún no has agregado productos.</p>
                </div>
            </div>

            <hr class="section-divider">

            <div class="mb-2 d-flex justify-content-between">
                <span class="text-secondary">Subtotal</span>
                <strong id="txtSubtotal">Bs 0.00</strong>
            </div>
            <div class="mb-3">
                <label class="form-label">Descuento (Bs)</label>
                <input type="number" min="0" step="0.01" id="descuento" value="0" class="form-control form-control-dark" oninput="renderCarrito()">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span style="font-weight:700;">Total</span>
                <span id="txtTotal" style="font-size:1.4rem; font-weight:800; color: var(--accent-secondary);">Bs 0.00</span>
            </div>

            <form action="<?= BASE_URL ?>/ventas/guardar" method="POST" id="formVenta">
                <input type="hidden" name="cliente_id" id="input_cliente_id">
                <input type="hidden" name="descuento" id="input_descuento">
                <input type="hidden" name="items" id="input_items">
                <button type="submit" class="btn btn-gradient w-100" id="btnConfirmar" disabled>
                    <i class="bi bi-check-circle me-1"></i> Confirmar Venta
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let carrito = [];

function agregarAlCarrito(producto) {
    if (producto.stock <= 0) {
        alert('Este producto no tiene stock disponible.');
        return;
    }
    const existente = carrito.find(i => i.id === producto.id);
    if (existente) {
        if (existente.cantidad + 1 > producto.stock) {
            alert('No hay suficiente stock disponible para "' + producto.nombre + '".');
            return;
        }
        existente.cantidad++;
    } else {
        carrito.push({ ...producto, cantidad: 1 });
    }
    renderCarrito();
}

function cambiarCantidad(id, delta) {
    const item = carrito.find(i => i.id === id);
    if (!item) return;
    const nueva = item.cantidad + delta;
    if (nueva <= 0) {
        carrito = carrito.filter(i => i.id !== id);
    } else if (nueva > item.stock) {
        alert('Stock máximo disponible: ' + item.stock);
    } else {
        item.cantidad = nueva;
    }
    renderCarrito();
}

function quitarItem(id) {
    carrito = carrito.filter(i => i.id !== id);
    renderCarrito();
}

function renderCarrito() {
    const cont = document.getElementById('carritoItems');
    const vacio = document.getElementById('carritoVacio');

    if (carrito.length === 0) {
        cont.innerHTML = '';
        cont.appendChild(vacio);
        document.getElementById('btnConfirmar').disabled = true;
    } else {
        cont.innerHTML = '';
        carrito.forEach(item => {
            const div = document.createElement('div');
            div.className = 'pos-cart-item';
            div.innerHTML = `
                <img src="<?= BASE_URL ?>/public/img/products/${item.imagen}" class="product-thumb">
                <div class="flex-grow-1">
                    <div style="font-size:.82rem; font-weight:600;">${item.nombre}</div>
                    <div style="font-size:.76rem; color:var(--text-secondary);">Bs ${item.precio.toFixed(2)} c/u</div>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-sm btn-outline-soft" onclick="cambiarCantidad(${item.id}, -1)">-</button>
                    <span style="min-width:22px; text-align:center;">${item.cantidad}</span>
                    <button type="button" class="btn btn-sm btn-outline-soft" onclick="cambiarCantidad(${item.id}, 1)">+</button>
                </div>
                <button type="button" class="btn btn-sm text-danger" onclick="quitarItem(${item.id})"><i class="bi bi-x-lg"></i></button>
            `;
            cont.appendChild(div);
        });
        document.getElementById('btnConfirmar').disabled = false;
    }

    const subtotal = carrito.reduce((acc, i) => acc + (i.precio * i.cantidad), 0);
    const descuento = parseFloat(document.getElementById('descuento').value) || 0;
    const total = Math.max(0, subtotal - descuento);

    document.getElementById('txtSubtotal').innerText = 'Bs ' + subtotal.toFixed(2);
    document.getElementById('txtTotal').innerText = 'Bs ' + total.toFixed(2);
}

document.getElementById('filtroProducto').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.producto-item').forEach(el => {
        const match = el.dataset.nombre.includes(q) || el.dataset.codigo.includes(q);
        el.style.display = match ? '' : 'none';
    });
});

document.getElementById('formVenta').addEventListener('submit', function (e) {
    if (carrito.length === 0) {
        e.preventDefault();
        return;
    }
    document.getElementById('input_cliente_id').value = document.getElementById('cliente_id').value;
    document.getElementById('input_descuento').value = document.getElementById('descuento').value || 0;
    document.getElementById('input_items').value = JSON.stringify(carrito.map(i => ({
        producto_id: i.id, cantidad: i.cantidad
    })));
});
</script>
