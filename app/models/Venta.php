<?php
class Venta extends Model
{
    public function all()
    {
        $sql = "SELECT v.*, u.nombre AS vendedor_nombre,
                       COALESCE(c.nombre, 'Consumidor Final') AS cliente_nombre
                FROM ventas v
                JOIN usuarios u ON u.id = v.usuario_id
                LEFT JOIN clientes c ON c.id = v.cliente_id
                ORDER BY v.fecha DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare("SELECT v.*, u.nombre AS vendedor_nombre,
                COALESCE(c.nombre, 'Consumidor Final') AS cliente_nombre, c.nit_ci
            FROM ventas v
            JOIN usuarios u ON u.id = v.usuario_id
            LEFT JOIN clientes c ON c.id = v.cliente_id
            WHERE v.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function detalle(int $ventaId)
    {
        $stmt = $this->db->prepare("SELECT vd.*, p.nombre AS producto_nombre, p.codigo, p.imagen
            FROM venta_detalle vd
            JOIN productos p ON p.id = vd.producto_id
            WHERE vd.venta_id = ?");
        $stmt->execute([$ventaId]);
        return $stmt->fetchAll();
    }

    /**
     * Crea una venta completa (cabecera + detalle) dentro de una transacción
     * y descuenta el stock de cada producto vendido.
     */
    public function crearVenta(array $cabecera, array $items, int $usuarioId): int
    {
        $this->db->beginTransaction();
        try {
            $numeroFactura = 'FAC-' . str_pad((string)(self::siguienteNumero($this->db)), 6, '0', STR_PAD_LEFT);

            $stmt = $this->db->prepare("INSERT INTO ventas
                (numero_factura, cliente_id, usuario_id, subtotal, descuento, total)
                VALUES (?,?,?,?,?,?)");
            $stmt->execute([
                $numeroFactura, $cabecera['cliente_id'] ?: null, $usuarioId,
                $cabecera['subtotal'], $cabecera['descuento'], $cabecera['total']
            ]);
            $ventaId = (int)$this->db->lastInsertId();

            $stmtDet = $this->db->prepare("INSERT INTO venta_detalle
                (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?,?,?,?,?)");

            $productoModel = new Producto();
            foreach ($items as $item) {
                $stmtDet->execute([
                    $ventaId, $item['producto_id'], $item['cantidad'],
                    $item['precio_unitario'], $item['subtotal']
                ]);
                $productoModel->ajustarStock(
                    $item['producto_id'], (int)$item['cantidad'], 'salida', $usuarioId,
                    'Venta ' . $numeroFactura
                );
            }

            $this->db->commit();
            return $ventaId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private static function siguienteNumero(PDO $db): int
    {
        return (int)$db->query("SELECT COUNT(*) + 1 AS n FROM ventas")->fetch()['n'];
    }

    public function totalVentasHoy()
    {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total),0) AS total FROM ventas WHERE DATE(fecha) = CURDATE() AND estado='completada'");
        return $stmt->fetch()['total'];
    }

    public function totalVentasMes()
    {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total),0) AS total FROM ventas WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE()) AND estado='completada'");
        return $stmt->fetch()['total'];
    }

    public function countHoy()
    {
        return $this->db->query("SELECT COUNT(*) AS total FROM ventas WHERE DATE(fecha) = CURDATE()")->fetch()['total'];
    }

    /** Ventas agrupadas por vendedor (para reportes / auditoría de desempeño) */
    public function ventasPorVendedor(?string $desde = null, ?string $hasta = null)
    {
        $sql = "SELECT u.nombre AS vendedor, COUNT(v.id) AS num_ventas, COALESCE(SUM(v.total),0) AS total_vendido
                FROM ventas v JOIN usuarios u ON u.id = v.usuario_id
                WHERE v.estado = 'completada'";
        $params = [];
        if ($desde) { $sql .= " AND v.fecha >= ?"; $params[] = $desde . ' 00:00:00'; }
        if ($hasta) { $sql .= " AND v.fecha <= ?"; $params[] = $hasta . ' 23:59:59'; }
        $sql .= " GROUP BY u.id ORDER BY total_vendido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Productos más vendidos */
    public function productosMasVendidos(int $limite = 10)
    {
        $sql = "SELECT p.nombre, p.imagen, SUM(vd.cantidad) AS cantidad_vendida, SUM(vd.subtotal) AS total_generado
                FROM venta_detalle vd
                JOIN productos p ON p.id = vd.producto_id
                JOIN ventas v ON v.id = vd.venta_id
                WHERE v.estado = 'completada'
                GROUP BY p.id ORDER BY cantidad_vendida DESC LIMIT $limite";
        return $this->db->query($sql)->fetchAll();
    }

    /** Ventas por rango de fecha (para reportes) */
    public function ventasPorRango(string $desde, string $hasta)
    {
        $stmt = $this->db->prepare("SELECT v.*, u.nombre AS vendedor_nombre,
                COALESCE(c.nombre, 'Consumidor Final') AS cliente_nombre
            FROM ventas v
            JOIN usuarios u ON u.id = v.usuario_id
            LEFT JOIN clientes c ON c.id = v.cliente_id
            WHERE v.fecha BETWEEN ? AND ?
            ORDER BY v.fecha DESC");
        $stmt->execute([$desde . ' 00:00:00', $hasta . ' 23:59:59']);
        return $stmt->fetchAll();
    }

    public function ventasUltimos7Dias()
    {
        $sql = "SELECT DATE(fecha) AS dia, COALESCE(SUM(total),0) AS total
                FROM ventas
                WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND estado='completada'
                GROUP BY DATE(fecha)
                ORDER BY dia ASC";
        return $this->db->query($sql)->fetchAll();
    }
}
