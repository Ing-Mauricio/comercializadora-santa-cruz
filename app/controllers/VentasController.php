<?php
class VentasController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $ventaModel = $this->model('Venta');
        $this->view('ventas/index', ['ventas' => $ventaModel->all()]);
    }

    public function create()
    {
        $this->requireAuth();
        $productoModel = $this->model('Producto');
        $clienteModel  = $this->model('Cliente');
        $this->view('ventas/create', [
            'productos' => $productoModel->all(),
            'clientes'  => $clienteModel->all(),
        ]);
    }

    /** Recibe el carrito en JSON desde el formulario de venta y confirma la transacción */
    public function guardar()
    {
        $this->requireAuth();

        $clienteId = (int)($_POST['cliente_id'] ?? 0);
        $descuento = (float)($_POST['descuento'] ?? 0);
        $itemsJson = $_POST['items'] ?? '[]';
        $items = json_decode($itemsJson, true);

        if (!is_array($items) || count($items) === 0) {
            $_SESSION['flash_error'] = 'Debes agregar al menos un producto a la venta.';
            $this->redirect('ventas/create');
        }

        $productoModel = $this->model('Producto');
        $subtotal = 0;
        $itemsValidados = [];

        foreach ($items as $item) {
            $producto = $productoModel->find((int)$item['producto_id']);
            if (!$producto) continue;

            $cantidad = max(1, (int)$item['cantidad']);
            if ($cantidad > (int)$producto['stock']) {
                $_SESSION['flash_error'] = "Stock insuficiente para '{$producto['nombre']}'. Disponible: {$producto['stock']}.";
                $this->redirect('ventas/create');
            }

            $sub = $cantidad * (float)$producto['precio_venta'];
            $subtotal += $sub;
            $itemsValidados[] = [
                'producto_id'     => $producto['id'],
                'cantidad'        => $cantidad,
                'precio_unitario' => $producto['precio_venta'],
                'subtotal'        => $sub,
            ];
        }

        if (empty($itemsValidados)) {
            $_SESSION['flash_error'] = 'No se pudo procesar la venta. Verifica los productos.';
            $this->redirect('ventas/create');
        }

        $total = max(0, $subtotal - $descuento);

        $ventaModel = $this->model('Venta');
        try {
            $ventaId = $ventaModel->crearVenta(
                ['cliente_id' => $clienteId, 'subtotal' => $subtotal, 'descuento' => $descuento, 'total' => $total],
                $itemsValidados,
                $_SESSION['usuario_id']
            );
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Ocurrió un error al registrar la venta.';
            $this->redirect('ventas/create');
        }

        $venta = $ventaModel->find($ventaId);
        $this->auditar('Ventas', 'Venta registrada',
            "Registró la venta {$venta['numero_factura']} por un total de Bs {$total} (" . count($itemsValidados) . " productos)");

        $_SESSION['flash_ok'] = 'Venta registrada correctamente: ' . $venta['numero_factura'];
        $this->redirect('ventas/show/' . $ventaId);
    }

    public function show($id)
    {
        $this->requireAuth();
        $ventaModel = $this->model('Venta');
        $venta = $ventaModel->find((int)$id);
        if (!$venta) { $this->redirect('ventas'); }
        $detalle = $ventaModel->detalle((int)$id);
        $this->view('ventas/show', ['venta' => $venta, 'detalle' => $detalle]);
    }
}
