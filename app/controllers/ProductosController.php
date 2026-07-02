<?php
class ProductosController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        $productoModel = $this->model('Producto');
        $busqueda = trim($_GET['q'] ?? '');
        $productos = $productoModel->all($busqueda);
        $this->view('productos/index', ['productos' => $productos, 'busqueda' => $busqueda]);
    }

    public function create()
    {
        $this->requireAuth();
        $categoriaModel = $this->model('Categoria');
        $this->view('productos/create', ['categorias' => $categoriaModel->all()]);
    }

    public function guardar()
    {
        $this->requireAuth();
        $productoModel = $this->model('Producto');

        $data = $this->sanitize($_POST);

        if ($data['codigo'] === '' || $data['nombre'] === '') {
            $_SESSION['flash_error'] = 'El código y nombre del producto son obligatorios.';
            $this->redirect('productos/create');
        }
        if ($productoModel->existsCodigo($data['codigo'])) {
            $_SESSION['flash_error'] = 'Ya existe un producto con ese código.';
            $this->redirect('productos/create');
        }

        $data['imagen'] = $this->subirImagen();

        $productoModel->create($data);
        $this->auditar('Productos', 'Creación', "Creó el producto {$data['nombre']} ({$data['codigo']}) con stock inicial {$data['stock']}");
        $_SESSION['flash_ok'] = 'Producto registrado correctamente.';
        $this->redirect('productos');
    }

    public function edit($id)
    {
        $this->requireAuth();
        $productoModel  = $this->model('Producto');
        $categoriaModel = $this->model('Categoria');
        $producto = $productoModel->find((int)$id);
        if (!$producto) { $this->redirect('productos'); }
        $this->view('productos/edit', ['producto' => $producto, 'categorias' => $categoriaModel->all()]);
    }

    public function actualizar($id)
    {
        $this->requireAuth();
        $productoModel = $this->model('Producto');
        $producto = $productoModel->find((int)$id);
        if (!$producto) { $this->redirect('productos'); }

        $data = $this->sanitize($_POST);

        if ($productoModel->existsCodigo($data['codigo'], (int)$id)) {
            $_SESSION['flash_error'] = 'Ya existe otro producto con ese código.';
            $this->redirect('productos/edit/' . $id);
        }

        $nuevaImagen = $this->subirImagen();
        $data['imagen'] = $nuevaImagen ?: $producto['imagen'];

        $productoModel->update((int)$id, $data);
        $this->auditar('Productos', 'Edición', "Actualizó el producto {$data['nombre']} ({$data['codigo']})");
        $_SESSION['flash_ok'] = 'Producto actualizado correctamente.';
        $this->redirect('productos');
    }

    /** Registrar entrada manual de stock (reposición / compra a proveedor) */
    public function entradaStock($id)
    {
        $this->requireAuth();
        $productoModel = $this->model('Producto');
        $cantidad = (int)($_POST['cantidad'] ?? 0);
        $motivo = trim($_POST['motivo'] ?? 'Reposición de stock');

        if ($cantidad > 0) {
            $producto = $productoModel->find((int)$id);
            $productoModel->entradaManual((int)$id, $cantidad, $_SESSION['usuario_id'], $motivo);
            $this->auditar('Inventario', 'Entrada de stock',
                "Aumentó el stock de '{$producto['nombre']}' en {$cantidad} unidades. Motivo: {$motivo}");
            $_SESSION['flash_ok'] = 'Stock actualizado correctamente.';
        }
        $this->redirect('productos');
    }

    public function eliminar($id)
    {
        $this->requireAuth();
        $productoModel = $this->model('Producto');
        $producto = $productoModel->find((int)$id);
        $productoModel->delete((int)$id);
        $this->auditar('Productos', 'Eliminación', "Eliminó el producto " . ($producto['nombre'] ?? ''));
        $_SESSION['flash_ok'] = 'Producto eliminado.';
        $this->redirect('productos');
    }

    public function movimientos($id = null)
    {
        $this->requireAuth();
        $productoModel = $this->model('Producto');
        $movimientos = $productoModel->movimientos($id ? (int)$id : null);
        $this->view('productos/movimientos', ['movimientos' => $movimientos]);
    }

    private function sanitize(array $post): array
    {
        return [
            'codigo'        => trim($post['codigo'] ?? ''),
            'nombre'        => trim($post['nombre'] ?? ''),
            'descripcion'   => trim($post['descripcion'] ?? ''),
            'categoria_id'  => (int)($post['categoria_id'] ?? 0) ?: null,
            'precio_compra' => (float)($post['precio_compra'] ?? 0),
            'precio_venta'  => (float)($post['precio_venta'] ?? 0),
            'stock'         => (int)($post['stock'] ?? 0),
            'stock_minimo'  => (int)($post['stock_minimo'] ?? 10),
        ];
    }

    /** Sube la imagen del producto a public/img/products/ y devuelve el nombre de archivo (o null) */
    private function subirImagen(): ?string
    {
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $permitidas)) {
            return null;
        }

        $nombreArchivo = uniqid('prod_') . '.' . $ext;
        $destino = __DIR__ . '/../../public/img/products/' . $nombreArchivo;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);

        return $nombreArchivo;
    }
}
