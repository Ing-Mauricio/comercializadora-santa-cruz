<?php
class Auditoria extends Model
{
    public function registrar(?int $usuarioId, string $modulo, string $accion, string $descripcion, ?string $ip)
    {
        $stmt = $this->db->prepare("INSERT INTO auditoria (usuario_id, modulo, accion, descripcion, ip) VALUES (?,?,?,?,?)");
        return $stmt->execute([$usuarioId, $modulo, $accion, $descripcion, $ip]);
    }

    public function all(array $filtros = [])
    {
        $sql = "SELECT a.*, u.nombre AS usuario_nombre
                FROM auditoria a
                LEFT JOIN usuarios u ON u.id = a.usuario_id
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['modulo'])) {
            $sql .= " AND a.modulo = ?";
            $params[] = $filtros['modulo'];
        }
        if (!empty($filtros['usuario_id'])) {
            $sql .= " AND a.usuario_id = ?";
            $params[] = $filtros['usuario_id'];
        }
        if (!empty($filtros['desde'])) {
            $sql .= " AND a.fecha >= ?";
            $params[] = $filtros['desde'] . ' 00:00:00';
        }
        if (!empty($filtros['hasta'])) {
            $sql .= " AND a.fecha <= ?";
            $params[] = $filtros['hasta'] . ' 23:59:59';
        }

        $sql .= " ORDER BY a.fecha DESC LIMIT 300";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function modulosDisponibles()
    {
        return $this->db->query("SELECT DISTINCT modulo FROM auditoria ORDER BY modulo")->fetchAll(PDO::FETCH_COLUMN);
    }
}
