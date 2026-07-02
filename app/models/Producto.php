<?php
class Producto extends Model
{
    public function all(string $busqueda = '')
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                LEFT JOIN categorias c ON c.id = p.categoria_id
                WHERE p.estado = 1";
        if ($busqueda !== '') {
            $sql .= " AND (p.nombre LIKE :b OR p.codigo LIKE :b)";
        }
        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->db->prepare($sql);
        if ($busqueda !== '') {
            $stmt->bindValue(':b', "%$busqueda%");
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("INSERT INTO productos
            (codigo, nombre, descripcion, categoria_id, precio_compra, precio_venta, stock, stock_minimo, imagen)
            VALUES (?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([
            $data['codigo'], $data['nombre'], $data['descripcion'], $data['categoria_id'] ?: null,
            $data['precio_compra'], $data['precio_venta'], $data['stock'], $data['stock_minimo'],
            $data['imagen']
        ]);
    }

    public function update(int $id, array $data)
    {
        $stmt = $this->db->prepare("UPDATE productos SET
            codigo=?, nombre=?, descripcion=?, categoria_id=?, precio_compra=?, precio_venta=?,
            stock_minimo=?, imagen=? WHERE id=?");
        return $stmt->execute([
            $data['codigo'], $data['nombre'], $data['descripcion'], $data['categoria_id'] ?: null,
            $data['precio_compra'], $data['precio_venta'], $data['stock_minimo'], $data['imagen'], $id
        ]);
    }

    public function delete(int $id)
    {
        $stmt = $this->db->prepare("UPDATE productos SET estado = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function existsCodigo(string $codigo, ?int $ignoreId = null)
    {
        if ($ignoreId) {
            $stmt = $this->db->prepare("SELECT id FROM productos WHERE codigo = ? AND id != ?");
            $stmt->execute([$codigo, $ignoreId]);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM productos WHERE codigo = ?");
            $stmt->execute([$codigo]);
        }
        return (bool)$stmt->fetch();
    }

    /** Ajusta stock (venta = salida, reposición = entrada) y deja registro de auditoría de movimiento */
    public function ajustarStock(int $productoId, int $cantidad, string $tipo, int $usuarioId, string $motivo = '')
    {
        $producto = $this->find($productoId);
        if (!$producto) return false;

        $stockAnterior = (int)$producto['stock'];
        $stockNuevo = $tipo === 'entrada' ? $stockAnterior + $cantidad : $stockAnterior - $cantidad;
        if ($stockNuevo < 0) $stockNuevo = 0;

        $stmt = $this->db->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmt->execute([$stockNuevo, $productoId]);

        $stmt2 = $this->db->prepare("INSERT INTO movimientos_stock
            (producto_id, usuario_id, tipo, cantidad, stock_anterior, stock_nuevo, motivo)
            VALUES (?,?,?,?,?,?,?)");
        $stmt2->execute([$productoId, $usuarioId, $tipo, $cantidad, $stockAnterior, $stockNuevo, $motivo]);

        return true;
    }

    public function entradaManual(int $productoId, int $cantidad, int $usuarioId, string $motivo)
    {
        return $this->ajustarStock($productoId, $cantidad, 'entrada', $usuarioId, $motivo);
    }

    public function stockBajo()
    {
        $stmt = $this->db->query("SELECT p.*, c.nombre AS categoria_nombre
            FROM productos p LEFT JOIN categorias c ON c.id = p.categoria_id
            WHERE p.estado = 1 AND p.stock <= p.stock_minimo
            ORDER BY p.stock ASC");
        return $stmt->fetchAll();
    }

    public function total()
    {
        return $this->db->query("SELECT COUNT(*) as total FROM productos WHERE estado = 1")->fetch()['total'];
    }

    public function valorInventario()
    {
        return $this->db->query("SELECT SUM(stock * precio_venta) as total FROM productos WHERE estado = 1")->fetch()['total'] ?? 0;
    }

    public function movimientos(int $productoId = null)
    {
        $sql = "SELECT m.*, p.nombre AS producto_nombre, u.nombre AS usuario_nombre
                FROM movimientos_stock m
                JOIN productos p ON p.id = m.producto_id
                JOIN usuarios u ON u.id = m.usuario_id";
        if ($productoId) {
            $sql .= " WHERE m.producto_id = " . (int)$productoId;
        }
        $sql .= " ORDER BY m.fecha DESC LIMIT 200";
        return $this->db->query($sql)->fetchAll();
    }
}
