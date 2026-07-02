<?php
class Usuario extends Model
{
    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ? AND estado = 1 LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT id, nombre, email, rol, estado, ultimo_acceso, created_at FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function actualizarUltimoAcceso(int $id)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT id, nombre, email, rol, estado, ultimo_acceso FROM usuarios ORDER BY nombre");
        return $stmt->fetchAll();
    }

    public function contarPorRol(string $rol)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE rol = ? AND estado = 1");
        $stmt->execute([$rol]);
        return $stmt->fetch()['total'];
    }
}
